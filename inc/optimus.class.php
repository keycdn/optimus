<?php


/* Quit */
defined('ABSPATH') OR exit;


/**
* Optimus
*
* @since 0.0.1
*/

class Optimus
{


	/**
	* Pseudo-Konstruktor der Klasse
	*
	* @since   0.0.1
	* @change  0.0.1
	*/

	public static function instance()
	{
		new self();
	}


	/**
	* Konstruktor der Klasse
	*
	* @since   0.0.1
	* @change  1.1.8
	*/

	public function __construct()
	{
		/* Filter */
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) or (defined('DOING_CRON') && DOING_CRON) or (defined('DOING_AJAX') && DOING_AJAX) ) {
			return;
		}

		/* Fire! */
		add_filter(
			'wp_generate_attachment_metadata',
			array(
				'Optimus_Request',
				'optimize_upload_images'
			),
			10,
			2
		);

		/* BE only */
		if ( ! is_admin() ) {
			return;
		}

		/* Hooks */
		add_action(
			'admin_print_styles-upload.php',
			array(
				'Optimus_Media',
				'add_css'
			)
		);
		add_filter(
			'manage_media_columns',
			array(
				'Optimus_Media',
				'manage_columns'
			)
		);
		add_action(
			'manage_media_custom_column',
			array(
				'Optimus_Media',
				'manage_column'
			),
			10,
			2
		);

		add_filter(
			'plugin_row_meta',
			array(
				__CLASS__,
				'add_row_meta'
			),
			10,
			2
		);
		add_filter(
			'plugin_action_links_' .OPTIMUS_BASE,
			array(
				__CLASS__,
				'add_action_link'
			)
		);
		add_action(
			'after_plugin_row_' .OPTIMUS_BASE,
			array(
				'Optimus_HQ',
				'display_key_input'
			)
		);
		add_action(
			'admin_init',
			array(
				'Optimus_HQ',
				'verify_key_input'
			)
		);
		add_action(
			'admin_init',
			array(
				'Optimus_Settings',
				'register_settings'
			)
		);
		add_action(
			'admin_menu',
			array(
				'Optimus_Settings',
				'add_page'
			)
		);
		add_filter(
			'wp_delete_file',
			array(
				'Optimus_Request',
				'delete_converted_file'
			)
		);

		add_action(
			'network_admin_notices',
			array(
				'Optimus_HQ',
				'optimus_hq_notice'
			)
		);
		add_action(
			'admin_notices',
			array(
				'Optimus_HQ',
				'optimus_hq_notice'
			)
		);
		add_action(
			'network_admin_notices',
			array(
				__CLASS__,
				'protected_site_notice'
			)
		);
		add_action(
			'admin_notices',
			array(
				__CLASS__,
				'protected_site_notice'
			)
		);
	}


	/**
	* Hinzufügen der Action-Links
	*
	* @since   1.1.2
	* @change  1.1.2
	*
	* @param   array  $data  Bereits existente Links
	* @return  array  $data  Erweitertes Array mit Links
	*/

	public static function add_action_link($data)
	{
		/* Rechte? */
		if ( ! current_user_can('administrator') ) {
			return $data;
		}

		return array_merge(
			$data,
			array(
				sprintf(
					'<a href="%s">%s</a>',
					add_query_arg(
						array(
							'page' => 'optimus'
						),
						admin_url('options-general.php')
					),
					__('Settings')
				)
			)
		);
	}


	/**
	* Hinzufügen der Meta-Informationen
	*
	* @since   0.0.1
	* @change  1.1.8
	*
	* @param   array   $rows  Array mit Links
	* @param   string  $file  Name des Plugins
	* @return  array          Array mit erweitertem Link
	*/

	public static function add_row_meta($rows, $file)
	{
		/* Restliche Plugins? */
		if ( $file !== OPTIMUS_BASE ) {
			return $rows;
		}

		/* Keine Rechte? */
		if ( ! current_user_can('administrator') ) {
			return $rows;
		}

		/* Add new key link */
		$rows = array_merge(
			$rows,
			array(
				sprintf(
					'<a href="%s">%s</a>',
					add_query_arg(
						array(
							'_optimus_action' => 'rekey'
						),
						network_admin_url('plugins.php#optimus')
					),
					( Optimus_HQ::key() ? 'Anderen Optimus HQ Key eingeben' : 'Optimus HQ aktivieren' )
				)
			)
		);

		/* Add expiration date */
		if ( Optimus_HQ::unlocked() ) {
			$rows = array_merge(
				$rows,
				array(
					sprintf(
						'Optimus HQ Ablaufdatum: %s',
						date( 'd.m.Y', Optimus_HQ::best_before() )
					)
				)
			);
		}

		return $rows;
	}


	/**
	* Entfernt Plugin-Optionen
	*
	* @since   1.1.0
	* @change  1.1.8
	*/

	public static function handle_uninstall_hook()
	{
		delete_option('optimus');
		delete_site_option('optimus_key');
		delete_site_transient('optimus_purchase_time');
		delete_transient('optimus_protected_site');
	}


	/**
	* Activation hook
	*
	* @since   1.1.5
	* @change  1.1.5
	*/

	public static function handle_activation_hook() {
		set_transient(
			'optimus_protected_site',
			self::_is_protected_site()
		);
	}


	/**
	* Ermittelt, ob Bilder von "Außen" erreichbar sind
	*
	* @since   1.1.5
	* @change  1.1.5
	*
	* @return  boolean  true/false  TRUE beim Response-Code >= 400
	*/

	private static function _is_protected_site() {
		/* Get attachments */
		$query = new WP_Query(
			array(
				'post_type'	     => 'attachment',
				'post_status' 	 => 'any',
				'orderby'        => 'rand',
				'posts_per_page' => '1'
			)
		);

		/* Attachment check */
		if ( empty($query->posts[0]) ) {
			return false;
		}

		/* Image attributes */
		if ( ! $image_attr = wp_get_attachment_image_src($query->posts[0]->ID) ) {
			return false;
		}

		/* Image request */
		$response_code = wp_remote_retrieve_response_code(
			wp_safe_remote_get(
				$image_attr[0]
			)
		);

		return ( $response_code >= 400 );
	}


	/**
	* Steuerung der Ausgabe von Admin-Notizen
	*
	* @since   1.1.5
	* @change  1.1.5
	*/

 	public static function protected_site_notice()
	{
		/* Plugins overview only */
		if ( $GLOBALS['pagenow'] !== 'plugins.php' ) {
			return;
		}

		/* No warnings */
		if ( ! get_transient('optimus_protected_site') ) {
			return;
		}

		/* Message */
		show_message(
			sprintf(
				'<div class="updated"><p>%s</p></div>',
				'<strong>Optimus meldet:</strong> Upload-Bilder müssen im Web erreichbar sein. Bitte <a href="http://optimus.io/#require" target="_blank">Systemvoraussetzungen</a> beachten. Plugin-Reaktivierung erneuert den Prüfstatus.'
			)
		);
	}


	/**
	* Rückgabe der Optionen
	*
	* @since   1.1.2
	* @change  1.1.4
	*
	* @return  array  $diff  Array mit Werten
	*/

	public static function get_options()
	{
		return wp_parse_args(
			get_option('optimus'),
			array(
				'copy_markers' => 0,
				'webp_convert' => 0
			)
		);
	}
}
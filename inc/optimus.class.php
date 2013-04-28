<?php


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
	* @change  1.1.4
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
				__CLASS__,
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
				'add_meta_link'
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

		add_action(
			'network_admin_notices',
			array(
				'Optimus_HQ',
				'display_admin_notices'
			)
		);
		add_action(
			'admin_notices',
			array(
				'Optimus_HQ',
				'display_admin_notices'
			)
		);
		add_filter(
			'wp_delete_file',
			array(
				__CLASS__,
				'delete_converted_file'
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
	* Hinzufügen der Meta-Links
	*
	* @since   0.0.1
	* @change  1.1.0
	*
	* @param   array   $input  Array mit Links
	* @param   string  $file   Name des Plugins
	* @return  array           Array mit erweitertem Link
	*/

	public static function add_meta_link($input, $file)
	{
		/* Restliche Plugins? */
		if ( $file !== OPTIMUS_BASE ) {
			return $input;
		}

		/* Keine Rechte? */
		if ( ! current_user_can('administrator') ) {
			return $input;
		}

		/* Optimus HQ */
		if ( Optimus_HQ::unlocked() ) {
			return $input;
		}

		return array_merge(
			$input,
			array(
				sprintf(
					'<a href="%s">Optimus HQ aktivieren</a>',
					add_query_arg(
						array(
							'_optimus_action' => 'rekey'
						),
						network_admin_url('plugins.php#optimus')
					)
				)
			)
		);
	}


	/**
	* Build-Optimierung für Upload-Image samt Thumbs
	*
	* @since   0.0.1
	* @change  1.1.4
	*
	* @param   array    $upload_data    Array mit Upload-Informationen
	* @param   integer  $attachment_id  Attachment ID
	* @return  array    $upload_data    Array mit erneuerten Upload-Informationen
	*/

	public static function optimize_upload_images($upload_data, $attachment_id) {
		/* Upload-Ordner */
		$upload_dir = wp_upload_dir();

		/* WP-Bugfix */
		if ( empty($upload_dir['subdir']) ) {
			$upload_path = $upload_dir['path'];
			$upload_url = $upload_dir['url'];
			$upload_file = $upload_data['file'];
		} else {
			$file_info = pathinfo($upload_data['file']);

			$upload_path = path_join($upload_dir['basedir'], $file_info['dirname']);
			$upload_url = path_join($upload_dir['baseurl'], $file_info['dirname']);
			$upload_file = $file_info['basename'];
		}

		/* Attachment */
		$attachment = get_post($attachment_id);

		/* Mime Type */
		$mime_type = get_post_mime_type($attachment);

		/* Prüfung auf Mime-Type */
		if ( ! self::_allowed_mime_type($mime_type) ) {
			return $upload_data;
		}

		/* Host-Prüfung */
		if ( ! self::_allowed_host_pattern($upload_url) ) {
			return $upload_data;
		}

		/* Optionen */
		$options = self::get_options();

		/* Array mit Dateien */
		$todo_files = array($upload_file);

		/* Thumbs hinzufügen */
		if ( !empty($upload_data['sizes']) ) {
			/* Loopen */
			foreach( $upload_data['sizes'] as $size ) {
				if ( self::_allowed_mime_type($size['mime-type']) ) {
					array_push(
						$todo_files,
						$size['file']
					);
				}
			}

			/* Umkehren */
			$todo_files = array_reverse(
				array_unique($todo_files)
			);
		}

		/* Differenz */
		$diff_filesizes = array();

		/* Files loopen */
		foreach ($todo_files as $file) {
			/* No file? */
			if ( empty($file) ) {
				continue;
			}

			/* Pfad + Datei */
			$upload_url_file = path_join($upload_url, $file);
			$upload_path_file = path_join($upload_path, $file);

			/* Dateigröße */
			$upload_filesize = (int)filesize($upload_path_file);

			/* Zu groß? */
			if ( ! self::_allowed_file_size($mime_type, $upload_filesize) ) {
				continue;
			}

			/* Encoded url */
			$upload_url_file_encoded = urlencode(
				esc_url_raw(
					$upload_url_file,
					array('http', 'https')
				)
			);

			/*  Request: Optimize image */
			$action_response = self::_do_image_action(
				$upload_path_file,
				array(
					'img'     => $upload_url_file_encoded,
					'action'  => 'optimize',
					'target'  => $mime_type,
					'markers' => $options['copy_markers']
				)
			);

			/* Check */
			switch (true) {
				case ( is_array($action_response) ):
					return array_merge(
						$upload_data,
						array(
							'optimus' => $action_response
						)
					);

				case ( $action_response === false ):
					return $upload_data;

				default:
					$response_filesize = $action_response;
			}

			/* Request: WebP convert */
			if ( $options['webp_convert'] && Optimus_HQ::unlocked() ) {
				$action_response = self::_do_image_action(
					self::_replace_file_extension(
						$upload_path_file,
						'webp'
					),
					array(
						'img'     => $upload_url_file_encoded,
						'action'  => 'convert',
						'target'  => 'image/webp'
					)
				);
			}

		  	/* Differenz */
		  	array_push(
		  		$diff_filesizes,
		  		self::_calculate_diff_filesize(
		  			$upload_filesize,
		  			$response_filesize
		  		)
		  	);
		}

		/* Arrays zählen */
		$ordered = count($todo_files);
		$received = count($diff_filesizes);

		/* Mittelwert speichern */
		if ( $received ) {
			$upload_data['optimus'] = array(
				'profit'   => max($diff_filesizes),
				'quantity' => round( $received * 100 / $ordered )
			);
		}

		return $upload_data;
	}


	/**
	* Änderung der Datei-Erweiterung
	*
	* @since   1.1.4
	* @change  1.1.4
	*
	* @param   string  $file       Dateipfad
	* @param   string  $extension  Ziel-Erweiterung
	* @return  string              Umbenannter Dateipfad
	*/

	private static function _replace_file_extension($file, $extension)
	{
		return substr_replace(
			$file,
			$extension,
			strlen(pathinfo($file, PATHINFO_EXTENSION)) * -1
		);
	}


	/**
	* Behandlung der Bilder-Aktionen
	*
	* @since   1.1.4
	* @change  1.1.4
	*
	* @param   string  $file  Dateipfad
	* @param   array   $args  POST-Argumente
	* @return  mixed          Fehlercodes/Dateigröße/FALSE im Fehlerfall
	*/

	private static function _do_image_action($file, $args)
	{
		/* Request senden */
		$response = self::_do_api_request($args);

		/* Inhalt */
		$response_body = (string)wp_remote_retrieve_body($response);

		/* Code */
		$response_code = (int)wp_remote_retrieve_response_code($response);

		/* Kein 200 als Antwort? */
		if ( $response_code !== 200 ) {
			return array(
				'error' => $response_code
			);
		}

		/* Fehler? */
		if ( is_wp_error($response) ) {
			return false;
		}

		/* Optimierte Größe */
		$response_filesize = wp_remote_retrieve_header(
			$response,
			'content-length'
		);

		/* Leere Datei? */
		if ( empty($response_filesize) ) {
			return false;
		}

		/* Inhalt schreiben */
		if ( ! file_put_contents($file, $response_body) ) {
			return false;
		}

		return $response_filesize;
	}


	/**
	* Start der API-Anfrage
	*
	* @since   1.1.4
	* @change  1.1.4
	*
	* @param   array  $args  POST-Argumente
	* @return  array         Array inkl. Rückgabe-Header
	*/

	private static function _do_api_request($args)
	{
		return wp_remote_post(
			sprintf(
				'%s%s',
				'http://api.optimus.io',
				( Optimus_HQ::unlocked() ? sprintf( '/%s/', Optimus_HQ::key() ) : '' )
			),
			array(
				'timeout' => 30,
				'body'	  => $args
			)
		);
	}


	/**
	* Prüfung des erlaubten Bildtyps pro Datei
	*
	* @since   1.1.0
	* @change  1.1.4
	*
	* @param   string   $mime_type  Mime Type
	* @return  boolean              TRUE bei bestehender Prüfung
	*/

	private static function _allowed_mime_type($mime_type)
	{
		/* Leer? */
		if ( empty($mime_type) ) {
			return false;
		}

		/* Quota-Prüfung */
		return array_key_exists(
			$mime_type,
			self::_get_request_quota()
		);
	}


	/**
	* Prüfung der erlaubten Bildgröße pro Dateityp
	*
	* @since   1.1.0
	* @change  1.1.4
	*
	* @param   string   $mime_type  Mime Type
	* @param   integer  $file_size  Bild-Größe
	* @return  boolean              TRUE bei bestehender Prüfung
	*/

	private static function _allowed_file_size($mime_type, $file_size)
	{
		/* Leer? */
		if ( empty($file_size) ) {
			return false;
		}

		/* Quota */
		$request_quota = self::_get_request_quota();

		/* Zu groß? */
		if ( $file_size > $request_quota[$mime_type] ) {
			return false;
		}

		return true;
	}


	/**
	* Prüfung des URL-Hosts auf den festgelegten Muster
	*
	* @since   1.1.0
	* @change  1.1.4
	*
	* @param   string  $url  Zu prüfende URL
	* @return  boolean       TRUE bei bestehender Prüfung
	*/

	private static function _allowed_host_pattern($url)
	{
		/* Leer? */
		if ( empty($url) ) {
			return false;
		}

		/* URL zerlegen */
		$parsed_url = parse_url($url);

		/* Leere Werte? */
		if ( empty($parsed_url['host']) OR empty($parsed_url['path']) ) {
			return false;
		}

		/* Host prüfen */
		if ( filter_var($parsed_url['host'], FILTER_VALIDATE_IP) OR strpos($parsed_url['host'], '.') === false ) {
			return false;
		}

		return true;
	}


	/**
	* Rückgabe der Kontingente pro Optimus Modell
	*
	* @since   1.1.0
	* @change  1.1.1
	*
	* @return  array  Array mit Datensätzen
	*/

	private static function _get_request_quota()
	{
		/* Kontingente */
		$quota = array(
			/* FREE */
			false => array(
				'image/jpeg' => 20 * 1024
			),

			/* HQ */
			true => array(
				'image/jpeg' => 1000 * 1024,
				'image/png'  => 200 * 1024
			)
		);

		return $quota[ Optimus_HQ::unlocked() ];
	}


	/**
	* Entfernt Plugin-Optionen
	*
	* @since   1.1.0
	* @change  1.1.0
	*/

	public static function handle_uninstall_hook()
	{
		delete_site_option('optimus_key');
	}


	/**
	* Löscht erzeugte WebP-Dateien
	*
	* @since   1.1.4
	* @change  1.1.4
	*
	* @param   string  $file  Zu löschende Original-Datei
	* @return  string  $file  Zu löschende Original-Datei
	*/

	public static function delete_converted_file($file) {
		/* Plugin options */
		$options = self::get_options();

		/* Nicht aktiv? */
		if ( ! $options['webp_convert'] OR ! Optimus_HQ::unlocked() ) {
			return $file;
		}

		/* Upload path */
		$upload_path = wp_upload_dir();
		$base_dir = $upload_path['basedir'];

		/* Init converted file */
		$converted_file = $file;

		/* Check for upload path */
		if ( strpos($converted_file, $base_dir) === false ) {
			$converted_file = path_join($base_dir, $converted_file);
		}

		/* Replace to webp extension */
		$converted_file = self::_replace_file_extension(
			$converted_file,
			'webp'
		);

		/* Remove if exists */
		if ( file_exists($converted_file) ) {
			@unlink($converted_file);
		}

		return $file;
	}


	/**
	* Ermittelt die Differenz der Dateigröße
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   intval  $before  Größe vor der Optimierung in Bytes
	* @param   intval  $after   Größe nach der Optimierung in Bytes
	* @return  intval           Ermittelte Differenz
	*/

	private static function _calculate_diff_filesize($before, $after)
	{
		/* Konvertieren */
		$before = (int)$before;
		$after = (int)$after;

		return sprintf(
			'%d',
			ceil( ($before - $after) * 100 / $before )
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
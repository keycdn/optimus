<?php


/* Quit */
defined('ABSPATH') OR exit;


/**
* Optimus_HQ
*
* @since 1.1.0
*/

class Optimus_HQ
{


	/**
	* Ausgabe des Eingabefeldes für den Optimus HQ Key
	*
	* @since   1.1.0
	* @change  1.1.6
	*/

	public static function display_key_input()
	{
		/* Keine Rechte? */
		if ( ! current_user_can('administrator') ) {
			return;
		}

		/* Überspringen? */
		if ( empty($_GET['_optimus_action']) OR $_GET['_optimus_action'] !== 'rekey' ) {
			return;
		} ?>

		<tr class="plugin-update-tr">
  			<td colspan="3" class="plugin-update">
  				<div class="update-message">
  					<form action="<?php echo network_admin_url('plugins.php') ?>" method="post">
						<input type="hidden" name="_optimus_action" value="verify" />
						<?php wp_nonce_field('_optimus_nonce') ?>

	  					<label for="_optimus_key">
	  						Optimus HQ Key:
	  						<input type="text" name="_optimus_key" id="_optimus_key" maxlength="17" pattern="\w{17}" />
	  					</label>

		  				<input type="submit" name="submit" value="Aktivieren" class="button button-primary regular" />
		  				<a href="<?php echo network_admin_url('plugins.php') ?>" class="button">Abbrechen</a>
		  			</form>
  				</div>
  			</td>
	  	</tr>

	  	<style>
		  	#optimus + .plugin-update-tr .update-message {
		  		margin-top: 12px;
		  	}
	  		#optimus + .plugin-update-tr .update-message::before {
	  			display: none;
	  		}
	  		#optimus + .plugin-update-tr label {
	  			line-height: 30px;
	  			vertical-align: top;
	  		}
	  		#optimus + .plugin-update-tr input[type="text"] {
	  			width: 300px;
	  			margin-left: 10px;
	  		}
	  	</style>
	<?php }


	/**
	* Prüfung und Speicherung des Optimus HQ Keys
	*
	* @since   1.1.0
	* @change  1.1.0
	*/

 	public static function verify_key_input()
	{
		/* Kein Request? */
		if ( empty($_POST['_optimus_action']) OR $_POST['_optimus_action'] !== 'verify' ) {
			return;
		}

		/* Prüfung des Keys */
		if ( empty($_POST['_optimus_key']) OR ! preg_match('/^\w{17}$/', $_POST['_optimus_key']) ) {
			return;
		}

		/* Security */
		check_admin_referer('_optimus_nonce');

		/* Speichern */
		update_site_option(
			'optimus_key',
			$_POST['_optimus_key']
		);

		/* Redirect */
		wp_safe_redirect(
			add_query_arg(
				array(
					'_optimus_notice' => 'licensed'
				),
				network_admin_url('plugins.php')
			)
		);

		die();
	}


	/**
	* Steuerung der Ausgabe von Admin-Notizen
	*
	* @since   1.1.0
	* @change  1.1.7
	*/

 	public static function optimus_hq_notice()
	{
		/* Typ festlegen */
		if ( ! empty($_GET['_optimus_notice']) && $_GET['_optimus_notice'] === 'licensed' ) {
			$type = 'licensed';
		} else if ( ! self::unlocked() ) {
			if ( $GLOBALS['pagenow'] === 'plugins.php' OR @get_current_screen()->id === get_plugin_page_hookname('optimus', 'options-general.php') ) {
				$type = 'unlocked';
			}
		}

		/* Leer? */
		if ( empty($type) ) {
			return;
		}

		/* Matching */
		switch( $type ) {
			case 'licensed':
				$msg = 'Vielen Dank für die Nutzung von <strong>Optimus HQ</strong>. Wissenswertes rund um das Plugin auf der offiziellen Website <a href="http://optimus.io" target="_blank">optimus.io</a>';
			break;

			case 'unlocked':
				$msg = 'Optimus ist aktuell eingeschränkt nutzbar. <strong>Optimus HQ</strong> beherrscht mehrere Bildformate und komprimiert größere Dateien. Details auf <a href="http://optimus.io" target="_blank">optimus.io</a>';
			break;

			default:
				return;
		}

		/* Ausgabe */
		show_message(
			sprintf(
				'<div class="updated"><p>%s</p></div>',
				$msg
			)
		);
	}


	/**
	* Interne Prüfung auf Optimus HQ
	* P.S. Manipulation bringt nichts, da serverseitige Prüfung. Peace!
	*
	* @since   1.1.0
	* @change  1.1.0
	*
	* @return  boolean  TRUE wenn Optimus HQ
	*/

	public static function unlocked()
	{
		return (bool)self::key();
	}


	/**
	* Rückgabe des Optimus HQ Keys
	*
	* @since   1.1.0
	* @change  1.1.0
	*
	* @return  string  Optimus HQ Key
	*/

	public static function key()
	{
		return get_site_option('optimus_key');
	}
}
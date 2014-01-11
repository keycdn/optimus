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

		/* Reset purchase_time */
		delete_site_transient('optimus_purchase_time');

		/* Speichern */
		update_site_option(
			'optimus_key',
			$_POST['_optimus_key']
		);

		/* Redirect */
		if ( self::unlocked() ) {
			wp_safe_redirect(
				add_query_arg(
					array(
						'_optimus_notice' => 'unlocked'
					),
					network_admin_url('plugins.php')
				)
			);
		} else {
			wp_safe_redirect(
				network_admin_url('plugins.php')
			);
		}

		die();
	}


	/**
	* Steuerung der Ausgabe von Admin-Notizen
	*
	* @since   1.1.0
	* @change  1.1.8
	*/

 	public static function optimus_hq_notice()
	{
		/* Check admin pages */
		if ( $GLOBALS['pagenow'] !== 'plugins.php' AND @get_current_screen()->id !== get_plugin_page_hookname('optimus', 'options-general.php') ) {
			return;
		}

		/* Get message type */
		if ( ! empty($_GET['_optimus_notice']) && $_GET['_optimus_notice'] === 'unlocked' ) {
			$type = 'unlocked';
		} else if ( self::locked() ) {
			$type = ( self::key() ? 'expired' : 'locked' );
		}

		/* Empty? */
		if ( empty($type) ) {
			return;
		}

		/* Matching */
		switch( $type ) {
			case 'unlocked':
				$msg = 'Vielen Dank für die Nutzung von <strong>Optimus HQ</strong>. Wissenswertes und Aktualisierungen rund um das Plugin auf <a href="https://plus.google.com/b/114450218898660299759/114450218898660299759/posts" target="_blank">Google+</a>.';
				$class = 'updated';
			break;

			case 'locked':
				$msg = 'Optimus ist aktuell nur eingeschränkt nutzbar. <strong>Optimus HQ</strong> beherrscht mehrere Bildformate und komprimiert größere Dateien. Details auf <a href="http://optimus.io" target="_blank">optimus.io</a>';
				$class = 'error';
			break;

			case 'expired':
				$msg = '<strong>Optimus HQ Key</strong> ist nicht gültig, da wahrscheinlich abgelaufen. Erworben kann ein neuer Optimus HQ Key auf <a href="http://optimus.io" target="_blank">optimus.io</a>';
				$class = 'error';
			break;

			default:
				return;
		}

		/* Output */
		show_message(
			sprintf(
				'<div class="%s"><p>%s</p></div>',
				$class,
				$msg
			)
		);
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


	/**
	* Interne Prüfung auf Optimus HQ
	* P.S. Manipulation bringt nichts, da serverseitige Prüfung. Peace!
	*
	* @since   1.1.0
	* @change  1.1.8
	*
	* @return  boolean  TRUE wenn Optimus HQ aktiv
	*/

	public static function unlocked()
	{
		return (bool)self::best_before();
	}


	/**
	* Interne Prüfung auf Optimus HQ
	* P.S. Manipulation bringt nichts, da serverseitige Prüfung. Peace!
	*
	* @since   1.1.8
	* @change  1.1.8
	*
	* @return  boolean  TRUE wenn Optimus HQ inaktiv
	*/

	public static function locked()
	{
		return ! self::unlocked();
	}


	/**
	* Ablaufdatum von Optimus HQ
	* P.S. Manipulation bringt nichts, da serverseitige Prüfung. Peace!
	*
	* @since   1.1.8
	* @change  1.1.8
	*
	* @return  mixed  FALSE/Date  Datum im Erfolgsfall
	*/

	public static function best_before()
	{
		/* Key exists? */
		if ( ! $key = self::key() ) {
			return false;
		}

		/* Timestamp from cache */
		if ( ! $purchase_time = get_site_transient('optimus_purchase_time') ) {
			$response = wp_safe_remote_get(
				sprintf(
					'%s/%s',
					'http://verify.optimus.io',
					$key
				)
			);

			/* Set the timestamp */
			if ( is_wp_error($response) ) {
				$purchase_time = -1;
			} else {
				$purchase_time = wp_remote_retrieve_body($response);
			}

			/* Validate the timestamp */
			if ( ! ( is_numeric($purchase_time) && $purchase_time <= PHP_INT_MAX && $purchase_time >= ~PHP_INT_MAX ) ) {
				$purchase_time = -1;
			}

			/* Store on cache */
			set_site_transient(
				'optimus_purchase_time',
				$purchase_time,
				4 * WEEK_IN_SECONDS
			);
		}

		/* Invalid purchase time? */
		if ( (int)$purchase_time <= 0 ) {
			return false;
		}

		/* Set expiration time */
		$expiration_time = strtotime(
			'+1 year',
			$purchase_time
		);

		/* Expired time? */
		if ( $expiration_time < time() ) {
			return false;
		}

		return $expiration_time;
	}
}
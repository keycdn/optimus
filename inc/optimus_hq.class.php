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


	/* Private vars */
	private static $_is_locked = NULL;
	private static $_is_unlocked = NULL;


	/**
	* Interne Prüfung auf Optimus HQ
	* P.S. Manipulation bringt nichts, da serverseitige Prüfung. Peace!
	*
	* @since   1.1.9
	* @change  1.1.9
	*
	* @return  boolean  TRUE wenn Optimus HQ nicht freigeschaltet
	*/

	public static function is_locked()
	{
		if ( self::$_is_locked !== NULL ) {
			return self::$_is_locked;
		}

		$is_locked = ! (bool)self::best_before();

		self::$_is_locked = $is_locked;
		self::$_is_unlocked = ! $is_locked;

		return $is_locked;
	}


	/**
	* Interne Prüfung auf Optimus HQ
	* P.S. Manipulation bringt nichts, da serverseitige Prüfung. Peace!
	*
	* @since   1.1.9
	* @change  1.1.9
	*
	* @return  boolean  TRUE wenn Optimus HQ freigeschaltet
	*/

	public static function is_unlocked()
	{
		if ( self::$_is_unlocked !== NULL ) {
			return self::$_is_unlocked;
		}

		return ! self::is_locked();
	}


	/**
	* Ablaufdatum von Optimus HQ
	* P.S. Manipulation bringt nichts, da serverseitige Prüfung. Peace!
	*
	* @since   1.1.9
	* @change  1.1.9
	*
	* @return  mixed  FALSE/Date  Datum im Erfolgsfall
	*/

	public static function best_before()
	{
		/* Key exists? */
		if ( ! $key = self::get_key() ) {
			return false;
		}

		/* Timestamp from cache */
		if ( ! $purchase_time = self::get_purchase_time() ) {
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

			/* Store as option */
			self::_update_purchase_time($purchase_time);
		}

		/* Invalid purchase time? */
		if ( (int)$purchase_time <= 0 ) {
			self::_delete_key();

			return false;
		}

		/* Set expiration time */
		$expiration_time = strtotime(
			'+1 year',
			$purchase_time
		);

		/* Expired time? */
		if ( $expiration_time < time() ) {
			self::_delete_key();

			return false;
		}

		return $expiration_time;
	}


	/**
	* Return the license key
	*
	* @since   1.1.0
	* @change  1.1.9
	*
	* @return  string  Optimus HQ Key
	*/

	public static function get_key()
	{
		return get_site_option('optimus_key');
	}


	/**
	* Update the license key
	*
	* @since   1.1.0
	* @change  1.1.9
	*
	* @return  mixed  $value  Optimus HQ Key value
	*/

	private static function _update_key($value)
	{
		update_site_option(
			'optimus_key',
			$value
		);
	}


	/**
	* Delete the license key
	*
	* @since   1.1.9
	* @change  1.1.9
	*/

	private static function _delete_key()
	{
		delete_site_option('optimus_key');
	}


	/**
	* Return the purchase timestamp
	*
	* @since   1.1.9
	* @change  1.1.9
	*
	* @return  string  Optimus HQ purchase timestamp
	*/

	public static function get_purchase_time()
	{
		return get_site_option('optimus_purchase_time');
	}


	/**
	* Update the purchase timestamp
	*
	* @since   1.1.9
	* @change  1.1.9
	*
	* @return  integer  $value  Purchase time as a timestamp
	*/

	private static function _update_purchase_time($value)
	{
		update_site_option(
			'optimus_purchase_time',
			$value
		);
	}


	/**
	* Delete the purchase timestamp
	*
	* @since   1.1.9
	* @change  1.1.9
	*/

	private static function _delete_purchase_time()
	{
		delete_site_option('optimus_purchase_time');
	}


	/**
	* Ausgabe des Eingabefeldes für den Optimus HQ Key
	*
	* @since   1.1.0
	* @change  1.1.6
	*/

	public static function display_key_input()
	{
		/* Keine Rechte? */
		if ( ! current_user_can('manage_options') ) {
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
	* @change  1.1.9
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

		/* Delete purchase_time */
		self::_delete_purchase_time();

		/* Store current key */
		self::_update_key($_POST['_optimus_key']);

		/* Redirect */
		wp_safe_redirect(
			add_query_arg(
				array(
					'_optimus_notice' => ( self::is_locked() ? 'locked' : 'unlocked' )
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
	* @change  1.1.9
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
		} else if ( self::is_locked() ) {
			$type = ( self::get_purchase_time() ? 'expired' : 'locked' );
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
				$msg = '<strong>Optimus HQ Key</strong> ist nicht länger gültig, da abgelaufen. Erworben kann ein neuer Optimus HQ Key auf <a href="http://optimus.io" target="_blank">optimus.io</a>';
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
}
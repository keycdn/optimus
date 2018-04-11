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
        $purchase_time = self::get_purchase_time();

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

            /* try to renew the licence once every 10 minutes */
            $transient = get_transient('optimus_renew_licence');
            if ( empty($transient) ) {
                set_transient('optimus_renew_licence', true, 600);

                $purchase_time_new = self::get_purchase_time(true);

                if ( $purchase_time_new <= $purchase_time ) {
                    /* unchanged */
                    return false;
                }

                /* re-calculate expiry time */
                $expiration_time = strtotime(
                    '+1 year',
                    $purchase_time_new
                );

                return $expiration_time;
            }

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
    * Return the purchase timestamp
    *
    * @since   1.1.9
    * @change  1.5.0
    *
    * @return  string  Optimus HQ purchase timestamp
    */

    public static function get_purchase_time($renew = false)
    {
        $purchase_time = get_site_option('optimus_purchase_time', 0);

        if ( $purchase_time == 0 || $renew == true) {
            if ( ! $key = self::get_key() ) {
                return false;
            }

            $response = wp_safe_remote_get(
                sprintf(
                    '%s/%s',
                    'https://verify.optimus.io',
                    $key
                )
            );

            /* Exit on error */
            if ( is_wp_error($response) ) {
                wp_die( $response->get_error_message() );
            }

            /* Initial state */
            $purchase_time = -1;

            /* Set the timestamp */
            if ( wp_remote_retrieve_response_code($response) === 200 ) {
                $purchase_time = (int) wp_remote_retrieve_body($response);
            }

            /* Store as option */
            update_site_option(
                'optimus_purchase_time',
                $purchase_time
            );

        }

        return $purchase_time;
    }


    /**
    * Ausgabe des Eingabefeldes für den Optimus HQ Key
    *
    * @since   1.1.0
    * @change  1.3.2
    */

    public static function display_key_input()
    {
        /* Plausibility check */
        if ( empty($_GET['_optimus_action']) OR $_GET['_optimus_action'] !== 'rekey' ) {
            return;
        }

        /* Capability check */
        if ( ! current_user_can('manage_options') ) {
            return;
        } ?>

        <tr class="plugin-update-tr">
              <td colspan="3" class="plugin-update">
                  <div class="update-message">
                      <form action="<?php echo network_admin_url('plugins.php') ?>" method="post">
                        <input type="hidden" name="_optimus_action" value="verify" />
                        <?php wp_nonce_field('_optimus__key_nonce') ?>

                          <label for="_optimus_key">
                              Optimus HQ Key:
                              <input type="text" name="_optimus_key" id="_optimus_key" maxlength="24" pattern="[A-Z0-9]{17,24}" />
                          </label>

                          <input type="submit" name="submit" value="<?php _e("Activate", "optimus"); ?>" class="button button-primary regular" />
                          <a href="<?php echo network_admin_url('plugins.php') ?>" class="button"><?php _e("Cancel", "optimus"); ?></a>
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
    * Verify und store the Optimus HQ key
    *
    * @since   1.1.0
    * @change  1.3.2
    */

    public static function verify_key_input()
    {
        /* Action check */
        if ( empty($_POST['_optimus_action']) OR $_POST['_optimus_action'] !== 'verify' ) {
            return;
        }

        /* Empty input? */
        if ( empty($_POST['_optimus_key']) ) {
            return;
        }

        /* Nonce check */
        check_admin_referer('_optimus__key_nonce');

        /* Capability check */
        if ( ! current_user_can('manage_options') ) {
            return;
        }

        /* Sanitize input */
        $optimus_key = sanitize_text_field($_POST['_optimus_key']);

        /* Advanced check */
        if ( ! preg_match('/^[A-Z0-9]{17,24}$/', $optimus_key) ) {
            return;
        }

        /* Delete purchase_time */
        delete_site_option('optimus_purchase_time');

        /* Store current key */
        self::_update_key($optimus_key);

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
    * @change  1.2.0
    */

     public static function optimus_hq_notice()
    {
        /* Check admin pages */
        if ( ! in_array($GLOBALS['pagenow'], array('plugins.php', 'index.php') ) ) {
            return;
        }

        /* Get message type */
        if ( ! empty($_GET['_optimus_notice']) && $_GET['_optimus_notice'] === 'unlocked' ) {
            $type = 'unlocked';
        } else if ( self::is_locked() ) {
            if ( self::get_purchase_time() ) {
                $type = 'expired';
            } else if ( get_transient('optimus_activation_hook_in_use') ) {
                $type = 'locked';
            }
        }

        /* Empty? */
        if ( empty($type) ) {
            return;
        }

        /* Matching */
        switch( $type ) {
            case 'unlocked':
                $msg = __("<p>Thank you for using <strong>Optimus HQ</strong>. Follow us on <a href=\"https://twitter.com/optimusHQ\" target=\"_blank\">Twitter</a> to get the latest news and updates.</p>", "optimus");
                $class = 'updated';
            break;

            case 'locked':
                $msg = __("<p><strong>Optimus</strong> is free of charge, the functionality is limited to the essential features.</p><p><strong>Optimus HQ</strong> (Premium) on the other hand can handle several image formats, compress larger files and connects through HTTPS. <span class=\"no-break\">More details on <a href=\"https://optimus.io\" target=\"_blank\">optimus.io</a></span><br /><br /><em>This information is displayed for 60 seconds and will not appear again.</em></p>", "optimus");
                $class = 'error';
            break;

            case 'expired':
                $msg = __("<p><strong>Optimus HQ license key</strong> has expired. Get a new Optimus HQ license key on <a href=\"https://optimus.io/en/\" target=\"_blank\">optimus.io</a>. Thank you!", "optimus");
                $class = 'error';
            break;

            default:
                return;
        }

        /* Output */
        show_message(
            sprintf(
                '<div class="%s">%s</div>',
                $class,
                $msg
            )
        );
    }
}

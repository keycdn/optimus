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
    * @change  1.4.6
    */

    public function __construct()
    {
        /* Get plugin options */
        $options = Optimus::get_options();

        /* Remove converted files */
        add_filter(
            'wp_delete_file',
            array(
                'Optimus_Request',
                'delete_converted_file'
            )
        );

        /* WP Retina 2x compatibility (existing images) */
        add_action(
            'wr2x_retina_file_added',
            array(
                'Optimus_Request',
                'optimize_wr2x_image'
            ),
            10,
            2
        );

        /* Filter */
        if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) OR (defined('DOING_CRON') && DOING_CRON) OR (defined('DOING_AJAX') && DOING_AJAX) OR (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) ) {
            return;
        }

        /* BE only */
        if ( ! is_admin() ) {
            return;
        }

        /* Hooks */
        add_action(
            'admin_enqueue_scripts',
            array(
                __CLASS__,
                'add_js_css'
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
        add_action(
            'admin_menu',
            array(
                'Optimus_Management',
                'add_bulk_optimizer_page'
            )
        );

        /* Admin notices */
        add_action(
            'all_admin_notices',
            array(
                __CLASS__,
                'optimus_requirements_check'
            )
        );
        add_action(
            'all_admin_notices',
            array(
                'Optimus_HQ',
                'optimus_hq_notice'
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
        if ( ! current_user_can('manage_options') ) {
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
                    __("Settings")
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
        if ( ! current_user_can('manage_options') ) {
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
                        network_admin_url('plugins.php#_optimus_key')
                    ),
                    ( Optimus_HQ::get_key() ? __("Enter a different Optimus HQ license key", "optimus") : '<span style="color:#dd3d36">'.__("Activate Optimus HQ", "optimus").'</span>' )
                )
            )
        );

        /* Add expiration date */
        if ( Optimus_HQ::is_unlocked() ) {
            $rows = array_merge(
                $rows,
                array(
                    sprintf(
                        __("Optimus HQ expiry date: %s", "optimus"),
                        date( 'd.m.Y', Optimus_HQ::best_before() )
                    )
                )
            );
        }

        return $rows;
    }


    /**
    * Run uninstall hook
    *
    * @since   1.1.0
    * @change  1.1.8
    */

    public static function handle_uninstall_hook()
    {
        delete_option('optimus');
        delete_site_option('optimus_key');
        delete_site_option('optimus_purchase_time');
    }


    /**
    * Run activation hook
    *
    * @since   1.2.0
    * @change  1.2.0
    */

    public static function handle_activation_hook() {
        set_transient(
            'optimus_activation_hook_in_use',
            1,
            MINUTE_IN_SECONDS
        );
    }


    /**
    * Check plugin requirements
    *
    * @since   1.3.1
    * @change  1.3.1
    */

    public static function optimus_requirements_check() {
        /* WordPress version check */
        if ( version_compare($GLOBALS['wp_version'], OPTIMUS_MIN_WP.'alpha', '<') ) {
            show_message(
                sprintf(
                    '<div class="error"><p>%s</p></div>',
                    sprintf(
                        __("Optimus is optimized for WordPress %s. Please disable the plugin or upgrade your WordPress installation (recommended).", "optimus"),
                        OPTIMUS_MIN_WP
                    )
                )
            );
        }

        /* cURL check */
        if ( ! WP_Http_Curl::test() ) {
            show_message(
                sprintf(
                    '<div class="error"><p>%s</p></div>',
                    sprintf(
                        __("Optimus requires the <a href=\"%s\" target=\"_blank\">cURL-Library</a>. Please contact your hosting provider to get cURL installed.", "optimus"),
                        'https://php.net/manual/en/intro.curl.php'
                    )
                )
            );
        }
    }


    /**
    * Return plugin options
    *
    * @since   1.1.2
    * @change  1.4.0
    *
    * @return  array  $diff  Data pairs
    */

    public static function get_options()
    {
        return wp_parse_args(
            get_option('optimus'),
            array(
                'copy_markers'      => 0,
                'webp_convert'      => 0,
                'webp_keeporigext'  => 0,
                'keep_original'     => 0,
                'secure_transport'  => 0,
                'manual_optimize'   => 0
            )
        );
    }


    /**
    * Hinzufügen von JavaScript und Styles
    *
    * @since   1.3.8
    * @change  1.3.8
    */
    public static function add_js_css()
    {
        wp_register_style(
            'optimus-styles',
            plugins_url(
                'css/styles.css',
                OPTIMUS_FILE
            )
        );
        wp_enqueue_style('optimus-styles');

        $handle = 'optimus-scripts';

        wp_register_script( $handle, plugins_url('js/scripts.js', OPTIMUS_FILE), array('jquery'), '2', TRUE );
        wp_localize_script($handle, 'optimusOptimize', array(
            'nonce' => wp_create_nonce('optimus-optimize'),
            'bulkDone' => __("All images have been optimized.", "optimus"),
            'bulkAction' => __("Optimize images", "optimus"),
            'optimizing' => __("Optimizing", "optimus"),
            'optimized' => __("optimized", "optimus"),
            'internalError' => __("Internal error", "optimus"),
            'waiting' => __("Waiting", "optimus"),
        ));
        wp_enqueue_script($handle);
    }
}

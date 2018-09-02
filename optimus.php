<?php
/*
Plugin Name: Optimus
Text Domain: optimus
Description: Smart compression and optimization of uploaded images in WordPress. Automatic, reliable, effective.
Author: KeyCDN
Author URI: https://www.keycdn.com
Plugin URI: https://optimus.io
License: GPLv2 or later
Version: 1.6.0
*/

/*
Copyright (C)  2012-2017 KeyCDN

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/


/* Check & Quit */
defined('ABSPATH') OR exit;


/* Konstanten */
define('OPTIMUS_FILE', __FILE__);
define('OPTIMUS_DIR', dirname(__FILE__));
define('OPTIMUS_BASE', plugin_basename(__FILE__));
define('OPTIMUS_MIN_WP', '3.8');


/* Hook optimus admin init */
add_action('init', 'optimus_admin_init');
function optimus_admin_init()
{
    if (is_admin()) {
        load_plugin_textdomain( 'optimus', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );

        add_action(
            'wp_ajax_optimus_optimize_image',
            array(
                'Optimus_Request',
                'optimize_image'
            )
        );
        add_action(
            'admin_action_optimus_bulk_optimizer',
            array(
                'Optimus_Management',
                'bulk_optimizer_media'
            )
        );
    }
}


/* Admin & XMLRPC only */
if ( is_admin() OR (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) ) {
    add_action(
        'plugins_loaded',
        array(
            'Optimus',
            'instance'
        )
    );
}

/* WP-CLI */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
    require_once(OPTIMUS_DIR."/inc/optimus_cli.class.php");

    add_action(
        'init',
        array(
            'Optimus_CLI',
            'add_commands'
        )
    );
}

/* Uninstall */
register_uninstall_hook(
    __FILE__,
    array(
        'Optimus',
        'handle_uninstall_hook'
    )
);


/* Activation */
register_activation_hook(
    __FILE__,
    array(
        'Optimus',
        'handle_activation_hook'
    )
);


/* Autoload Init */
spl_autoload_register('optimus_autoload');

/* Check if manual optimization is enabled, hook in if not */
$options = Optimus::get_options();

if ( ! $options['manual_optimize'] ) {
    add_action('wp_generate_attachment_metadata',
        array(
            'Optimus_Request',
            'optimize_upload_images',
        ),
        10,
        2
    );
}

/* Autoload Funktion */
function optimus_autoload($class) {
    if ( in_array($class, array('Optimus', 'Optimus_HQ', 'Optimus_Management', 'Optimus_Settings', 'Optimus_Media', 'Optimus_Request')) ) {
        require_once(
            sprintf(
                '%s/inc/%s.class.php',
                OPTIMUS_DIR,
                strtolower($class)
            )
        );
    }
}

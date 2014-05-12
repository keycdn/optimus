<?php
/*
Plugin Name: Optimus
Description: Verlustfreie Komprimierung der Upload-Bilder in WordPress. Automatisch, zuverlässig, wirkungsvoll.
Author: Sergej M&uuml;ller
Author URI: http://wpcoder.de
Plugin URI: https://optimus.io
Version: 1.3.2
*/


/* Check & Quit */
defined('ABSPATH') OR exit;


/* Konstanten */
define('OPTIMUS_FILE', __FILE__);
define('OPTIMUS_BASE', plugin_basename(__FILE__));
define('OPTIMUS_MIN_WP', '3.8');


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

/* Autoload Funktion */
function optimus_autoload($class) {
	if ( in_array($class, array('Optimus', 'Optimus_HQ', 'Optimus_Settings', 'Optimus_Media', 'Optimus_Request')) ) {
		require_once(
			sprintf(
				'%s/inc/%s.class.php',
				dirname(__FILE__),
				strtolower($class)
			)
		);
	}
}
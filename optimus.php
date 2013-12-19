<?php
/*
Plugin Name: Optimus
Description: Reduzierung der Dateigröße während des Uploads der Bilder in die Mediathek. Effektive und geschwinde Komprimierung ohne Qualitätsverlust.
Author: Sergej M&uuml;ller
Author URI: http://wpcoder.de
Plugin URI: http://optimus.io
Version: 1.1.7
*/


/* Sicherheitsabfrage */
if ( ! class_exists('WP') ) {
	die();
}


/* Konstanten */
define('OPTIMUS_FILE', __FILE__);
define('OPTIMUS_BASE', plugin_basename(__FILE__));


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

/* Activate */
register_activation_hook(
	__FILE__,
	array(
		'Optimus',
		'handle_activation_hook'
	)
);

/* Uninstall */
register_uninstall_hook(
	__FILE__,
	array(
		'Optimus',
		'handle_uninstall_hook'
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
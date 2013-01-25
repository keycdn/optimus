<?php
/*
Plugin Name: Optimus
Description: Reduzierung der Dateigröße während des Uploads der Bilder in die Mediathek. Effektive und geschwinde Komprimierung ohne Qualitätsverlust.
Author: Sergej M&uuml;ller
Author URI: http://wpcoder.de
Plugin URI: http://optimus.io
Version: 1.1.2
*/


/* Sicherheitsabfrage */
if ( !class_exists('WP') ) {
	die();
}


/* Konstanten */
define('OPTIMUS_FILE', __FILE__);
define('OPTIMUS_BASE', plugin_basename(__FILE__));


/* Hooks */
add_action(
	'plugins_loaded',
	array(
		'Optimus',
		'instance'
	)
);
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
	if ( in_array($class, array('Optimus', 'Optimus_HQ', 'Optimus_Settings', 'Optimus_Media')) ) {
		require_once(
			sprintf(
				'%s/inc/%s.class.php',
				dirname(__FILE__),
				strtolower($class)
			)
		);
	}
}
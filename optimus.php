<?php
/*
Plugin Name: Optimus
Description: Reduzierung der Dateigröße während des Uploads der Bilder in die Mediathek. Effektive und geschwinde Komprimierung ohne Qualitätsverlust.
Author: Sergej M&uuml;ller
Author URI: http://wpseo.de
Plugin URI: http://wordpress.org/extend/plugins/optimus/
Version: 0.0.6
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


/* Autoload Init */
spl_autoload_register('optimus_autoload');

/* Autoload Funktion */
function optimus_autoload($class) {
	if ( in_array($class, array('Optimus')) ) {
		require_once(
			sprintf(
				'%s/inc/%s.class.php',
				dirname(__FILE__),
				strtolower($class)
			)
		);
	}
}
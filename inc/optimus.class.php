<?php


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
	* @change  0.0.2
	*/

	public function __construct()
	{
		/* Filter */
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) or (defined('DOING_CRON') && DOING_CRON) or (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) or (defined('DOING_AJAX') && DOING_AJAX) ) {
			return;
		}

		/* BE only */
		if ( !is_admin() ) {
			return;
		}
		
		/* Hooks */
		add_action(
			'admin_init',
			array(
				__CLASS__,
				'admin_init'
			)
		);
		add_action(
			'admin_print_styles-upload.php',
			array(
				__CLASS__,
				'add_media_css'
			)
		);
		add_filter(
			'plugin_row_meta',
			array(
				__CLASS__,
				'add_meta_link'
			),
			10,
			2
		);
		add_filter(
			'wp_generate_attachment_metadata',
			array(
				__CLASS__,
				'optimize_upload_images'
			)
		);
		add_filter(
			'manage_media_columns',
			array(
				__CLASS__,
				'manage_posts_columns'
			)
		);
		add_action(
			'manage_media_custom_column',
			array(
				__CLASS__,
				'manage_posts_column'
			),
			10,
			2
		);
	}
	
	
	/**
	* Initialisierung des Admin-Bereiches
	*
	* @since   0.0.2
	* @change  0.0.2
	*/
	
	public static function admin_init()
	{
		wp_register_style(
			'optimus-media',
			plugins_url(
				'css/styles.min.css',
				OPTIMUS_FILE
			)
		);
	}
	
	
	/**
	* Hinzufügen der Stylesheets
	*
	* @since   0.0.2
	* @change  0.0.2
	*/
	
	public static function add_media_css()
	{
		wp_enqueue_style('optimus-media');
	}


	/**
	* Hinzufügen der Meta-Links
	*
	* @since   0.0.1
	* @change  0.0.3
	*
	* @param   array   $input  Array mit Links
	* @param   string  $file   Name des Plugins
	* @return  array           Array mit erweitertem Link
	*/

	public static function add_meta_link($input, $file)
	{
		/* Restliche Plugins? */
		if ( $file !== OPTIMUS_BASE ) {
			return $input;
		}
		
		return array_merge(
			$input,
			array(
				'<a href="http://wpcoder.de" target="_blank">Plugins des Autors</a>',
				'<a href="http://flattr.com/profile/sergej.mueller" target="_blank">Unterstützen via Flattr</a>',
				'<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5RDDW9FEHGLG6" target="_blank">Unterstützen via PayPal</a>'
			)
		);
	}
	
	
	/**
	* Build-Optimierung für Upload-Image samt Thumbs
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   array  $upload_data  Array mit Upload-Informationen
	* @return  array  $upload_data  Array mit erneuerten Upload-Informationen
	*/
	
	public static function optimize_upload_images($upload_data) {
		/* Upload-Ordner */
		$upload_dir = wp_upload_dir();
		
		/* WP-Bugfux */
		if ( empty($upload_dir['subdir']) ) {
			$upload_path = trailingslashit($upload_dir['path']);
			$upload_file_url = trailingslashit($upload_dir['url']);
			$upload_file = $upload_data['file'];
		} else {
			$file_info = pathinfo($upload_data['file']);
			
			$upload_path = trailingslashit( trailingslashit($upload_dir['basedir']) . $file_info['dirname'] );
			$upload_url = trailingslashit( trailingslashit($upload_dir['baseurl']) . $file_info['dirname'] );
			$upload_file = $file_info['basename'];
		}
		
		/* Kein richtiges Format */
		if ( ! file_is_displayable_image( $upload_path . $upload_file ) ) {
			return $upload_data;
		}
		
		/* Array mit Dateien */
		$todo_files = array($upload_file);
		
		/* Thumbs hinzufügen */
		if ( !empty($upload_data['sizes']) ) {
			/* Loopen */
			foreach( $upload_data['sizes'] as $size ) {
				array_push(
					$todo_files,
					$size['file']
				);
			}
			
			/* Umkehren */
			$todo_files = array_reverse(
				array_unique($todo_files)
			);
		}
		
		$result_diff = array();
		
		/* Files loopen */
		foreach ($todo_files as $file) {
			/* Dateigröße */
			$upload_filesize = (int)filesize($upload_path . $file);

			/* Zu klein/groß? */
			if ( $upload_filesize <= 0 or $upload_filesize > 1024 * 100 ) {
				continue;
			}
			
			/* Request senden */
			$result = self::_optimize_upload_image( $upload_url . $file	);
			
			/* Inhalt */
			$result_body = wp_remote_retrieve_body($result);
			
			/* Kein 200 als Antwort? */
			if ( wp_remote_retrieve_response_code($result) !== 200 ) {
				$upload_data['optimus'] = esc_html($result_body);
				
				return $upload_data;
			}
			
			/* Fehler? */
			if ( is_wp_error($result) ) {
				$upload_data['optimus'] = $result->get_error_message();
				
				return $upload_data;
			}
			
			/* Inhalt schreiben */
			if ( ! file_put_contents($upload_path . $file, $result_body) ) {
				wp_die('Bild kann nicht ersetzt werden!');
			}
		  	
		  	/* Optimierte Größe */
		  	$result_filesize = wp_remote_retrieve_header(
		  		$result,
		  		'content-length'
		  	);
		  	
		  	/* Differenz */
		  	array_push(
		  		$result_diff,
		  		self::_calculate_diff_filesize(
		  			$upload_filesize,
		  			$result_filesize
		  		)
		  	);
		}
		
		/* Mittelwert speichern */
		if ( $count = count($result_diff) ) {
			$upload_data['optimus'] = round(
				array_sum($result_diff) / $count
			);
		}
		
		return $upload_data;
	}
	
	
	/**
	* Start der Optimierungsanfrage für eine Datei
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   string  $file  URL der zu optimierender Datei
	* @return  array          Array inkl. Rückgabe-Header
	*/
	
	private static function _optimize_upload_image($file)
	{
		/* Argumente */
		$params = array(
			'img' => urlencode(
				esc_url_raw(
					$file,
					array(
						'http',
						'https'
					)
				)
			)
		);
		
		/* URL erfragen */
		return wp_remote_post(
			'http://37.200.98.126',
			array(
				'timeout' => 60,
				'body'	  => $params
			)
		);
	}
	
	
	/**
	* Ausgabe der Optimus-Spalte mit der Überschrift
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   array  $columns  Verfügbare Spalten
	* @return  array            Editierte Spalten
	*/
	
	public static function manage_posts_columns($columns)
	{
		return array_merge(
			$columns,
			array(
				'optimus' => 'Optimus'
			)
		);
	}
	
	
	/**
	* Ausgabe der Optimus-Spalte mit Werten
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   string   $column  Bezeichnung der Spalte
	* @param   integer  $id      ID des aktuellen Objektes
	*/
	
	public static function manage_posts_column($column, $id)
	{
		/* Falsche Spalte? */
		if ( $column !== 'optimus' ) {
			return;
		}
		
		echo self::_get_diff_filesize($id);
	}
		
	
	/**
	* Ermittelt die Differenz der Dateigröße
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   intval  $before  Größe vor der Optimierung in Bytes
	* @param   intval  $after   Größe nach der Optimierung in Bytes
	* @return  intval           Ermittelte Differenz
	*/
	
	private static function _calculate_diff_filesize($before, $after)
	{
		/* Konvertieren */
		$before = (int)$before;
		$after = (int)$after;
		
		return sprintf(
			'%d',
			ceil( ($before - $after) * 100 / $before )
		);
	}
	
	
	/**
	* Gibt die formatierte Differenz der Dateigröße zurück
	*
	* @since   0.0.1
	* @change  0.0.2
	*
	* @param   intval  $id  Attachment-ID
	* @return  mixed        Ermittelter Wert
	*/
	
	private static function _get_diff_filesize($id)
	{
		/* Metadaten des Anhangs */
		$data = (array)wp_get_attachment_metadata($id);
		
		/* Ausgabe */
		if ( array_key_exists('optimus', $data) ) {
			/* Init */
			$optimus = esc_html($data['optimus']);
			
			/* Ergebnis als Zahl */
			if ( is_numeric($optimus) ) {
				return sprintf(
					'<p class="done"><span>%d%%</span></p>',
					$optimus
				);
			}
			
			/* Ergebnis als Fehler */
			return sprintf(
				'<p class="error"><span>+</span><em>%s</em></p>',
				$optimus
			);
		}
		
		return NULL;
	}
}
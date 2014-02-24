<?php


/* Quit */
defined('ABSPATH') OR exit;


/**
* Optimus_Request
*
* @since 1.1.7
*/

class Optimus_Request
{


	/**
	* Build-Optimierung für Upload-Image samt Thumbs
	*
	* @since   0.0.1
	* @change  1.1.9
	*
	* @param   array    $upload_data    Array mit Upload-Informationen
	* @param   integer  $attachment_id  Attachment ID
	* @return  array    $upload_data    Array mit erneuerten Upload-Informationen
	*/

	public static function optimize_upload_images($upload_data, $attachment_id) {
		/* Already optimized? */
		if ( ! empty($upload_data['optimus']) ) {
			return $upload_data;
		}

		/* Upload-Ordner */
		$upload_dir = wp_upload_dir();

		/* WP-Bugfix */
		if ( empty($upload_dir['subdir']) ) {
			$upload_path = $upload_dir['path'];
			$upload_url = $upload_dir['url'];
			$upload_file = $upload_data['file'];
		} else {
			$file_info = pathinfo($upload_data['file']);

			$upload_path = path_join($upload_dir['basedir'], $file_info['dirname']);
			$upload_url = path_join($upload_dir['baseurl'], $file_info['dirname']);
			$upload_file = $file_info['basename'];
		}

		/* Simple regex check */
		if ( ! preg_match('/^[^\?\%]+\.(?:jpe?g|png)$/i', $upload_file) ) {
			return $upload_data;
		}

		/* Attachment */
		$attachment = get_post($attachment_id);

		/* Mime Type */
		$mime_type = get_post_mime_type($attachment);

		/* Prüfung auf Mime-Type */
		if ( ! self::_allowed_mime_type($mime_type) ) {
			return $upload_data;
		}

		/* Optionen */
		$options = Optimus::get_options();

		/* Array mit Dateien */
		$todo_files = array($upload_file);

		/* Thumbs hinzufügen */
		if ( !empty($upload_data['sizes']) ) {
			/* Loopen */
			foreach( $upload_data['sizes'] as $thumb ) {
				if ( self::_allowed_mime_type($thumb['mime-type']) ) {
					array_push(
						$todo_files,
						$thumb['file']
					);
				}
			}

			/* Umkehren */
			$todo_files = array_reverse(
				array_unique($todo_files)
			);
		}

		/* Differenz */
		$diff_filesizes = array();

		/* Files loopen */
		foreach ($todo_files as $file) {
			/* No file? */
			if ( empty($file) ) {
				continue;
			}

			/* Pfad + Datei */
			$upload_url_file = path_join($upload_url, $file);
			$upload_path_file = path_join($upload_path, $file);

			/* Dateigröße */
			$upload_filesize = (int)filesize($upload_path_file);

			/* Zu groß? */
			if ( ! self::_allowed_file_size($mime_type, $upload_filesize) ) {
				continue;
			}

			/* Encoded url */
			$upload_url_file_encoded = urlencode(
				esc_url_raw(
					$upload_url_file,
					array('http', 'https')
				)
			);

			/*  Request: Optimize image */
			$action_response = self::_do_image_action(
				$upload_path_file,
				array(
					'file' => $upload_url_file_encoded,
					'copy' => $options['copy_markers']
				)
			);

			/* Check */
			switch (true) {
				case ( is_array($action_response) ):
					return array_merge(
						$upload_data,
						array(
							'optimus' => $action_response
						)
					);

				case ( $action_response === false ):
					return $upload_data;

				default:
					$response_filesize = $action_response;
			}

			/* Request: WebP convert */
			if ( $options['webp_convert'] && Optimus_HQ::is_unlocked() ) {
				$action_response = self::_do_image_action(
					$upload_path_file,
					array(
						'file' => $upload_url_file_encoded,
						'webp' => true
					)
				);
			}

		  	/* Differenz */
		  	array_push(
		  		$diff_filesizes,
		  		self::_calculate_diff_filesize(
		  			$upload_filesize,
		  			$response_filesize
		  		)
		  	);
		}

		/* Arrays zählen */
		$ordered = count($todo_files);
		$received = count($diff_filesizes);

		/* Mittelwert speichern */
		if ( $received ) {
			$upload_data['optimus'] = array(
				'profit'   => max($diff_filesizes),
				'quantity' => round( $received * 100 / $ordered )
			);
		}

		return $upload_data;
	}


	/**
	* Änderung der Datei-Erweiterung
	*
	* @since   1.1.4
	* @change  1.1.7
	*
	* @param   string  $file       Dateipfad
	* @param   string  $extension  Ziel-Erweiterung
	* @return  string              Umbenannter Dateipfad
	*/

	private static function _replace_file_extension($file, $extension)
	{
		return substr_replace(
			$file,
			$extension,
			strlen(pathinfo($file, PATHINFO_EXTENSION)) * -1
		);
	}


	/**
	* Behandlung der Bilder-Aktionen
	*
	* @since   1.1.4
	* @change  1.2.1
	*
	* @param   string  $file  Dateipfad
	* @param   array   $args  POST-Argumente
	* @return  mixed          Fehlercodes/Dateigröße/FALSE im Fehlerfall
	*/

	private static function _do_image_action($file, $args)
	{
		/* Request senden */
		$response = self::_do_api_request($file, $args);

		/* Code */
		$response_code = (int)wp_remote_retrieve_response_code($response);

		/* No content header */
		if ( $response_code === 204 ) {
			return false;
		}

		/* Kein 200 als Antwort? */
		if ( $response_code !== 200 ) {
			return array(
				'error' => $response_code
			);
		}

		/* Fehler? */
		if ( is_wp_error($response) ) {
			return false;
		}

		/* File size */
		$response_filesize = wp_remote_retrieve_header($response, 'content-length');

		/* File body */
		$response_body = (string)wp_remote_retrieve_body($response);

		/* Leere Datei? */
		if ( empty($response_filesize) OR empty($response_body) ) {
			return false;
		}

		/* File ext replace for WebP */
		if ( ! empty($args['webp']) ) {
			$file = self::_replace_file_extension(
				$file,
				'webp'
			);
		}

		/* Inhalt schreiben */
		if ( ! file_put_contents($file, $response_body) ) {
			return false;
		}

		return $response_filesize;
	}


	/**
	* Start der API-Anfrage
	*
	* @since   1.1.4
	* @change  1.1.9
	*
	* @param   array  $args  POST-Argumente
	* @return  array         Array inkl. Rückgabe-Header
	*/

	private static function _do_api_request($file, $args)
	{
		/* cURL request */
		if ( WP_Http_Curl::test() ) {
			add_action(
				'http_api_curl',
				array(
					__CLASS__,
					'set_curl_options'
				)
			);

			return wp_safe_remote_post(
				sprintf(
					'%s/%s?%s',
					'http://magic.optimus.io',
					Optimus_HQ::get_key(),
					self::_curl_optimus_task($args)
				),
				array(
					'timeout' => 30,
					'body'	  => file_get_contents($file)
				)
			);
		}

		/* Fallback request */
		return wp_safe_remote_post(
			sprintf(
				'%s/%s',
				'http://api.optimus.io',
				Optimus_HQ::get_key()
			),
			array(
				'timeout' => 30,
				'body'	  => $args
			)
		);
	}


	/**
	* Set cURL request options
	*
	* @since   1.1.9
	* @change  1.1.9
	*
	* @param   object  $handle  cURL handle with default options
	* @return  object  $handle  cURL handle with added options
	*/

	public static function set_curl_options(&$handle)
	{
		curl_setopt(
			$handle,
			CURLOPT_BINARYTRANSFER,
			true
		);
	}


	/**
	* Get optimus task depending on $args array
	*
	* @since   1.1.9
	* @change  1.1.9
	*
	* @param   array   $args  Array mit arguments
	* @return  string         Current optimus task
	*/

	private static function _curl_optimus_task($args)
	{
		if ( ! empty($args['copy']) ) {
			return 'clean';
		}
		if ( ! empty($args['webp']) ) {
			return 'webp';
		}

		return 'optimize';
	}


	/**
	* Prüfung des erlaubten Bildtyps pro Datei
	*
	* @since   1.1.0
	* @change  1.1.7
	*
	* @param   string   $mime_type  Mime Type
	* @return  boolean              TRUE bei bestehender Prüfung
	*/

	private static function _allowed_mime_type($mime_type)
	{
		/* Leer? */
		if ( empty($mime_type) ) {
			return false;
		}

		/* Quota-Prüfung */
		return array_key_exists(
			$mime_type,
			self::_get_request_quota()
		);
	}


	/**
	* Prüfung der erlaubten Bildgröße pro Dateityp
	*
	* @since   1.1.0
	* @change  1.1.7
	*
	* @param   string   $mime_type  Mime Type
	* @param   integer  $file_size  Bild-Größe
	* @return  boolean              TRUE bei bestehender Prüfung
	*/

	private static function _allowed_file_size($mime_type, $file_size)
	{
		/* Leer? */
		if ( empty($file_size) ) {
			return false;
		}

		/* Quota */
		$request_quota = self::_get_request_quota();

		/* Zu groß? */
		if ( $file_size > $request_quota[$mime_type] ) {
			return false;
		}

		return true;
	}


	/**
	* Rückgabe der Kontingente pro Optimus Modell
	*
	* @since   1.1.0
	* @change  1.1.7
	*
	* @return  array  Array mit Datensätzen
	*/

	private static function _get_request_quota()
	{
		/* Kontingente */
		$quota = array(
			/* FREE */
			false => array(
				'image/jpeg' => 20 * 1024
			),

			/* HQ */
			true => array(
				'image/jpeg' => 1000 * 1024,
				'image/png'  => 200 * 1024
			)
		);

		return $quota[ Optimus_HQ::is_unlocked() ];
	}


	/**
	* Löscht erzeugte WebP-Dateien
	*
	* @since   1.1.4
	* @change  1.1.7
	*
	* @param   string  $file  Zu löschende Original-Datei
	* @return  string  $file  Zu löschende Original-Datei
	*/

	public static function delete_converted_file($file) {
		/* Plugin options */
		$options = Optimus::get_options();

		/* Nicht aktiv? */
		if ( ! $options['webp_convert'] OR Optimus_HQ::is_locked() ) {
			return $file;
		}

		/* Upload path */
		$upload_path = wp_upload_dir();
		$base_dir = $upload_path['basedir'];

		/* Init converted file */
		$converted_file = $file;

		/* Check for upload path */
		if ( strpos($converted_file, $base_dir) === false ) {
			$converted_file = path_join($base_dir, $converted_file);
		}

		/* Replace to webp extension */
		$converted_file = self::_replace_file_extension(
			$converted_file,
			'webp'
		);

		/* Remove if exists */
		if ( file_exists($converted_file) ) {
			@unlink($converted_file);
		}

		return $file;
	}


	/**
	* Ermittelt die Differenz der Dateigröße
	*
	* @since   0.0.1
	* @change  1.1.7
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
}
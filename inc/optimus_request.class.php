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
	* Default remote scheme
	*
	* @var  string
	*/

	private static $_remote_scheme = 'http';


	/**
	* Build optimization for a upload image including previews
	*
	* @since   0.0.1
	* @change  1.3.6
	*
	* @param   array    $upload_data    Incoming upload information
	* @param   integer  $attachment_id  Attachment ID
	* @return  array    $upload_data    Renewed upload information
	*/

	public static function optimize_upload_images($upload_data, $attachment_id) {
		/* Already optimized? */
		if ( ! empty($upload_data['optimus']) ) {
			return $upload_data;
		}

		/* Only images, please */
		if ( empty($upload_data['file']) ) {
			return $upload_data;
		}

		/* Skip regenerating */
		if ( ! empty($_POST['action']) && $_POST['action'] === 'regeneratethumbnail' ) {
			return $upload_data;
		}

		/* cURL only */
		if ( ! WP_Http_Curl::test() ) {
			return $upload_data;
		}

		/* WP upload folder */
		$upload_dir = wp_upload_dir();

		/* Upload dir workaround */
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

		/* Get the attachment */
		$attachment = get_post($attachment_id);

		/* Attachment mime type */
		$mime_type = get_post_mime_type($attachment);

		/* Mime type check */
		if ( ! self::_allowed_mime_type($mime_type) ) {
			return $upload_data;
		}

		/* Get plugin options */
		$options = Optimus::get_options();

		/* Init arrays */
		$todo_files = array();
		$diff_filesizes = array();

		/* Keep the master */
		if ( ! $options['keep_original'] ) {
			array_push(
				$todo_files,
				$upload_file
			);
		}

		/* Set cURL options */
		add_action(
			'http_api_curl',
			array(
				__CLASS__,
				'set_curl_options'
			)
		);

		/* Set https scheme */
		if ( $options['secure_transport'] && Optimus_HQ::is_unlocked() ) {
			self::$_remote_scheme = 'https';
		}

		/* Search for thumbs */
		if ( ! empty($upload_data['sizes']) ) {
			foreach( $upload_data['sizes'] as $thumb ) {
				if ( $thumb['file'] && self::_allowed_mime_type($thumb['mime-type']) ) {
					array_push(
						$todo_files,
						$thumb['file']
					);
				}
			}

			/* Reverse files array */
			$todo_files = array_reverse(
				array_unique($todo_files)
			);
		}

		/* No images to process */
		if ( empty($todo_files) ) {
			return $upload_data;
		}

		/* Loop todo files */
		foreach ($todo_files as $file) {
			/* Merge path & file */
			$upload_url_file = path_join($upload_url, $file);
			$upload_path_file = path_join($upload_path, $file);

			/* Get file size */
			$upload_filesize = (int)filesize($upload_path_file);

			/* Too big? */
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

			/* Evaluate response */
			if ( is_numeric($action_response) ) {
				$response_filesize = $action_response;
			} else {
				return $upload_data;
			}

			/* Request: WebP convert */
			if ( $options['webp_convert'] && Optimus_HQ::is_unlocked() ) {
				self::_do_image_action(
					$upload_path_file,
					array(
						'file' => $upload_url_file_encoded,
						'webp' => true
					)
				);
			}

		  	/* File size difference */
		  	array_push(
		  		$diff_filesizes,
		  		self::_calculate_diff_filesize(
		  			$upload_filesize,
		  			$response_filesize
		  		)
		  	);
		}

		/* Count files */
		$ordered = count($todo_files);
		$received = count($diff_filesizes);

		/* Average values */
		if ( $received ) {
			$upload_data['optimus'] = array(
				'profit'   => max($diff_filesizes),
				'quantity' => round( $received * 100 / $ordered )
			);
		}

		return $upload_data;
	}


	/**
	* Handle image actions
	*
	* @since   1.1.4
	* @change  1.3.0
	*
	* @param   string  $file  Image file
	* @param   array   $args  Request arguments
	* @return  array          Request failed with an error code
	* @return  false          An error has occurred
	* @return  null           Empty response with 204 status code
	* @return  intval         Response content length
	*/

	private static function _do_image_action($file, $args)
	{
		/* Start request */
		$response = self::_do_api_request($file, $args);

		/* Response status code */
		$response_code = (int)wp_remote_retrieve_response_code($response);

		/* Not success status code? */
		if ( $response_code !== 200 ) {
			return false;
		}

		/* Response error? */
		if ( is_wp_error($response) ) {
			return false;
		}

		/* Response properties */
		$response_body = wp_remote_retrieve_body($response);
		$response_type = wp_remote_retrieve_header($response, 'content-type');
		$response_length = (int)wp_remote_retrieve_header($response, 'content-length');

		/* Empty file? */
		if ( empty($response_body) OR empty($response_type) OR empty($response_length) ) {
			return false;
		}

		/* Mime type check */
		if ( ! self::_allowed_mime_type($response_type) ) {
			return false;
		}

		/* Extension replace for WebP */
		if ( isset($args['webp']) ) {
			$file = self::_replace_file_extension(
				$file,
				'webp'
			);
		}

		/* Rewrite image file */
		if ( ! file_put_contents($file, $response_body) ) {
			return false;
		}

		return $response_length;
	}


	/**
	* Optimus API request
	*
	* @since   1.1.4
	* @change  1.3.5
	*
	* @param   string  $file  Image file
	* @param   array   $args  Request arguments
	* @return  array          Response data
	*/

	private static function _do_api_request($file, $args)
	{
		return wp_safe_remote_post(
			sprintf(
				'%s://%s.%s/%s?%s',
				self::$_remote_scheme,
                'magic',
                'optimus.io',
				Optimus_HQ::get_key(),
				self::_curl_optimus_task($args)
			),
			array(
				'body'	  => file_get_contents($file),
				'timeout' => 30
			)
		);
	}


	/**
	* Set cURL request options
	*
	* @since   1.1.9
	* @change  1.3.0
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
		curl_setopt(
			$handle,
			CURLOPT_HTTPHEADER,
			array(
				'Accept: image/*'
			)
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
	* Adjustment of the file extension
	*
	* @since   1.1.4
	* @change  1.3.0
	*
	* @param   string  $file       File path
	* @param   string  $extension  Target extension
	* @return  string              Renewed file path
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
	* Return Optimus quota for a plugin type
	*
	* @since   1.1.0
	* @change  1.3.5
	*
	* @return  array  Optimus quota
	*/

	private static function _get_request_quota()
	{
		/* Quota */
		$quota = array(
			/* Optimus */
			false => array(
				'image/jpeg' => 20 * 1024
			),

			/* Optimus HQ */
			true => array(
				'image/jpeg' => 1000 * 1024,
				'image/webp' => 1000 * 1024,
				'image/png'  => 500  * 1024
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
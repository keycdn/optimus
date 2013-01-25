<?php


/**
* Optimus_Media
*
* @since 1.1.2
*/

class Optimus_Media
{


	/**
	* Hinzufügen der Stylesheets
	*
	* @since   0.0.2
	* @change  1.1.2
	*/

	public static function add_css()
	{
		wp_register_style(
			'optimus-media',
			plugins_url(
				'css/styles.min.css',
				OPTIMUS_FILE
			)
		);

		wp_enqueue_style('optimus-media');
	}


	/**
	* Ausgabe der Optimus-Spalte mit der Überschrift
	*
	* @since   0.0.1
	* @change  1.1.2
	*
	* @param   array  $columns  Verfügbare Spalten
	* @return  array            Editierte Spalten
	*/

	public static function manage_columns($columns)
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
	* @change  1.1.2
	*
	* @param   string   $column  Bezeichnung der Spalte
	* @param   integer  $id      ID des aktuellen Objektes
	*/

	public static function manage_column($column, $id)
	{
		/* Falsche Spalte? */
		if ( $column !== 'optimus' ) {
			return;
		}

		echo self::_column_html($id);
	}


	/**
	* Gibt die formatierte Spalte in HTML zurück
	*
	* @since   0.0.1
	* @change  1.1.2
	*
	* @param   intval  $id  Attachment-ID
	* @return  mixed        Ermittelter Wert
	*/

	private static function _column_html($id)
	{
		/* Metadaten des Anhangs */
		$data = (array)wp_get_attachment_metadata($id);

		/* Ausgabe */
		if ( array_key_exists('optimus', $data) ) {
			/* Init */
			$optimus = $data['optimus'];

			/* Neue Methode */
			if ( is_array($optimus) ) {
				/* Ausgabe der Erfolgmeldung */
				if ( isset($optimus['profit']) ) {
					return sprintf(
						'<div class="%s"><p>%d%%</p></div>',
						self::_pie_class( $optimus['quantity'] ),
						$optimus['profit']
					);
				}

				/* Ausgabe des Fehlercodes */
				if ( isset($optimus['error']) ) {
					return sprintf(
						'<div class="fail"><p>%d</p></div>',
						$optimus['error']
					);
				}
			}

			/* Ergebnis als Zahl */
			if ( is_numeric($optimus) ) {
				return sprintf(
					'<div><p>%d%%</p></div>',
					$optimus
				);
			}

			/* Ergebnis als String */
			return sprintf(
				'<div class="fail"><p>X</p></div>',
				$optimus
			);
		}

		return NULL;
	}


	/**
	* Gibt die CSS-Klasse je nach Menge komprimierter Dateien
	*
	* @since   0.0.8
	* @change  1.1.2
	*
	* @param   intval  $quantity  Menge als Prozentwert
	* @return  string             CSS-Klasse
	*/

	private static function _pie_class($quantity)
	{
		/* Init */
		$quantity = (int)$quantity;

		/* Leer? */
		if ( empty($quantity) ) {
			return '';
		}

		/* Loop */
		switch(true) {
			case ($quantity == 100):
				return 'four';
			case ($quantity <= 25):
				return 'one';
			case ($quantity <= 50):
				return 'two';
			default:
				return 'three';
		}
	}
}
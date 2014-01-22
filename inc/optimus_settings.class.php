<?php


/* Quit */
defined('ABSPATH') OR exit;


/**
* Optimus_Settings
*
* @since 1.1.2
*/

class Optimus_Settings
{


	/**
	* Registrierung der Settings
	*
	* @since   1.0.0
	* @change  1.1.2
	*/

	public static function register_settings()
	{
		register_setting(
			'optimus',
			'optimus',
			array(
				__CLASS__,
				'validate_options'
			)
		);
	}


	/**
	* Valisierung der Optionsseite
	*
	* @since   1.0.0
	* @change  1.1.4
	*
	* @param   array  $data  Array mit Formularwerten
	* @return  array         Array mit geprüften Werten
	*/

	public static function validate_options($data)
	{
		return array(
			'copy_markers' => (int)(!empty($data['copy_markers'])),
			'webp_convert' => (int)(!empty($data['webp_convert']))
		);
	}


	/**
	* Einfügen der Optionsseite
	*
	* @since   1.0.0
	* @change  1.1.6
	*/

	public static function add_page()
	{
		$page = add_options_page(
			'Optimus',
			'Optimus',
			'manage_options',
			'optimus',
			array(
				__CLASS__,
				'options_page'
			)
		);
	}


	/**
	* Darstellung der Optionsseite
	*
	* @since   1.0.0
	* @change  1.1.6
	*/

	public static function options_page()
	{ ?>
		<div class="wrap">
			<h2>
				Optimus
			</h2>

			<form method="post" action="options.php">
				<?php settings_fields('optimus') ?>

				<?php $options = Optimus::get_options() ?>

				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							Bild-Metadaten
						</th>
						<td>
							<fieldset>
								<label for="optimus_copy_markers">
									<input type="checkbox" name="optimus[copy_markers]" id="optimus_copy_markers" value="1" <?php checked(1, $options['copy_markers']) ?> />
									Keine Löschung der Bild-Metadaten
								</label>

								<p class="description">
									Aktive Option behält EXIF- und IPTC-Daten bzw. Copyright- und Fotoaufnahme-Parameter in Bildern. Die Größenreduzierung fällt geringer aus.
								</p>
							</fieldset>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							WebP-Dateien
						</th>
						<td>
							<fieldset>
								<label for="optimus_webp_convert">
									<input type="checkbox" name="optimus[webp_convert]" id="optimus_webp_convert" value="1" <?php checked(1, $options['webp_convert']) ?> onclick='if ( this.checked ) return confirm("Nur für erfahrene Nutzer, da Anpassung der Server-Konfigurationsdatei und Qualitätskontrolle notwendig.\n\nOption aktivieren?")' />
									Anfertigung der WebP-Dateien
								</label>

								<p class="description">
									Nur Optimus HQ. Verlangsamt die Generierung der Vorschaubilder. Modifizierung der Server-Konfigurationsdatei und Überprüfung der Ausgabe erforderlich. [<a href="http://cup.wpcoder.de/webp-jpeg-alternative/" target="_blank">Details</a>]
								</p>
							</fieldset>
						</td>
					</tr>
				</table>

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
			</form>
		</div><?php
	}
}
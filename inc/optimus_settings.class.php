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
	* @change  1.3.1
	*/

	public static function register_settings()
	{
		register_setting(
			'optimus',
			'optimus',
			array(
				__CLASS__,
				'validate_settings'
			)
		);
	}


	/**
	* Valisierung der Optionsseite
	*
	* @since   1.0.0
	* @change  1.3.1
	*
	* @param   array  $data  Array mit Formularwerten
	* @return  array         Array mit geprüften Werten
	*/

	public static function validate_settings($data)
	{
		return array(
			'copy_markers'		=> (int)(!empty($data['copy_markers'])),
			'webp_convert'		=> (int)(!empty($data['webp_convert'])),
			'secure_transport'	=> (int)(!empty($data['secure_transport']))
		);
	}


	/**
	* Einfügen der Optionsseite
	*
	* @since   1.0.0
	* @change  1.3.1
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
				'settings_page'
			)
		);
	}


	/**
	* Darstellung der Optionsseite
	*
	* @since   1.3.2  Implementierung von get_intermediate_image_sizes
	* @since   1.0.0
	*
	* @return  void
	*/

	public static function settings_page()
	{ ?>
		<div class="wrap">
			<h2>
				Optimus
			</h2>

			<form method="post" action="options.php">
				<?php settings_fields('optimus') ?>

				<?php $options = Optimus::get_options() ?>

				<table class="form-table">
					<?php if ( $sizes = get_intermediate_image_sizes() ) { ?>
						<tr valign="top">
							<th scope="row">
								Bildgrößen
							</th>
							<td>
								<p>
									<?php echo implode( ', ', array_values($sizes) ) ?>
								</p>

								<p class="description">
									Zusätzlich zum Originalbild und abhängig von der Dateigröße verkleinert Optimus die in WordPress registrierten Bildgrößen. [<a href="https://plus.google.com/b/114450218898660299759/114450218898660299759/posts/jZVfeac5eHW" target="_blank">Details</a>]
								</p>
							</td>
						</tr>
					<?php } ?>

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
									Nur <a href="https://optimus.io" target="_blank">Optimus HQ</a>. Aktive Option behält EXIF-, Copyright- und Fotoaufnahme-Informationen in Bildern. Die Größenreduzierung fällt geringer aus. [<a href="https://plus.google.com/b/114450218898660299759/114450218898660299759/posts/Wwz7uFHBzFF" target="_blank">Details</a>]
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
									Nur <a href="https://optimus.io" target="_blank">Optimus HQ</a>. Modifizierung der Server-Konfigurationsdatei und Überprüfung der Ausgabe erforderlich. Verlangsamt den Optimierungsprozess. [<a href="https://plus.google.com/114450218898660299759/posts/3emb7o4368X" target="_blank">Details</a>]
								</p>
							</fieldset>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							HTTPS-Verbindung
						</th>
						<td>
							<fieldset>
								<label for="optimus_secure_transport">
									<input type="checkbox" name="optimus[secure_transport]" id="optimus_secure_transport" value="1" <?php checked(1, $options['secure_transport']) ?> />
									Bilder TLS-verschlüsselt übertragen
								</label>

								<p class="description">
									Nur <a href="https://optimus.io" target="_blank">Optimus HQ</a>. Die Kommunikation zum Optimus-Server erfolgt über eine HTTPS-Verbindung. Verlangsamt den Optimierungsprozess. [<a href="https://plus.google.com/b/114450218898660299759/114450218898660299759/posts/5f2f9XKXb4F" target="_blank">Details</a>]
								</p>
							</fieldset>
						</td>
					</tr>
				</table>

				<?php submit_button() ?>
			</form>
		</div><?php
	}
}
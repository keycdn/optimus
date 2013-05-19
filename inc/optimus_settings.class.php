<?php


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
	* @change  1.1.2
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

		add_action(
			'admin_print_styles-' .$page,
			array(
				__CLASS__,
				'add_css'
			)
		);
	}


	/**
	* Einbindung von CSS
	*
	* @since   1.0.0
	* @change  1.1.2
	*/

	public static function add_css()
	{
		/* Infos auslesen */
		$data = get_plugin_data(OPTIMUS_FILE);

		/* CSS registrieren */
		wp_register_style(
			'optimus_css',
			plugins_url('css/styles.min.css', OPTIMUS_FILE),
			array(),
			$data['Version']
		);

		/* CSS einbinden */
		wp_enqueue_style('optimus_css');
	}


	/**
	* Darstellung der Optionsseite
	*
	* @since   1.0.0
	* @change  1.1.4
	*/

	public static function options_page()
	{ ?>
		<div class="wrap" id="optimus_main">
			<?php screen_icon('optimus') ?>

			<h2>
				Optimus
			</h2>

			<form method="post" action="options.php">
				<?php settings_fields('optimus') ?>

				<?php $options = Optimus::get_options() ?>

				<div class="table rounded">
					<table class="form-table">
						<tr>
							<th>
								Bild-Metadaten <strong>nicht</strong> entfernen
								<small>
									Aktive Option behält EXIF- und IPTC-Daten in Fotos.<br />Empfohlen, wenn Copyright- und Aufnahme-Parameter erhalten bleiben sollen. <strong>Die Größenreduzierung fällt geringer aus.</strong>
								</small>
							</th>
							<td>
								<input type="checkbox" name="optimus[copy_markers]" value="1" <?php checked(1, $options['copy_markers']) ?> />
							</td>
						</tr>
					</table>
				</div>

				<div class="table rounded">
					<table class="form-table">
						<tr>
							<th>
								WebP-Dateien anfertigen <span>Optimus HQ</span>
								<small>
									Aktive Option legt für jedes Bild eine WebP-Variante an.<br />Erweiterung der Datei .htaccess um <a href="https://gist.github.com/sergejmueller/5462544" target="_blank">Code-Snippet</a> erforderlich.<br /><strong>Verlangsamt die Generierung der Vorschaubilder.</strong>
								</small>
							</th>
							<td>
								<input type="checkbox" name="optimus[webp_convert]" value="1" <?php checked(1, $options['webp_convert']) ?> />
							</td>
						</tr>
					</table>
				</div>

				<div class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</div>
			</form>
		</div><?php
	}
}
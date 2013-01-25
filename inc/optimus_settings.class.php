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
	* @change  1.1.2
	*
	* @param   array  $data  Array mit Formularwerten
	* @return  array         Array mit geprüften Werten
	*/

	public static function validate_options($data)
	{
		return array(
			'copy_markers' => (int)(!empty($data['copy_markers']))
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
	* @change  1.1.2
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
								Bild-Metadaten (EXIF, IPTC) <strong>nicht</strong> entfernen
								<small>
									Aktive Option behält EXIF- und IPTC-Informationen.<br />Andernfalls werden alle Bild-Metadaten entfernt.
								</small>
							</th>
							<td>
								<input type="checkbox" name="optimus[copy_markers]" value="1" <?php checked(1, $options['copy_markers']) ?> />
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
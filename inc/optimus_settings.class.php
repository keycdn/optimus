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
    * @change  1.5.0
    *
    * @param   array  $data  Array mit Formularwerten
    * @return  array         Array mit geprüften Werten
    */

    public static function validate_settings($data)
    {
        return array(
            'copy_markers'      => (int)(!empty($data['copy_markers'])),
            'webp_convert'      => (int)(!empty($data['webp_convert'])),
            'webp_keeporigext'  => (int)(!empty($data['webp_keeporigext'])),
            'keep_original'     => (int)(!empty($data['keep_original'])),
            'secure_transport'  => (int)(!empty($data['secure_transport'])),
            'manual_optimize'   => (int)(!empty($data['manual_optimize']))
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
    * @since   1.0.0
    * @change  1.4.0
    *
    * @return  void
    */

    public static function settings_page()
    { ?>
        <div class="wrap">
            <h2>
                <?php _e("Optimus Settings", "optimus"); ?>
            </h2>

            <div class="updated"><p><?php _e("Need to optimize all your existing images? Use the <strong><a href=\"".admin_url('tools.php?page=optimus-bulk-optimizer')."\">Optimus Bulk Optimizer</a></strong>.", "optimus"); ?></p></div>

            <form method="post" action="options.php">
                <?php settings_fields('optimus') ?>

                <?php $options = Optimus::get_options() ?>

                <table class="form-table">
                    <?php if ( $sizes = get_intermediate_image_sizes() ) { ?>
                        <tr valign="top">
                            <th scope="row">
                                <?php _e("Image sizes", "optimus"); ?>
                            </th>
                            <td>
                                <p>
                                    <?php echo implode( ', ', array_values($sizes) ) ?>
                                </p>

                                <p class="description">
                                    <?php _e("In addition to the original image, Optimus compresses the registered image sizes in WordPress. [<a href=\"https://optimus.keycdn.com/support/optimus-settings/#compression-thumbnail-images\" target=\"_blank\">Details</a>]", "optimus"); ?>
                                </p>
                                <br>
                                <p class="description">
                                    <?php if ( Optimus_HQ::is_locked() ) { _e("The size limit of the free version of Optimus is <strong>100 KB</strong>. Do you want to compress larger images? Get a license for <a href=\"https://optimus.io\" target=\"_blank\">Optimus HQ</a>.", "optimus"); } ?>
                                </p>
                            </td>
                        </tr>
                    <?php } ?>

                    <tr valign="top">
                        <th scope="row">
                            <?php _e("Ignore original images", "optimus"); ?>
                        </th>
                        <td>
                            <fieldset>
                                <label for="optimus_keep_original">
                                    <input type="checkbox" name="optimus[keep_original]" id="optimus_keep_original" value="1" <?php checked(1, $options['keep_original']) ?> />
                                    <?php _e("No optimization of original images", "optimus"); ?>
                                </label>

                                <p class="description">
                                    <?php _e("Optimus only compresses preview images (Thumbnails). Original images uploaded to WordPress are not affected. [<a href=\"https://optimus.keycdn.com/support/optimus-settings/#optimization-original-images\" target=\"_blank\">Details</a>]", "optimus"); ?>
                                </p>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">
                            <?php _e("Keep image metadata", "optimus"); ?>
                        </th>
                        <td>
                            <fieldset>
                                <label for="optimus_copy_markers">
                                    <input type="checkbox" name="optimus[copy_markers]" id="optimus_copy_markers" value="1" <?php checked(1, $options['copy_markers']); echo Optimus_HQ::is_locked() ? "onclick=\"return false;\" disabled=\"disabled\"" : ""; ?> />
                                    <?php _e("No deletion of image metadata", "optimus"); ?>
                                </label>

                                <p class="description">
                                    <?php _e("Only <a href=\"https://optimus.io\" target=\"_blank\">Optimus HQ</a>. Active option keeps EXIF-, copyright and photo creation information in images. Size reduction is less significant. [<a href=\"https://optimus.keycdn.com/support/optimus-settings/#remove-metadata\" target=\"_blank\">Details</a>]", "optimus"); ?>
                                </p>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">
                            <?php _e("WebP files", "optimus"); ?>
                        </th>
                        <td>
                            <fieldset>
                                <label for="optimus_webp_convert">
                                    <input type="checkbox" name="optimus[webp_convert]" id="optimus_webp_convert" value="1" <?php checked(1, $options['webp_convert']); echo Optimus_HQ::is_locked() ? "onclick=\"return false;\" disabled=\"disabled\"" : ""; ?> />
                                    <?php _e("Creation of WebP files", "optimus"); ?>
                                </label>

                                <p class="description">
                                    <?php _e("Only <a href=\"https://optimus.io\" target=\"_blank\">Optimus HQ</a>. It is recommended to use the <a href=\"https://wordpress.org/plugins/cache-enabler/\">Cache Enabler plugin</a> to integrate the WebP images. [<a href=\"https://optimus.keycdn.com/support/optimus-settings/#convert-to-webp\" target=\"_blank\">Details</a>]", "optimus"); ?>
                                </p>
                            </fieldset>

                            <br>

                            <fieldset>
                                <label for="optimus_webp_keeporigext">
                                    <input type="checkbox" name="optimus[webp_keeporigext]" id="optimus_webp_keeporigext" value="1" <?php checked(1, $options['webp_keeporigext']); echo Optimus_HQ::is_locked() ? "onclick=\"return false;\" disabled=\"disabled\"" : ""; ?> />
                                    <?php _e("Append .webp extension", "optimus"); ?>
                                </label>

                                <p class="description">
                                    <?php _e("Append .webp extension instead of replacing the original one (e.g. <i>test.jpg.webp</i>)", "optimus"); ?>
                                </p>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">
                            <?php _e("HTTPS connection", "optimus"); ?>
                        </th>
                        <td>
                            <fieldset>
                                <label for="optimus_secure_transport">
                                    <input type="checkbox" name="optimus[secure_transport]" id="optimus_secure_transport" value="1" <?php checked(1, $options['secure_transport']); echo Optimus_HQ::is_locked() ? "onclick=\"return false;\" disabled=\"disabled\"" : ""; ?> />
                                    <?php _e("Transfer images using TLS encryption", "optimus"); ?>
                                </label>

                                <p class="description">
                                    <?php _e("Only <a href=\"https://optimus.io\" target=\"_blank\">Optimus HQ</a>. Communication with the Optimus server is conducted through an HTTPS connection. Slightly slows down the optimization process.", "optimus"); ?>
                                </p>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">
                            <?php _e("Manual optimization", "optimus"); ?>
                        </th>
                        <td>
                            <fieldset>
                                <label for="optimus_manual_optimize">
                                    <input type="checkbox" name="optimus[manual_optimize]" id="optimus_manual_optimize" value="1" <?php checked(1, $options['manual_optimize']) ?> />
                                    <?php _e("No optimization of images during the upload process", "optimus"); ?>
                                </label>

                                <p class="description">
                                    <?php _e("This setting prevents the automatic optimization during the upload process. Images need to be optimized via the Media Library later on. [<a href=\"https://optimus.keycdn.com/support/image-bulk-optimization/\" target=\"_blank\">Details</a>]", "optimus"); ?>
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

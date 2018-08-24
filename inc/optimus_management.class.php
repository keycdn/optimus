<?php


/* Quit */
defined('ABSPATH') OR exit;


/**
* Optimus_Management
*
* @since 1.3.8
*/

class Optimus_Management
{


    /**
    * Bulk optimizer media
    *
    * @since   1.3.8
    * @change  1.4.4
    */

    public static function bulk_optimizer_media() {
        check_admin_referer('bulk-media');

        if (empty($_GET['media']) || !is_array( $_GET['media'])) {
            return;
        }

        $ids = implode('-', array_map('intval', $_GET['media']));
        wp_redirect(add_query_arg(
            '_wpnonce',
            wp_create_nonce('optimus-bulk-optimizer'),
            admin_url("tools.php?page=optimus-bulk-optimizer&ids=$ids")
        ));
        exit();
    }


    /**
    * Add bulk optimizer page
    *
    * @since   1.3.8
    * @change  1.3.8
    */

    public static function add_bulk_optimizer_page()
    {
        /* Management page */
        add_management_page(
            __("Optimus Bulk Optimizer", "optimus"),
            __("Optimize all images", "optimus"),
            'upload_files',
            'optimus-bulk-optimizer',
            array(
                __CLASS__,
                'bulk_optimizer_page'
            )
        );
    }


    /**
    * Bulk optimizer collect assets
    *
    * @since   1.5.0
    *
    */

    public static function bulk_optimizer_assets() {
        global $wpdb;

        /* Get plugin options */
        $options = Optimus::get_options();

        /* Supported image types */
        $imageTypes = ['jpeg', 'png'];
        foreach ($imageTypes as &$imageType) {
           $imageType = "$wpdb->posts.post_mime_type = 'image/$imageType'"; 
        }
        $queryImageTypes = "(". join(" OR ", $imageTypes) .")";

        /* Check if images are already optimized */
        if ( $options['webp_convert'] ) {
            $optimus_query = '%optimus%webp";i:1%';
        } else {
            $optimus_query = '%optimus%';
        }

        /* Check if specific IDs are selected */
        if (!empty($_GET['ids'])) {
            $ids = implode(',', array_map('intval', explode('-', $_GET['ids'])));
            $id_query = "AND ID IN($ids)";
        } else {
            $id_query = "";
        }

        /* Image query */
        $query = "SELECT $wpdb->posts.ID, $wpdb->posts.post_title, $wpdb->posts.post_mime_type
            FROM $wpdb->posts, $wpdb->postmeta
            WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
                AND $wpdb->posts.post_type = 'attachment'
                AND $wpdb->posts.post_mime_type LIKE 'image/%'
                AND $queryImageTypes
                AND $wpdb->postmeta.meta_key = '_wp_attachment_metadata'
                AND $wpdb->postmeta.meta_value NOT LIKE '$optimus_query'
                $id_query
            ORDER BY $wpdb->posts.ID DESC";

        return $wpdb->get_results($query, ARRAY_A);
    }


    /**
    * Bulk optimizer page
    *
    * @since   1.3.8
    * @change  1.5.0
    *
    */

    public static function bulk_optimizer_page() {
        $assets = self::bulk_optimizer_assets();
        $count = count($assets);

        echo '<div class="wrap" id="optimus-bulk-optimizer">';
        echo '<h2>' . __("Optimus Bulk Optimizer", "optimus") . '</h2>';
        if ((empty($_POST['optimus-bulk-optimizer']) && empty($_GET['ids'])) || $count == 0) {
            echo '<p>' . __("The Optimus bulk optimizer compresses all images that have not yet been compressed in your WordPress media library.", "optimus") . '</p>';

            if ( Optimus_HQ::is_locked() ) {
                echo '<p>' . __("It is recommended to run the bulk image optimization with an Optimus HQ activated version due to the size limitation of the free version.", "optimus") . '</p>';
            }

            echo '<p><em>' . sprintf(__("Optimus found <strong>%d images</strong> in your WordPress media library that can be optimized.", "optimus"), $count) . '</em></p>';
            echo '<form method="POST" action="?page=optimus-bulk-optimizer">';
            echo '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce('optimus-bulk-optimizer') . '">';
            echo '<input type="hidden" name="optimus-bulk-optimizer" value="1">';
            echo '<p><input type="submit" name="submit" id="submit" class="button button-primary" value="'.__("Optimize all images", "optimus").'"></p>';
            echo '</form>';
        } else {
            check_admin_referer('optimus-bulk-optimizer');
            echo '<p>' . __("It might take a while until all images are optimized. This depends on the amount and size of the images.", "optimus") . '</p>';
            echo '<p><em>' . __("Note: Do not close this tab during the optimization process.", "optimus") . '</em></p>';

            echo '<div id="optimus-progress"><p>' . __("Completed", "optimus") . ' <span>0</span> / ' . sprintf(' %d </p></div>', $count);
            echo '<div id="media-items"></div>';

            echo '<script type="text/javascript">jQuery(function() { optimusBulkOptimizer('. json_encode($assets) . ')})</script>';
        }

        echo '</div>';
    }
}

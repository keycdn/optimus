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
	* Bulk optimizer page
	*
	* @since   1.3.8
	* @change  1.3.8
	*
	*/

	public static function bulk_optimizer_page() {
		global $wpdb;

        if (!empty($_GET['ids'])) {
            $ids = implode(',', array_map('intval', explode('-', $_GET['ids'])));
            $condition = "AND ID IN($ids)";
        } else {
            $condition = "";
        }

        $query = "SELECT ID, post_title, post_mime_type FROM $wpdb->posts WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%' $condition ORDER BY ID DESC";
        $assets = $wpdb->get_results($query, ARRAY_A);
        $count = count($assets);

		echo '<div class="wrap" id="optimus-bulk-optimizer">';
		echo '<h2>' . __("Optimus Bulk Optimizer", "optimus") . '</h2>';
		if (empty($_POST['optimus-bulk-optimizer']) && empty($_GET['ids'])) {
			echo '<p>' . __("The Optimus bulk optimizer compresses all images that have not yet been compressed in your WordPress media library.", "optimus") . '</p>';

            if ( Optimus_HQ::is_locked() ) {
                echo '<p>' . __("It is recommended to run the bulk image optimization with an Optimus HQ activated version due to the size limitation of the free version.", "optimus") . '</p>';
			}

            echo '<p><em>' . sprintf(__("Optimus found <strong>%d images</strong> in your WordPress media library.", "optimus"), $count) . '</em></p>';
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

<?php


/* Quit */
defined('ABSPATH') OR exit;


/**
* Optimus WP-CLI
*
*/

class Optimus_CLI extends WP_CLI_Command
{
    /**
     * Optimize command
     *
     * @since 1.6.0
     */

    public static function optimize( $args, $assoc_args ) {
        $assoc_args = wp_parse_args( $assoc_args, array( 'format' => 'table', 'num' => 10 ) );

        $assets = Optimus_Management::bulk_optimizer_assets();
        $assets = array_slice($assets, 0, $assoc_args['num']);

        foreach ($assets as $key=>$img) {
            $assets[$key]['result'] = self::_optimize_image($img);

            if ( $assets[$key]['result'] === false ) {
                $assets[$key]['profit']   = 0;
                $assets[$key]['quantity'] = 0;
                $assets[$key]['webp']     = '';
            } else {
                $assets[$key]['profit']   = $assets[$key]['result']['optimus']['profit'];
                $assets[$key]['quantity'] = $assets[$key]['result']['optimus']['quantity'];
                $assets[$key]['webp']     = $assets[$key]['result']['optimus']['webp'];
            }
        }

        $formatter = new \WP_CLI\Formatter( $assoc_args,
            array(
                'ID',
                'post_title',
                'post_mime_type',
                'profit',
                'quantity',
                'webp'
            ),
            'optimize' );
        $formatter->display_items( $assets );
    }

    /**
     * webp sync command.
     *
     * Checks if all the registered files really have the optimized version.
     * Note: Only works if convert to webp is enabled.
     */
    public static function syncMissingWebp () {
        $options = Optimus::get_options();
        if ($options['webp_convert'] == 0) {
            WP_CLI::error('webp sync command synchronizes the optimization status of attachments with actual files in the filesystem, but the webp feature is not enabled.', TRUE);
        }

        // Retrieve all post IDs with positive optimization status in database.
        $posts = Optimus_Management::bulk_optimized_assets();

        foreach ($posts as $key => $post) {
            $assets = Optimus_Request::get_files_paths($post['ID']);
            foreach ($assets as $asset_path) {
                if (stream_resolve_include_path($asset_path) === FALSE) {
                    $metadata = wp_get_attachment_metadata($post['ID']);
                    // Remove the optimus metadata when the file does not exist.
                    unset($metadata['optimus']);
                    update_post_meta($post['ID'], '_wp_attachment_metadata', $metadata);
                    // No need to check further files as the whole attachment
                    // will be re-optimized.
                    break;
                }
            }
        }
    }

    private static function _optimize_image($img) {
        /* get metadata */
        $metadata = wp_get_attachment_metadata($img['ID']);
        if (!is_array($metadata)) {
            // Metadata missing
            return false;
        }

        /* optimize image */
        $optimus_metadata = Optimus_Request::optimize_upload_images($metadata, $img['ID']);

        if ( !empty($optimus_metadata['optimus']['error']) ) {
            return false;
        }

        /* check if optimus array empty */
        if ( empty($optimus_metadata['optimus']) ) {
            return false;
        }

        /* update metadata */
        update_post_meta($img['ID'], '_wp_attachment_metadata', $optimus_metadata);

        return $optimus_metadata;
    }

    public static function add_commands() {
        $cmd_optimize = function( $args, $assoc_args ) { self::optimize( $args, $assoc_args ); };
        WP_CLI::add_command( 'optimus optimize', $cmd_optimize, array(
            'shortdesc' => 'Bulk optimize some images.',
            'synopsis'  => array(
                array(
                    'type'          => 'assoc',
                    'name'          => 'num',
                    'description'   => 'Batch size in number of images to process',
                    'optional'      => true,
                    'default'       => 10,
                ),
                array(
                    'type'          => 'assoc',
                    'name'          => 'format',
                    'description'   => 'Output results in format',
                    'optional'      => true,
                    'default'       => 'table',
                    'options'       => array( 'table', 'csv', 'json' ),
                ),
            ),
        ));

        $cmd_syncmissing = function() { self::syncMissingWebp(); };
        WP_CLI::add_command( 'optimus webp sync', $cmd_syncmissing, array(
            'shortdesc' => 'Synchronizes actual webp file optimization status and missing files on disk with database.',
        ));
    }
}


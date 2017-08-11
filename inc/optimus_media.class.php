<?php


/* Quit */
defined('ABSPATH') OR exit;


/**
* Optimus_Media
*
* @since 1.1.2
*/

class Optimus_Media
{


    /**
    * Media column output
    *
    * @since   0.0.1
    * @change  1.3.0
    *
    * @param   array  $columns  Available columns
    * @return  array            Renewed columns
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
    * Print Optimus values as column
    *
    * @since   0.0.1
    * @change  1.3.0
    *
    * @param   string   $column  Column name
    * @param   integer  $id      Current object ID
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
    * Returns the formatted column as HTML
    *
    * @since   0.0.1
    * @change  1.3.0
    *
    * @param   intval  $id  Object ID
    * @return  string       Column HTML
    */

    private static function _column_html($id)
    {
        /* Attachment metadata */
        $data = (array)wp_get_attachment_metadata($id);

        /* Data exists? */
        if ( empty($data['optimus']) OR ! is_array($data['optimus']) ) {
            return;
        }

        /* Array init */
        $optimus = $data['optimus'];

        /* Metadata exists? */
        if ( ! isset($optimus['quantity']) OR ! isset($optimus['profit']) ) {
            return;
        }

        return sprintf(
            '<div class="%s"><p>%d%%</p></div>',
            self::_pie_class(
                $optimus['quantity']
            ),
            $optimus['profit']
        );
    }


    /**
    * Specifies the CSS class depending on the amount of compressed files
    *
    * @since   0.0.8
    * @change  1.3.0
    *
    * @param   intval  $quantity  File quantity
    * @return  string             Optimus CSS class
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

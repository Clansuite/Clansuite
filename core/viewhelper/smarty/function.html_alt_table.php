<?php

/**
 * Smarty {html_alt_table} function plugin
 *
 * Type:     function
 * Name:     html_alt_table
 * Date:     Nov 29, 2004
 * Purpose:  make an html table from an array of arrays
 * Input:
 *         - loop = array to loop through
 *         - header = wether or not display the header line (using array keys of the first line)
 *         - table_id = provide a specific css id for the table ( default is "table")
 *
 *
 * Examples:
 * <pre>
 * {table loop=$data}
 * {table loop=$data header=true table_id='my_smarty_table'}
 * </pre>
 *
 * Install: Drop into the plugin directory
 *
 * Changes:
 *    2004-11-29 created
 *    2005-01-04 bug fixes (thanks to Lars Kappert)
 *
 * Notes:
 *
 * this piece of code is quite simple but i find it very useful...
 * feel free to improve it
 *
 */

/**
 * Additional Notes :
 *
 * html_alt_table is designed to display eadily 2-D arrays , i.e. arrays containing arrays,
 *
 * the function is easy to use as it only needs $loop to be an array of array
 * although there are two major limitations :
 *     1/ to define the table appearence, you MUST use CSS
 *     2/ $loops must be an array of rows and each row must be an array of cell
 *
 * -------------------------------------------------------------
 *
 * Practical Example:
 *
 * index.php:
 *
 * include'Smarty.class.php');
 * $smarty = new Smarty;
 * $line0=array("name"=>"Régis", "phone"=> "00000000", "age" =>"63");
 * $line1=array("name"=>"Léopoldine", "phone"=> "987654312", "age" =>"27");
 * $smarty->assign('data',array($line0,$line1));
 * $smarty->display('index.tpl');
 *
 * index.tpl:
 *
 * {html_alt_table loop=$data}
 *
 *
 * this will generate the following html code :
 *
 * <table id='table'>
 * <tr class='table_row_odd' id='table_row_header' >
 * <td class='table_col' id='table_col_name'>name</td>
 * <td class='table_col' id='table_col_phone'>phone</td>
 * <td class='table_col' id='table_col_age'>age</td>
 * </tr>
 * <tr class='table_row_even' id='table_row_0'>
 * <td class='table_col' id='table_col_name'>Régis</td>
 * <td class='table_col' id='table_col_phone'>00000000</td>
 * <td class='table_col' id='table_col_age'>63</td>
 * </tr>
 * <tr class='table_row_odd' id='table_row_1'>
 * <td class='table_col' id='table_col_name'>Léopoldine</td>
 * <td class='table_col' id='table_col_phone'>987654312</td>
 * <td class='table_col' id='table_col_age'>27</td>
 * </tr>
 * </table>
 *
 *
 */

/**
 * @name   smarty_function_html_alt_table
 * @author   damien clochard <damien@taadeem.net>
 * @version  1.1
 * @link
 * @param array
 * @param Smarty
 * @return string
 */

function smarty_function_html_alt_table($params, $smarty)
{
    // default values
    //
    // loop is mandatory

    $table_id= 'table';
    $header=true;

    if (!isset($params['loop']))
    {
        $smarty->trigger_error("html_alt_table: missing 'loop' parameter");
        return;
    }

    // reading input params
    //
    // this part is based on the original
    // html_table function
    foreach ($params as $_key=>$_value)
    {
        switch ($_key)
        {
            case 'loop':
                $$_key = (array) $_value;
                break;

            case 'table_id':
                $$_key = (string) $_value;
                break;
            case 'header':
                $$_key = (boolean) $_value;
                break;
        }
    }

    # init
    $rows_count=count($loop);

    // if the array is empty, there's no need to go further
    if ($rows_count==0) return;

    $first_line=$loop[0];
    $cols_count=count($first_line);

    $row_class=$table_id."_row";
    $row_class_odd=$row_class."_odd";
    $row_class_even=$row_class."_even";

    $col_class=$table_id."_col";

    // starting table
    //
    // This table will not contain style informations.
    // If you want to define your own  style , you *must*
    // defines a CSS styleheet using the correct id and
    // class names
    $output = "<table id='$table_id'>\n";


    // table headers
    //
    // We assume that the keys of the first array
    // are relevant as column names...
    if ($header)
    {
        $headers=array_keys($first_line);
        $css_id=$row_class."_header";
        $css_class=$row_class_odd;
        $output .= "<tr class='$css_class' id='$css_id' >\n";
        for ($h=0; $h<$cols_count; $h++)
        {
            $css_class=$col_class;
            $css_id=$col_class."_$headers[$h]";
            $output.="<td class='$css_class' id='$css_id'>";
            $output.="$headers[$h]";
            $output.="</td>\n";
        }
        $output .= "</tr>\n";
    }

    // table content
    //
    // each row is identified by an unique css id
    // and its class name indicates if it's an odd or
    // even line
    for ($r=0; $r<$rows_count; $r++)
    {
        $css_class=($r%2 == 0)?$row_class_even:$row_class_odd;
        $css_id=$row_class."_$r";
        $output .= "<tr class='$css_class' id='$css_id'>\n";

        $row=array_values($loop[$r]);

        // we assume that every row has the same number of columns that the first one
        // if it's not the case, you might want to uncomment the line above
        //
        //$cols_count=count($row);


        // display a line
        //
        // each <td> tag is identified by a css id
        $css_class=$col_class;
        for ($c=0; $c<$cols_count; $c++)
        {
            $css_id=$col_class."_$headers[$c]";

            $output.="<td class='$css_class' id='$css_id'>";
            $output.="$row[$c]";
            $output.="</td>\n";
        }
        $output .= "</tr>\n";
    }
    $output .= "</table>\n";

    return $output;
}
?>
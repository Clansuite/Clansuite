<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/**
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 **/

return array(

 'cc-js'  => array('//themes/core/javascript/jquery/jquery.js',
                   '//themes/core/javascript/jquery/jquery.ui.js',
                   '//themes/core/javascript/clip.js',
                   '//modules/menu/javascript/XulMenu.js'),
                    
 'cc-css' => array('//themes/admin/admin.css', 
                   '//themes/core/css/jquery-ui-peppergrinder/jquery-ui-1.7.2.custom.css', 
                   '//modules/menu/css/menu.css'),

    // 'js' => array('//js/file1.js', '//js/file2.js'),
    // 'css' => array('//css/file1.css', '//css/file2.css'),

    // custom source example
    /*'js2' => array(
        dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
        // do NOT process this file
        new Minify_Source(array(
            'filepath' => dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
            'minifier' => create_function('$a', 'return $a;')
        ))
    ),//*/

    /*'js3' => array(
        dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
        // do NOT process this file
        new Minify_Source(array(
            'filepath' => dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
            'minifier' => array('Minify_Packer', 'minify')
        ))
    ),//*/
);
<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * This smarty function is part of "Clansuite - just an eSports CMS"
 * @link http://www.clansuite.com
 *
 * @author      Jens-André Koch <jakoch@web.de>
 * @copyright   Copyright (C) 2009 Jens-André Koch
 * @license     GNU Public License (GPL) v2 or any later version
 * @version     SVN $Id$
 *
 * Name:        openflashchart
 * Type:        function
 * Purpose: This TAG inserts a openflashchart flash object.
 *
 * Calls the openflashchart object from templates-side with parameters applied
 * {openflashchart ...}
 * Parameters: width, heigth, url, data, swfobject, baseurl, debug
 *
 * Example usage:
 * {openflashchart width="200" height="200" url="http" data="" swfobject=false debug=false}
 *
 * @param array $params as described above
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_openflashchart($params, $smarty)
{
    include ROOT_LIBRARIES . '/open-flash-chart/php-ofc-library/open_flash_chart_object.php';

    # auto-prefix url with www_root if http is not in the url string
    $params['url'] = WWW_ROOT .'/'. $params['url'];

    $params += array(
                        'width'         => 320,
                        'height'        => 200,
                        'url'           => WWW_ROOT .'/'. $params['url'],
                        'swfobject'     => false,
                        'baseurl'       => WWW_ROOT . '/libraries/open-flash-chart/', # path to open-flash-chart.swf
    );

    open_flash_chart_object($params['width'], $params['height'], $params['url'], $params['swfobject'], $params['baseurl']);

    if(isset($params['debug']) and $params['debug'] == true or DEBUG == true)
    {
        echo '<br /> The source for the dynamic data is: <a target="_blank" href="'. $params['url'].'">'. $params['url'] .'</a>';
    }
}
?>
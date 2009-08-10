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
 * Name:     	openflashchart
 * Type:     	function
 * Purpose: This TAG inserts a openflashchart flash object.
 *
 * Calls the openflashchart object from templates-side with parameters applied
 * {openflashchart ...}
 * Parameters: width, heigth, url, data, swfobject, baseurl
 *
 * Example usage:
 * {openflashchart width="200" height="200" url="http" data="" swfobject=false}
 *
 * @param array $params as described above
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_openflashchart($params, &$smarty)
{
    require ( ROOT_LIBRARIES . "/ofc/php-ofc-library/open_flash_chart_object.php");

    $params += array(
                        'width'         => 320,
                        'height'        => 200,
                        'url'           => null,
                        'data'          => null,
                        'swfobject'     => false,
                        'baseurl'       => ROOT_LIBRARIES . '/ofc/', # path to open-flash-chart.swf
    );

    if ($params['return'] != null)
    {
        open_flash_chart_object($params['width'], $params['height'], $params['url'], $params['swfobject'], $params['baseurl']);
    }
    else
    {
        open_flash_chart_object_str($params['width'], $params['height'], $params['url'], $params['swfobject'], $params['baseurl']);
    }
}
?>
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
 * @author Jens-André Koch <jakoch@web.de>
 * @copyright Copyright (C) 2009 Jens-André Koch
 * @license GNU Public License (GPL) v2 or any later version
 * @version SVN $Id$
 *
 * Name:    icon
 * Type:    function
 * Purpose: This TAG inserts images/icons.
 *
 * Static Function to Call variable Methods from templates via
 * {icon ...}
 * Parameters: icondir, src, width, heigth, alt, extra
 *
 * Example Usage:
 * {icon name="rss"}
 * {icon theme="lullacons" name="calendar"}
 *
 * @param array $params as described above
 * @param Smarty $smarty
 * @return string
 */

function smarty_function_icon($params, &$smarty)
{
    #clansuite_xdebug::printR($params);

    extract($params);

    # set a icondir / iconpack
    if ( empty($icondir))
    {
        $icondir = 'icons';
    }
    else # icondir var incomming
    {
        # check if it is a valid one
        $icondir_whitelist = array( 'icons', 'lullacons' );
        if(in_array($icondir, $icondir_whitelist))
        {
            # valid
        }
        else # fallback to valid
        {
            $icondir = 'icons';
        }
    }

    # transform name into a valid image src
    if (empty($src) and isset($name))
    {
        $src = WWW_ROOT_THEMES_CORE . '/images/'.$icondir.'/'.$name.'.png';
    }

    # we got no height, set it to zero
    if (empty($height))
    {
        $height = 0;
    }

    # we got no width, ok then its zero again
    if (empty($width))
    {
        $width = 0;
    }

    # we got no height nor width. well let's detect it automatically then.
    if (($height == 0) or ($width == 0))
    {
        $currentimagesize = getimagesize($src);
        $width = $currentimagesize[0];
        $height= $currentimagesize[1];
    }

    # we got no alternative text. let's add a default text with $name;
    if(empty($alt))
    {
        $alt = 'Clansuite Icon: '.$name;
    }

    # no extra attributes to add, then let it be an empty string
    if(empty($extra))
    {
        $extra = '';
    }

    $html = "<img src='$src' height='$height' width='$width' alt='$alt' $extra />";

    return $html;
}
?>
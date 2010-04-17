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

function smarty_function_icon($params, $smarty)
{

    extract($params);
    
    /**
     * if the src attribute contains a http://SERVER_NAME URL its cutted of
     */
    if(isset($src) and empty($src) == false)
    {
        $needle = 'http://'.$_SERVER['SERVER_NAME'].'/';
        $pos = strpos($src, $needle);    
        if(isset($src) and is_int($pos))
        {
            #clansuite_xdebug::printR($pos);
            $src = substr($src, $pos + strlen($needle));
            $name = basename($src);
        }
    }
    
    # we have two alternatives :
    # a) src => user has set src, defining the path to the image and imagename
    # b) icondir, name => user has defined the icons dir (relative to core/images folder) and the name of a png file    
    
    # check if it is a valid one
    $icondir_whitelist = array( 'icons', 'lullacons' );
    if(isset($icondir) and in_array($icondir, $icondir_whitelist))
    {
        # valid
    }
    else # fallback to a valid default
    {
        $icondir = 'icons';
    }

    # if we got no valid src, set a default image
    if(isset($src) and is_file($src) == false)
    {
        #$src = WWW_ROOT_THEMES_CORE . '/images/noimage.gif';
        $src = ROOT_THEMES . 'core/images/noimage.gif';
        $name = 'No Image found.'.$src;
    }

    # transform name into a valid image src
    if (isset($icondir) and isset($name) and empty($src))
    {
        #$src = WWW_ROOT_THEMES_CORE . '/images/'.$icondir.'/'.$name.'.png';
        $src = ROOT_THEMES . 'core/images/'.$icondir.'/'.$name.'.png';
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
    if(isset($src) and empty($alt))
    {
		$file = $src;

		$info = pathinfo($file);
		$file_name =  basename($file,'.'.$info['extension']);
        $alt = $file_name;
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
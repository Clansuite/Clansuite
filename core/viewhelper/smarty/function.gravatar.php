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
 * @copyright Copyright (C) 2008 Jens-André Koch
 * @license GNU Public License (GPL) v2 or any later version
 * @version SVN $Id$
 *
 * Name:     	gravatar
 * Type:     	function
 * Purpose: This TAG inserts a valid Gravatar Image.
 *
 * See http://en.gravatar.com/ for further information.
 *
 * Parameters:
 * - email      = the email to fetch the gravatar for (required)
 * - size       = the images width
 * - rating     = the highest possible rating displayed image [ G | PG | R | X ]
 * - default    = full url to the default image in case of none existing OR
 *                invalid rating (required, only if "email" is not set)
 *
 * Example usage:
 *
 * {gravatar email="example@example.com" size="40" rating="R" default="http://myhost.com/myavatar.png"}
 *
 * @param array $params as described above (emmail, size, rating, defaultimage)
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_gravatar($params, $smarty)
{
	# check for email adress
	if(isset($params['email'])) { $email = trim(strtolower($params['email'])); }
	else
	{
	    $smarty->trigger_error("Gravatar Image couldn't be loaded! Parameter 'email' not specified!");
		return;
	}

	# default avatar
	if(isset($params['default']))   {   $defaultImage = urlencode($params['default']);	} else { $defaultImage = ''; }
	# size
	if(isset($params['size']))	    {   $size = $params['size'];	} else { $size = ''; }
	# rating
	if(isset($params['rating']))    {   $rating = $params['rating']; } else { $rating = ''; }

	# initialize gravatar library
	if (!class_exists('clansuite_gravatar')) // prevent redeclaration
    {
	    require ROOT_LIBRARIES . '/gravatar/clansuite_gravatar.class.php';
    }
    $gravatar = new clansuite_gravatar($email, $rating, $size, $defaultImage);
    return $gravatar;
}
?>
<?php
   /**
    * Clansuite - just an eSports CMS
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id: news.module.php 2753 2009-01-21 22:54:47Z vain $
    */

//Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Modulenavigation for Module Matches
 */
$modulenavigation = array(
    '1' => array(
        'action' => 'show',
        'name' => 'Overview',
        'url' => '/matches/admin', # &action=show
        'icon' => '',
        'title' => ''
    ),
    '2' => array(
        'action' => 'create',
        'name' => 'Create new',
        'url' => '/matches/admin/create',
        'icon' => '',
        'title' => ''
    ),
    '3' => array(
        'action' => 'settings',
        'name' => 'Settings',
        'url' => '/matches/admin/settings',
        'icon' => '',
        'title' => ''
    ),
);
?>
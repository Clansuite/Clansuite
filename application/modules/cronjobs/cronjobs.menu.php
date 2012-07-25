<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * GNU/GPL v2 or any later version; see LICENSE file
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    *
    *

    */

/**
 * Clansuite Modulenavigation for Module Cronjobs
 */
$modulenavigation = array(
    '1' => array(
        'action' => 'show',
        'name' => 'Overview',
        'url' => '/cronjobs/admin', # &action=show
        'icon' => '',
        'title' => ''
    ),
    '2' => array(
        'action' => 'create',
        'name' => 'Create new',
        'url' => '/cronjobssub=admin&action=create',
        'icon' => '',
        'title' => ''
    ),
    '3' => array(
        'action' => 'settings',
        'name' => 'Settings',
        'url' => '/cronjobs/admin/settings',
        'icon' => '',
        'title' => ''
    ),
);
?>
<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * 
    *
    * @version    SVN: $Id: news.module.php 2753 2009-01-21 22:54:47Z vain $
    */

//Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Modulenavigation for Module Modulemanager
 */

$modulenavigation = array(
                            '1' => array(
                                            'action'  => 'show',
                                            'name'    => 'Overview',
                                            'url'      => '/modulemanager/admin', # &action=show
                                            'icon'    => '',
                                            'title' => ''
                                        ),

                            '2' => array(
                                            'action'  => 'builder',
                                            'name'    => 'Create with Modulebuilder',
                                            'url'     => '/modulemanager/admin/builder',
                                            'icon'    => '',
                                            'title' => ''
                                        ),
                                        
                            '3' => array(
                                            'action'  => 'install',
                                            'name'    => 'Install',
                                            'url'     => '/modulemanager/admin/install',
                                            'icon'    => '',
                                            'title' => ''
                                        ),

                            '4' => array(
                                            'action'  => 'edit',
                                            'name'    => 'Edit',
                                            'url'     => '/modulemanager/admin/edit',
                                            'icon'    => '',
                                            'title' => ''
                                        ),
                                        
                            '5' => array(
                                            'action'  => 'export',
                                            'name'    => 'Export',
                                            'url'     => '/modulemanager/admin/export',
                                            'icon'    => '',
                                            'title' => ''
                                        ),

                            '6' => array(
                                            'action'  => 'settings',
                                            'name'    => 'Settings',
                                            'url'     => '/modulemanager/admin/settings',
                                            'icon'    => '',
                                            'title' => ''
                                        ),
                         );

/**
 * Clansuite Adminmenu for Module Modulemanager
 */

$adminmenu        = array(
                             '1' => array(
                                            'name'       => '',
                                            'url'        => '',
                                            'tooltip'    => '',
                                            'target'     => '',
                                            'permission' => '',
                                            'icon'       => ''
                                         ),
                         );
?>
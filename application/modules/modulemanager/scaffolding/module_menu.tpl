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
    * @link       http://gna.org/projects/clansuite
    *

    */

//Security Handler
if (!defined('IN_CS')){ die('Direct Access forbidden.' );}

/**
 * Clansuite Modulenavigation for Module {$mod.module_name|capitalize}
 */

$modulenavigation = array(
                            '1' => array(
                                            'action'  => 'show',
                                            'name'    => 'Overview',
										    'url'	  => 'index.php?mod=news&sub=admin', # &action=show
										    'icon'    => '',
										    'tooltip' => ''
										),

							'2' => array(
							                'action'  => 'create',
							                'name'    => 'Create new',
										    'url'     => 'index.php?mod=news&sub=admin&action=create',
										    'icon'    => '',
										    'tooltip' => ''
										),

						    '3' => array(
							                'action'  => 'settings',
							                'name'    => 'Settings',
										    'url'     => 'index.php?mod=news&sub=admin&action=settings',
										    'icon'    => '',
										    'tooltip' => ''
										),
						 );

/**
 * Clansuite Adminmenu for Module News
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
<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
    * http://www.clansuite.com/
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-2008)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Module:       Admin
 *
 * @package clansuite
 * @subpackage module_admin
 * @category modules
 */
class module_admin extends ModuleController implements Clansuite_Module_Interface
{

    public function __construct(Phemto $injector=null)
    {
        parent::__construct(); # run constructor on ModuleController
    }

    /**
     * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
     * @desc Loads necessary language files
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
        # read module config
        #$this->config->readConfig( ROOT_MOD . '/admin/admin.config.php');
    }

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loading necessary language files
    */

    function auto_run()
    {
        global $lang, $trail;

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Admin'), '/index.php?mod=admin');

        switch ($_REQUEST['action'])
        {
            default:
            case 'show':
                $trail->addStep($lang->t('Control Center'), '/index.php?mod=admin');
                $this->show();
            break;
        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
     * Show the welcome to adminmenu and shortcuts
     */

    public function action_show()
    {
        #$user->hasAccess('admin','show');

        # Get Render Engine
        $smarty = $this->getView();
        
        /*
        $row    = 0;
        $col    = 0;
        $images = array();       
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'adminmenu_shortcuts ORDER BY `cat` DESC, `order` ASC, title ASC' );
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ( is_array ( $result ) )
        {
            foreach( $result as $data )
            {
                $col++;
                $images[$row][$col] = $data;

                if ( $col == 4 )
                {
                    $row = $row+1;
                    $col = 0;
                }
            }
        }

        /*
        $files = array( 'console', 'downloads', 'articles', 'links', 'calendar', 'time', 'email', 'shoutbox', 'help', 'security', 'gallery', 'system', 'replays', 'news', 'settings', 'users', 'backup', 'templates' );
        $stmt = $db->prepare( "INSERT INTO cs_adminmenu_shortcuts ( href, title, file_name ) VALUES ( ?, ?, ? )" );
        foreach( $files as $key )
        {
            $stmt->execute( array( 'index.php?mod=admin&sub='.$key, $key, $key.'.png' ) );
        }
        */

        #$smarty->assign( 'shortcuts', $images );
        #$this->output .= $smarty->fetch('admin/welcome.tpl');
        #$this->output .= $smarty->fetch('admin/shortcuts.tpl');
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        $this->setTemplate('admin/welcome.tpl');
        # Prepare the Output
        $this->prepareOutput();
    }
}
?>
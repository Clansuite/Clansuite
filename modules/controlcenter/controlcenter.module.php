<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Modul:  ControlCenter (Backend)
 *
 * @package clansuite
 * @subpackage module_controlcenter
 * @category modules
 */
class Module_ControlCenter extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Admin -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # read module config
        #$this->config->readConfig( ROOT_MOD . '/admin/admin.config.php');
    }

    /**
     * Show the welcome to adminmenu and shortcuts
     */
    public function action_show()
    {
        #$user::hasAccess('admin','show');

        # Get Render Engine
        $view = $this->getView();

        # Load DBAL
        #parent::getInjector()->instantiate('clansuite_doctrine')->doctrine_initialize();

        $row    = 0;
        $col    = 0;
        $images = array();

        $result = Doctrine_Query::create()
                                 ->select('s.*')
                                 ->from('CsAdminmenuShortcut s')
                                 ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                 #->setHydrationMode(Doctrine::HYDRATE_NONE)
                                 ->orderby('s.cat DESC, s.order ASC, s.title ASC')
                                 ->execute();
        #var_dump($result);

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

        /* Insert Entry

        $files = array( 'console', 'downloads', 'articles', 'links', 'calendar', 'time', 'email', 'shoutbox', 'help', 'security', 'gallery', 'system', 'replays', 'news', 'settings', 'users', 'backup', 'templates' );
        $stmt = $db->prepare( "INSERT INTO cs_adminmenu_shortcuts ( href, title, file_name ) VALUES ( ?, ?, ? )" );
        foreach( $files as $key )
        {
            $stmt->execute( array( 'index.php?mod=controlcenter&sub='.$key, $key, $key.'.png' ) );
        }*/

        $view->assign( 'shortcuts', $images );

        # Fetch Render Engine and Set Layout
        $view->setLayoutTemplate('admin/index.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }


    /**
     * Show the Informations to submit a Bug
     */
    public function action_bugs()
    {
        #$user::hasAccess('admin','bugs');

        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Report Bugs &amp; Issues'), '/index.php??mod=controlcenter&amp;action=bugs');

        # Fetch Render Engine and Set Layout
        $this->getView()->setLayoutTemplate('admin/index.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Show the Informations to submit a Bug
     */
    public function action_about()
    {
        #$user::hasAccess('admin','about');

        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('About Clansuite'), '/index.php?mod=controlcenter&amp;action=about');

        # Fetch Render Engine and Set Layout
        $this->getView()->setLayoutTemplate('admin/index.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }
}
?>
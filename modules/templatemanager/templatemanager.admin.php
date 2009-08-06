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
 * Clansuite Module Administration - Templatemanager
 *
 * @author      Jens-André Koch  <vain@clansuite.com>
 * @copyright   Jens-André Koch, (2005 - onwards)
 * @since       0.2alpha
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Templatemanager
 */
class Module_Templatemanager_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Main Method of Templatemanager Module
     *
     * Sets up module specific stuff, needed by all actions of the module
     * Calls the requested Action $_REQUEST['action']
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # read module config
        $this->config->readConfig( ROOT_MOD . '/templatemanager/templatemanager.config.php');
    }

    public function action_admin_show()
    {
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_editor method for the Templatemanager module
     * @param void
     * @return void
     */
    public function action_admin_editor()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Editor'), '/index.php?mod=templatemanager&amp;action=editor');

        $file = ROOT_MOD . stripslashes($_GET['file']);
        #$file = ROOT_MOD . 'templatemanager\templates\action_admin_editor.tpl';

        #Clansuite_xdebug::printr($file);

        if(is_file($file))
        {
            $handle = fopen($file, 'r') or die('Unable to open the file');
            $templateText = fread($handle, filesize($file));
            fclose($handle);
        }

        $smarty = $this->getView();
        $smarty->assign('templateName', $file);
        $smarty->assign('templateText', htmlentities($templateText));

        #$request = $this->injector->instantiate('Clansuite_HttpRequest');
        /**
         *

         if ( $_POST['form_submit'] )
         {
           $file = 'template.tpl';
           $handle = fopen($file, 'w') or die('Unable to open the file');
           fwrite($handle, $_POST['template']);
           fclose($handle);
        }
        */

        # Prepare the Output
        $this->prepareOutput();
    }
}
?>
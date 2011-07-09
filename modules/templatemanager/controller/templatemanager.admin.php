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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Templatemanager_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Templatemanager
 */
class Clansuite_Module_Templatemanager_Admin extends Clansuite_Module_Controller
{
    public function initializeModule()
    {
        $this->getModuleConfig();
    }

    public function action_admin_show()
    {
        $modulenames = Clansuite_ModuleInfoController::getModuleNames(false, true);

        # create a new form
        $form = new Clansuite_Form('module_select_dropdown_form', 'post', '/menu/admin/modulemenu_edit');

        # select dropdown for modules
        $form->addElement('select')->setName('menu_select_form[modulename]')->setLabel(_('Module'))->setOptions($modulenames);

        # add the buttonbar
        $form->addElement('buttonbar')->getButton('cancelbutton')->setCancelURL('index.php?mod=templatemanager&sub=admin');

        # assign the html of the form to the view
        $this->getView()->assign('module_select_dropdown_form', $form->render());
        $this->display();
    }

    /**
     * Show all templates for a certain module
     */
    public function action_admin_showmoduletemplates()
    {
        $view = $this->getView();

        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Editor'), '/templatemanager/admin/showmoduletemplates');

        # Incomming Variables

        # GET: tplmod (module of the template)
        $modulename = $this->request->getParameter('modulename', 'GET');
        $modulename = strtolower(stripslashes($modulename));

        $view->assign('templateeditor_modulename', $modulename);
        $view->assign('templates', $this->getTemplatesOfModule($modulename));

        $this->display();
    }

    public function getTemplatesOfModule($module)
    {
        $templates = array();
        $i = 0;

        $tpls = glob(ROOT_MOD . $module . DS . 'view' . DS . 'smarty' . DS . '*.tpl');

        foreach ( $tpls as $filename )
        {
            ++$i;
            $templates[$i]['filename'] = basename($filename);
            $templates[$i]['fullpath'] = $filename;
        }

        return $templates;
    }

    public function action_admin_create()
    {
        $this->action_admin_editor();
    }

    public function action_admin_edit()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Edit'), '/templatemanager/admin/edit');

        $view = $this->getView();

        # Incomming Variables

        $tplmod   = $this->request->getParameter('tplmod', 'GET');
        $tpltheme = $this->request->getParameter('tpltheme', 'GET');
        $file     = $this->request->getParameter('file', 'GET');

        #Clansuite_Debug::firebug($file);

        # check if we edit a module template or a theme template
        if(isset($tplmod) )
        {
            $tplmod = ucfirst(stripslashes($tplmod));
            $view->assign('templateeditor_modulename',  $tplmod);
            #Clansuite_Debug::firebug($tplmod);
        }
        elseif(isset($tpltheme))
        {
            $tpltheme = stripslashes($tpltheme);
            $view->assign('templateeditor_themename',   $tpltheme);
            #Clansuite_Debug::firebug($tpltheme);
        }

        if(isset($file))
        {
            $relative_file = '';

            # construct relative template filename
            if(empty($tplmod) == false)
            {
                $relative_file = 'modules/'.$file;
            }
            elseif(empty($tpltheme) == false)
            {
                $relative_file = $tpltheme.'/'.$file;
            }
            else
            {
                $relative_file = $file;
            }

            $view->assign('templateeditor_relative_filename', $relative_file);

            $file = realpath(ROOT_MOD . $file);
            $view->assign('templateeditor_absolute_filename', $file);
            $view->assign('templateeditor_filename', $file);
        }

        $templateText = '';
        $templateeditor_newfile = '';

        # let's check, if this template exists
        if(is_file($file))
        {
            # ok, it does exist - fetch it's content
            $handle = fopen($file, 'r') or die('Unable to open the file. Apply correct permissions.');
            $templateText = fread($handle, filesize($file));
            fclose($handle);

            $templateeditor_newfile = false;
        }
        else # template does not exist

        {
            # fetch a template for rapidly setting up the new template :)
            $templateText =  $view->fetch('create_new_template.tpl');

            $templateeditor_newfile = true;
        }

        $view->assign('templateeditor_textarea',    htmlentities($templateText));
        $view->assign('templateeditor_newfile',     $templateeditor_newfile);

        $this->display();
    }

    public function action_admin_save()
    {
        #Clansuite_Debug::printR($this->getHttpRequest());

        $tplfilename    = (string) $this->request->getParameter('templateeditor_absolute_filename');
        $tplmodulename  = (string) $this->request->getParameter('templateeditor_modulename');
        $tplthemename   = (string) $this->request->getParameter('templateeditor_themename');
        $tpltextarea    = (string) $this->request->getParameter('templateeditor_textarea');

        if(empty($tplfilename) == false and isset($tpltextarea))
        {
            #Clansuite_Functions::force_file_put_contents($tplfilename, stripslashes($tpltextarea));
            file_put_contents($tplfilename, stripslashes($tpltextarea));
        }

        $message = 'Template "' . htmlentities($tplfilename) . '"saved.';
        $this->redirect('/templatemanager/admin', 0, 201, 'success#' . $message);
    }

    public function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), '/templatemanager/admin/settings');

        $this->display();
    }
}
?>
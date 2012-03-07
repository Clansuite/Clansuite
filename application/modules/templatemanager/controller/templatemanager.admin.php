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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
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
    public function _initializeModule()
    {
        $this->getModuleConfig();
    }

    public function action_admin_list()
    {
        # fetch modulenames as numeric indexed array
        $modules = Clansuite_ModuleInfoController::getModuleNames(false, true);
        $themes = Clansuite_Theme::getThemeDirectories();

        # create a new form
        $form = new Clansuite_Form('module_select_dropdown_form', 'post', '/templatemanager/admin/edit');
        $form->setLegend(_('Select Module or Theme to edit'));

        # select dropdown for modules
        $form->addElement('select')->setName('select_form[module]')->setLabel(_('Module'))
                ->setOptions($modules)->withValuesAsKeys();

        # select dropdown for themes
        $form->addElement('select')->setName('select_form[theme]')->setLabel(_('Theme'))
                ->setOptions($themes)->withValuesAsKeys();;

        # add the buttonbar
        $form->addElement('buttonbar')->setCancelButtonURL('index.php?mod=templatemanager&sub=admin');

        # assign the html of the form to the view
        $this->getView()->assign('module_select_dropdown_form', $form->render());

        $this->display();
    }

    /**
     * Show all templates for a certain module
     */
    public function action_admin_showmoduletemplates()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Editor'), '/templatemanager/admin/showmoduletemplates');

        # Incomming Variables
        $modulename = $this->request->getParameter('modulename', 'GET');
        $modulename = strtolower(stripslashes($modulename));

        $view = $this->getView();
        $view->assign('templateeditor_modulename', $modulename);
        $view->assign('templates', Clansuite_ModuleInfoController::getTemplatesOfModule($modulename));
        $this->display();
    }

    public function action_admin_new()
    {
        $this->action_admin_edit();
    }

    public function action_admin_edit()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Edit'), '/templatemanager/admin/edit');

        $view = $this->getView();

        # Incomming Variables
        if($this->request->getRequestMethod() == 'GET')
        {
            $module = $this->request->getParameterFromGet('tplmod');
            $theme = $this->request->getParameterFromGet('tpltheme');
            $file = $this->request->getParameterFromGet('file');
        }

        if($this->request->getRequestMethod() == 'POST')
        {
            $form = $this->request->getParameterFromPost('select_form');

            $module = empty($form['module']) ? null : $form['module'];
            $theme = empty($form['theme']) ? null : $form['theme'];
        }

        /**
         * We edit either a module or a theme file.
         */
        if(isset($module))
        {
            $file_absolute = ROOT_MOD . $module;

            $view->assign('templateeditor_modulename', ucfirst(stripslashes($module)));

        }
        elseif(isset($theme))
        {
            $file_absolute = ROOT_THEMES . $theme;

            $view->assign('templateeditor_themename', stripslashes($theme));
        }

        /**
         * we have the base of the requested file
         */
        if(isset($file))
        {
            $relative_file = '';

            # construct relative template filename
            if(isset($module))
            {
                $relative_file = 'modules'.DS.$file;
            }
            elseif(isset($theme))
            {
                $relative_file = $theme.DS.$file;
            }
            else
            {
                $relative_file = $file;
            }
        }

        $view->assign('templateeditor_relative_filename', $relative_file);
        $view->assign('templateeditor_absolute_filename', dirname($file_absolute) . DS . $file);
        $view->assign('templateeditor_filename', pathinfo($file, 6));

        $templateContent = '';
        $templateeditor_newfile = '';

        # let's check, if this template exists
        if(is_file($file))
        {
            # ok, it does exist - fetch it's content
            $handle = fopen($file, 'r') or die('Unable to open the file. Apply correct permissions.');
            $templateContent = fread($handle, filesize($file));
            fclose($handle);

            $templateeditor_newfile = false;
        }
        else # template does not exist

        {
            # fetch a template for rapidly setting up the new template :)
            $templateContent =  $view->fetch('create_new_template.tpl');

            $templateeditor_newfile = true;
        }

        $view->assign('templateeditor_textarea',    htmlentities($templateContent));
        $view->assign('templateeditor_newfile',     $templateeditor_newfile);

        #Clansuite_Debug::dump($view->getTemplateVars());

        $this->display();
    }

    public function action_admin_save()
    {
        #Clansuite_Debug::printR($this->getHttpRequest());

        $filename    = (string) $this->request->getParameter('templateeditor_absolute_filename');
        $modulename  = (string) $this->request->getParameter('templateeditor_modulename');
        $themename   = (string) $this->request->getParameter('templateeditor_themename');
        $textarea    = (string) $this->request->getParameter('templateeditor_textarea');

        if(empty($filename) == false and isset($textarea))
        {
            #Clansuite_Functions::force_file_put_contents($tplfilename, stripslashes($tpltextarea));
            file_put_contents($filename, stripslashes($textarea));
        }

        $message = 'Template "' . htmlentities($filename) . '"saved.';
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
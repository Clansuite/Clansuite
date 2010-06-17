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
    * @link       http://gna.org/projects/
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false)
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
class Clansuite_Module_Templatemanager_Admin extends Clansuite_Module_Controller implements Clansuite_Module_Interface
{
    public function initializeModule(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        $this->getModuleConfig();
    }

    public function action_admin_show()
    {
        $this->display();
    }

    /**
     * Show all templates for a certain module
     */
    public function action_admin_showmoduletemplates()
    {
        $view = $this->getView();

        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Editor'), '/index.php?mod=templatemanager&amp;sub=admin&amp;action=showmoduletemplates');

        # Incomming Variables

        # GET: tplmod (module of the template)
        $tplmod = $this->getHttpRequest()->getParameter('tplmod', 'G');
        $tplmod = ucfirst(stripslashes($tplmod));

        $view->assign('templateeditor_modulename',  $tplmod);

        clansuite_xdebug::firebug( ROOT_MOD . $tplmod . DS. 'view' .DS . '*.tpl' );

        $templates = $this->getTemplatesOfModule($tplmod);

        $view->assign('view', $templates);

        $this->display();
    }

    public function getTemplatesOfModule($tplmod)
    {
        $templates = array();
        $i = 0;

        $tpls = glob( ROOT_MOD . $tplmod . DS. 'view' .DS . '*.tpl' );

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
        Clansuite_Breadcrumb::add( _('Edit'), '/index.php?mod=templatemanager&amp;sub=admin&amp;action=edit');

        $view = $this->getView();

        # Incomming Variables

        $tplmod   = $this->getHttpRequest()->getParameter('tplmod', 'G');
        $tpltheme = $this->getHttpRequest()->getParameter('tpltheme', 'G');
        $file     = $this->getHttpRequest()->getParameter('file', 'G');

        Clansuite_Xdebug::firebug($file);

        # check if we edit a module template or a theme template
        if(isset($tplmod) )
        {
            $tplmod = ucfirst(stripslashes($tplmod));
            $view->assign('templateeditor_modulename',  $tplmod);
            Clansuite_Xdebug::firebug($tplmod);
        }
        elseif(isset($tpltheme))
        {
            $tpltheme = stripslashes($tpltheme);
            $view->assign('templateeditor_themename',   $tpltheme);
            Clansuite_Xdebug::firebug($tpltheme);
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
            $file = Clansuite_Functions::slashfix(ROOT_MOD . $file);
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
        #Clansuite_Xdebug::printR($this->getHttpRequest());

        $tplfilename    = (string) $this->getHttpRequest()->getParameter('templateeditor_absolute_filename');
        $tplmodulename  = (string) $this->getHttpRequest()->getParameter('templateeditor_modulename');
        $tplthemename   = (string) $this->getHttpRequest()->getParameter('templateeditor_themename');
        $tpltextarea    = (string) $this->getHttpRequest()->getParameter('templateeditor_textarea');

        if(empty($tplfilename) == false and isset($tpltextarea))
        {
            Clansuite_Functions::force_file_put_contents($tplfilename, stripslashes($tpltextarea));
        }

        $view = $this->getView();
        $view->assign('templateeditor_absolute_filename', htmlentities($tplfilename));

        $this->display();
    }

    public function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), '/index.php?mod=templatemanager&amp;sub=admin&amp;action=settings');

        $this->display();
    }
}
?>
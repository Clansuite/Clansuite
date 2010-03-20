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

// Security Handler
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Module Administration - Templatemanager
 *
 * @author      Jens-André Koch  <vain@clansuite.com>
 * @copyright   Jens-André Koch, (2005 - onwards)

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
    
    private static function force_file_put_contents($dir, $contents)
    {
        $parts = explode(DS, $dir);
        $file = array_pop($parts);
        $dir = '';
        $i = 0;
        
        foreach($parts as $part)
        {
            if($i == 0)
            {
                $dir .= $part;
            }
            else
            {
                $dir .= DS . $part;
            }
            $i++;
            
            if( is_dir($dir) == false ) 
            {
                mkdir($dir);
            }
        }
        
        file_put_contents("$dir".DS."$file", $contents);
    }
    
    /**
     * Show all templates for a certain module
     */
    public function action_admin_showmoduletemplates()
    {
        # get view
        $view = $this->getView();
         
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Editor'), '/index.php?mod=templatemanager&amp;sub=admin&amp;action=showmoduletemplates');
        
        # Incomming Variables

        # GET: tplmod (module of the template)
        $tplmod = $this->getHttpRequest()->getParameter('tplmod','G');
        $tplmod = ucfirst(stripslashes($tplmod));
        
        $view->assign('templateeditor_modulename',  $tplmod);
        
        clansuite_xdebug::firebug( ROOT_MOD . $tplmod . DS. 'templates' .DS . '*.tpl' );
        
        $templates = $this->gettemplatesofmodule($tplmod);   
        
        #clansuite_xdebug::printR($templates);     
        
        $smarty->assign('templates', $templates);        
        
        # Prepare the Output
        $this->prepareOutput();
    }
    
    public function gettemplatesofmodule($tplmod)
    {
        $templates = array();
        $i = 0;
        
        foreach ( glob( ROOT_MOD . $tplmod . DS. 'templates' .DS . '*.tpl' ) as $filename )
        {   
            ++$i;
            $templates[$i]['filename'] = basename($filename);
            $templates[$i]['fullpath'] = $filename;
        }
        
<<<<<<< .mine
        return $templates;
        /*
        $_POST['dir'] = urldecode($_POST['dir']);

        if( file_exists( ROOT . $_POST['dir']) )
        {
        	$files = scandir( ROOT . $_POST['dir']);
        	
            natcasesort($files);
        	
            if( count($files) > 2 )
            { /* The 2 accounts for . and .. */
        		/*
                echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
        		
                // All dirs
        		foreach( $files as $file )
                {
        			if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file) )
                    {
        				echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
        			}
        		}
        		
                // All files
        		foreach( $files as $file )
                {
        			if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file) )
                    {
        				$ext = preg_replace('/^.*\./', '', $file);
        				echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\">" . htmlentities($file) . "</a></li>";
        			}
        		}
        		echo "</ul>";	
        	}
        }*/
=======
        #clansuite_xdebug::printR($templates);     
        
        $view->assign('templates', $templates);        
        
        # Prepare the Output
        $this->prepareOutput();
>>>>>>> .r4276
    }
    
    public function action_admin_create()
    {
        $this->action_admin_editor();
    }

    /**
     * The action_admin_editor method for the Templatemanager module
     * @param void
     * @return void
     */
    public function action_admin_edit()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Edit'), '/index.php?mod=templatemanager&amp;sub=admin&amp;action=edit');
        
        # get view
        $view = $this->getView();
        
        # Incomming Variables

        # GET: tplmod (module of the template)
        $tplmod = $this->getHttpRequest()->getParameter('tplmod');
        # GET: tpltheme (theme of the template)
        $tpltheme = $this->getHttpRequest()->getParameter('tplmod');
        
        # check if we edit a module template or a theme template
        if(isset($tplmod) )
        {
            $tplmod = ucfirst(stripslashes($tplmod));
            $view->assign('templateeditor_modulename',  $tplmod);
            #Clansuite_Xdebug::printR($tplmod);
        }
        elseif(isset($tpltheme))
        {
            $tpltheme = stripslashes($tpltheme);
            $view->assign('templateeditor_themename',   $tpltheme);
            #Clansuite_Xdebug::printR($tpltheme);
        }
              
        # GET: template filename
        $file = $this->getHttpRequest()->getParameter('file');
        #Clansuite_Xdebug::printR($file);
        
        #Clansuite_Xdebug::firebug($file);
        
        if(isset($file))
        {              
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
            
            $smarty->assign('templateeditor_relative_filename', $relative_file);
        
            $file = ROOT_MOD . $file;
           
           # DS on win is "\"
            if( DS == '\\')
            {
                # correct slashes
                $file = str_replace('/', '\\', $file);
<<<<<<< .mine
            }

            $smarty->assign('templateeditor_absolute_filename', $file);
=======
            } 
            
            #Clansuite_Xdebug::printR($file);
            $view->assign('templateeditor_filename', $file);
>>>>>>> .r4276
        }

        # let's check, if this template exists
        if(is_file($file))
        {
            # ok, it does exist - fetch it's content
            $handle = fopen($file, 'r') or die('Unable to open the file');
            $templateText = fread($handle, filesize($file));
            fclose($handle);

            $templateeditor_newfile = false;
        } 
        else # template does not exist
        {   
            # fetch a template for rapidly setting up the new template :)
            $templateText =  $smarty->fetch('create_new_template.tpl');

            $templateeditor_newfile = true;
        }
               
        $view->assign('templateeditor_textarea',    htmlentities($templateText));
        $view->assign('templateeditor_newfile',     $templateeditor_newfile);

        # Prepare the Output
        $this->prepareOutput();
    }

    public function action_admin_save()
    {
        #Clansuite_Xdebug::printR($this->getHttpRequest());

        $tplfilename    = (string) $this->getHttpRequest()->getParameter('templateeditor_absolute_filename');
        $tplmodulename  = (string) $this->getHttpRequest()->getParameter('templateeditor_modulename');
        $tplthemename   = (string) $this->getHttpRequest()->getParameter('templateeditor_themename');
        $tpltextarea    = (string) $this->getHttpRequest()->getParameter('templateeditor_textarea'); #@todo disable IDS check for this var

        if(empty($tplfilename) == false and isset($tpltextarea))
        {
            self::force_file_put_contents($tplfilename, stripslashes($tpltextarea));
        }

        $smarty = $this->getView();
        $smarty->assign('templateeditor_absolute_filename',    htmlentities($tplfilename));
        
        # Prepare the Output
        $this->prepareOutput();
    }
    
    public function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Settings'), '/index.php?mod=templatemanager&amp;sub=admin&amp;action=settings');

        # Prepare the Output
        $this->prepareOutput();
    }
}
?>
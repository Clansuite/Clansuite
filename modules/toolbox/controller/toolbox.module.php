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
    * @version    SVN: $Id: about.module.php 4744 2010-09-26 23:13:04Z vain $
    */

//Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Toolbox
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Toolbox
 */
class Clansuite_Module_Toolbox extends Clansuite_Module_Controller
{

    public function initializeModule()
    {
        # read module config
        $this->getModuleConfig();
    }

    public function action_show()
    {
    }

    /**
     * ---------------------------------------------------
     * CssBuilder
     * ---------------------------------------------------
     */
    public function action_cssbuilder()
    {
        $this->setRenderMode('NOLAYOUT');
        $compile = $browsers = array();
        $htmlout = '';

        if(false === class_exists('Clansuite_Cssbuilder', false))
        {
            include_once ROOT_CORE . 'tools/cssbuilder.core.php';
        }
        $builder = new Clansuite_Cssbuilder();

        // -------------------------------------------------------------
        // hier können browser hinzugefügt werden
        // Der default (Mozilla) ist bereits vorhanden
        // -------------------------------------------------------------
        $builder::addBrowser( 'ie', 'Internet Explorer', true, 'ie' );
        #$builder::addBrowser( 'chrome', 'Google Chrome', true, 'ch' );
        #$builder::addBrowser( 'opera', 'Opera', true, 'op' );
        #$builder::addBrowser( 'safari', 'Safari', true, 'sf' );
        #$builder::addBrowser( 'camino', 'Camino', true, 'cam' );
        #$builder::addBrowser( 'konqueror', 'Konqueror', true, 'cam' );

        $browserArray = $builder::getBrowsers();

        if( isset($_POST['submit']) )
        {
            $compile['compileCore'] = (isset($_POST['compileCore'])) ? true : false;
            $compile['coreImport'] = (isset($_POST['coreImport'])) ? true : false;
            $compile['compileThemeFrontend'] = (isset($_POST['compileThemeFrontend'])) ? true : false;
            $compile['compileThemeBackend'] = (isset($_POST['compileThemeBackend'])) ? true : false;

            $compile['themeFrontendPath'] = $_POST['themeFrontendPath'];
            $compile['themeFrontend'] = $_POST['themeFrontend'];
            $compile['themeBackendPath'] = $_POST['themeBackendPath'];
            $compile['themeBackend'] = $_POST['themeBackend'];

            $formBrowsers = $_POST['browsers'];

            $browser = array();
            $i = 0;
            
            foreach( $formBrowsers as $key => $val)
            {
                if(isset($val['active']) and $val['active'] == 1)
                {
                    $browser[$i]['description'] = $val['description'];
                    $browser[$i]['postfix'] = $val['postfix'];
                    $i++;
                }
            }
            
            $compile['browsers'] = $browser;

            // Builder-Informationen übergeben
            $builder::setBuilderInfo($compile);

            if( ( true=== $compile['compileCore'] ) || 
               ( true=== $compile['compileThemeFrontend'] and $compile['themeFrontendPath'] != '' and $compile['themeFrontend'] != '' ) || 
               ( true=== $compile['compileThemeBackend'] and $compile['themeBackendPath'] != '' and $compile['themeBackend'] != '' )
            ) {

                // ------------------------------------------------------------
                // Compile
                // ------------------------------------------------------------
                $htmlout .= '<div class="cmSuccess">';
                
                $nr_browsers = count($browser);
                for($i=0; $i < $nr_browsers; $i++)
                {
                    $htmlout .= '<p class="cmBoxTitle" style="padding-left:50px;">';
                    $htmlout .= 'CSS-Builder Information <u>'.$browser[$i]['description'].'</u></p>';
                    $htmlout .= $builder->build($i);
                }
                unset($nr_browsers);
                
                $htmlout .= '</div>';
                $htmlout .= '<br />';
                $htmlout .= '</td></tr></table>';
            }
        }
        else
        {
            $compile['compileCore']             = false;
            $compile['coreImport']              = true;
            $compile['compileThemeFrontend']    = true;
            $compile['compileThemeBackend']     = false;
            $compile['themeFrontendPath']       = $builder::getFrontendPath();
            $compile['themeFrontend']           = $builder::getFrontendTheme();
            $compile['themeBackendPath']        = $builder::getBackendPath();
            $compile['themeBackend']            = $builder::getBackendTheme();
        }

        # Get Render Engine
        $view = $this->getView();
        $view->assign('browserinfo', $browserArray);
        $view->assign('compile', $compile);
        $view->assign('msgcompiled', $htmlout);

        $this->display();
    }

    public function widget_toolbox()
    {
    }

    public function widget_toolbar()
    {
    }
}
?>
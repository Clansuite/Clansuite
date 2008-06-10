<?php
/**
* settings
* This is the Admin Control Panel
*
* PHP >= version 5.1.4
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
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @author     Florian Wolf <xsign.dll@clansuite.com>
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @link       http://gna.org/projects/clansuite
*
* @author     Jens-André Koch, Florian Wolf
* @copyright  Clansuite Group
* @license    GPL v2
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite
 *
 * Module:       Admin Settings
 *
 * @package clansuite
 * @subpackage module_admin_settings
 * @category modules
 */
class module_admin_settings extends ModuleController implements Clansuite_Module_Interface
{
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }
        
    /**
     * action_settings_show
     */
    function action_settings_show()
    {   
        # Set Pagetitle and Breadcrumbs        
        trail::addStep( _('Show'), '/index.php?mod=admin&amp;sub=settings&amp;action=show');
        
        # Get Render Engine
        $smarty = $this->getView();
        
        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');
        
        # Assign Config to Smarty
        $smarty->view->assign('cfg', $config);
                
        # Set Admin Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
        # Specifiy the template manually
        $this->setTemplate('admin/settings/settings.tpl');
        
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * action_settings_update
     */
    function action_settings_update()
    {
        # Set Pagetitle and Breadcrumbs        
        trail::addStep( _('Update'), '/index.php?mod=admin&amp;sub=settings&amp;action=update');

        # Incomming Data
        # @todo get post via request object
        $data = $_POST['config'];
        
        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');
        
        $config->writeConfig( ROOT . 'clansuite.config.php',$data);
        
        /**
        * @desc Handle the update
        *//*
        $cfg_file = file_get_contents(ROOT . '/config.class.php');
        foreach($data as $key => $value)
        {
            if( is_array($value) )
            {
                foreach( $value as $meta_key => $meta_value )
                {
                    if( preg_match('#^[0-9]+$#', $meta_value) )
                	{
                	    $cfg_file = preg_replace( '#\$this->meta\[\''. $meta_key . '\'\][\s]*\=.*\;#', '$this->meta[\''. $meta_key . '\'] = ' . $meta_value . ';', $cfg_file );
                	}
                	else
                	{
                        $cfg_file = preg_replace( '#\$this->meta\[\''. $meta_key . '\'\][\s]*\=.*\;#', '$this->meta[\''. $meta_key . '\'] = \'' . $meta_value . '\';', $cfg_file );
                	}
                }
            }
            else
            {
                if( preg_match('#^[0-9]+$#', $value) )
                {
                    $cfg_file = preg_replace( '#\$this->'. $key . '[\s]*\=.*\;#', '$this->'. $key . ' = ' . $value . ';', $cfg_file );
                }
                else
                {
                    $cfg_file = preg_replace( '#\$this->'. $key . '[\s]*\=.*\;#', '$this->'. $key . ' = \'' . $value . '\';', $cfg_file );
                }
            }
        }

        file_put_contents( ROOT . '/config.class.php', $cfg_file );
        */
        # Redirect 
        
        #$functions->redirect( 'index.php?mod=admin&sub=settings', 'metatag|newsite', 3, $lang->t( 'The config file has been succesfully updated...' ), 'admin' );
    }
}
?>
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
    *
    * @version    SVN: $Id: $    
    */

  
//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}
  
       
/**
 * Clansuite Module - {$mod.module_name|capitalize} 
 *
 * Description: {$mod.meta.description}
 *
 * @version    {$mod.meta.initialversion}
 * @author     {$mod.meta.author} {$mod.meta.email}
 * @copyright  {$mod.meta.copyright}
 * @license    {$mod.meta.license} 
 * @link       {$mod.meta.website}
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  {$mod.module_name|capitalize}
 */

class Clansuite_Module_{$mod.module_name|capitalize} extends ModuleController implements Clansuite_Module_Interface
{
    
    /**
     * Module_{$mod.module_name|capitalize} -> Execute 
     *
     * Execute sets up common module specific stuff, needed by all actions of the module.
     * After execute is performed, the next step in the processing order is the requested action $_REQUEST['action'].
     */
     
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        
        # read module config
        $this->config->readConfig( ROOT_MOD . '{$mod.modulename}/{$mod.modulename}.config.php');

    
    }
    
{$frontend_methods|default}

{$widget_methods|default}


}

?>
<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch (c) 2005 - onwards
    *
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: $
    */

/**
 * {$mod.meta.title} Admin Module (Backend)
 * ({$mod.module_name})
 *
 * @license    {$mod.meta.license}
 * @author     {$mod.meta.author}
 * @link       {$mod.meta.homepage}
 */
{literal}
// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}
{/literal}
/**
 * @package Clansuite
 * @subpackage module_admin_{$mod.module_name}
 */
class Module_{$mod.module_name|capitalize}_Admin extends ModuleController implements Clansuite_Module_Interface{literal}
{{/literal}
    /**
     * Main Method of {$mod.module_name|capitalize} Module
     *
     * Sets up module specific stuff, needed by all actions of the module
     * Calls the requested Action $_REQUEST['action']
     */
    public function execute(httprequest $request, httpresponse $response){literal}
    {{/literal}  
        # read module config
        $this->config->readConfig( ROOT_MOD . '{$mod.module_name}/{$mod.module_name}.config.php');

        # proceed to the requested action
        $this->processActionController($request);{literal}
    }{/literal}     
{$backend_methods|default}
{literal}
}
{/literal}
?>

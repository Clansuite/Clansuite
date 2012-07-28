<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Clansuite\Module;

/**
 * Clansuite Administration Module - {$mod.module_name|capitalize}
 *
 * Description: {$mod.meta.description}
 *
 * @version     {$mod.meta.initialversion}
 * @author      {$mod.meta.author} {$mod.meta.email}
 * @copyright   {$mod.meta.copyright}
 * @license     {$mod.meta.license}
 * @link        {$mod.meta.website}
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  {$mod.module_name|capitalize}
 */
class {$mod.module_name|capitalize}_Admin extends ModuleController implements Clansuite_Module_Interface
{

    /**
     * Module_{$mod.module_name|capitalize}_Admin -> Execute
     *
     * Execute sets up common module specific stuff, needed by all actions of the module.
     * After execute is performed, the next step in the processing order is the requested action $_REQUEST['action'].
     */

    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {

        # read module config
        $this->config->readConfig( ROOT_MOD . '{$mod.modulename}/{$mod.modulename}.config.php');


    }

{$backend_methods|default}


}

?>

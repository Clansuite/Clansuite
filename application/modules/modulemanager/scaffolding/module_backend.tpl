<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * GNU/GPL v2 or any later version; see LICENSE file
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *

    */

namespace Clansuite\Module;

//Security Handler
if (!defined('IN_CS')){ die('Direct Access forbidden.' );}


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
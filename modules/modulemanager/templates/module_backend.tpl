<?php
/**
 * {$mod.meta.title} Admin Module (Backend)
 * ({$mod.module_name})
 *
 * @license    {$mod.meta.license}
 * @author     {$mod.meta.author}
 * @link       {$mod.meta.homepage}
 * @version    SVN: $Id: $
 */
{literal}
// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}
{/literal}
/**
 * An example class, this is grouped with
 * other classes in the "sample" package and
 * is part of "classes" subpackage
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
        $this->config->readConfig( ROOT_MOD . '/{$mod.module_name}/{$mod.module_name}.config.php');

        # proceed to the requested action
        $this->processActionController($request);{literal}
    }{/literal}     
{$backend_methods|default}
{literal}
}
{/literal}
?>

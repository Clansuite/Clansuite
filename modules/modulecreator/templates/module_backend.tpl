<?php
/**
 * {$m.module_name|capitalize} Admin (Bakcend) Module
*
 * @license    {$m.meta.license}
 * @author     {$m.meta.author}
 * @link       {$m.meta.homepage}
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
 * @subpackage {$m.module_name}
 */
class Module_{$m.module_name}_Admin extends ModuleController implements Clansuite_Module_Interface{literal}
{{/literal}
    /**
     * Main Method of {$m.module_name|capitalize} Module
     *
     * Sets up module specific stuff, needed by all actions of the module
     * Calls the requested Action $_REQUEST['action']
     */
    public function execute(httprequest $request, httpresponse $response){literal}
    {{/literal}  
        # read module config
        $this->config->readConfig( ROOT_MOD . '/{$m.module_name}/{$m.module_name}.config.php');

        # proceed to the requested action
        $this->processActionController($request);{literal}
    }{/literal}     
{$backend_methods}
{literal}
}
{/literal}
?>

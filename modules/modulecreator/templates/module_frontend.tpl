{literal}
<?php
/**
 * Index Module
*
 * @license    {m.}
 * @author     {m.}
 * @link       {m.}
 * @version    SVN: $Id: $
 */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * An example class, this is grouped with
 * other classes in the "sample" package and
 * is part of "classes" subpackage
 * @package Clansuite
 * @subpackage {m.}
 */
class Module_{m.} extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Main Method of {m.} Module
     *
     * Sets up module specific stuff, needed by all actions of the module
     * Calls the requested Action $_REQUEST['action']
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }   

    {m.methods}
}
?>
{/literal}
<?php
/**
 * ClanSuite Gallery Module (Frontend)
 * (gallery)
 *
 * @license    GPL
 * @author     Florian Wolf
 * @link       http://www.clansuite.com
 * @version    SVN: $Id: $
 */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * An example class, this is grouped with
 * other classes in the "sample" package and
 * is part of "classes" subpackage
 * @package Clansuite
 * @subpackage module_gallery
 */
class Module_Gallery extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Main Method of Gallery Module
     *
     * Sets up module specific stuff, needed by all actions of the module
     * Calls the requested Action $_REQUEST['action']
     */
    public function execute(httprequest $request, httpresponse $response)
    {  
        # read module config
        $this->config->readConfig( ROOT_MOD . '/gallery/gallery.config.php');

        # proceed to the requested action
        $this->processActionController($request);
    }     

    /**
     * The action_show method for the Gallery module
     * @param void
     * @return void 
     */
    public function action_show()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('show'), '/index.php?mod=gallery&amp;action=show');

        
        # Prepare the Output
        $this->prepareOutput();
    }


    /**
     * The widget_gallery method for the Gallery module (widget!)
     * @param void
     * @return void 
     */
    public function widget_gallery($item)
    {
        $this->renderWidget(__METHOD__);
    }


}

?>
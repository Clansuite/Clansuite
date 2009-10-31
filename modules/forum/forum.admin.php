<?php
/**
 * ClanSuite Forum Admin Module (Backend)
 * (forum)
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
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Forum
 */
class Module_Forum_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Main Method of Forum Module
     *
     * Sets up module specific stuff, needed by all actions of the module
     * Calls the requested Action $_REQUEST['action']
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {  
        # read module config
        $this->config->readConfig( ROOT_MOD . '/forum/forum.config.php');
    }     

    /**
     * The action_admin_show method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_show()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show'), '/index.php?mod=forum&amp;action=show');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_create_category method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_create_category()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Create Category'), '/index.php?mod=forum&amp;action=create_category');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_create_board method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_create_board()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Create Board'), '/index.php?mod=forum&amp;action=create_board');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_edit_category method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_edit_category()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Edit Category'), '/index.php?mod=forum&amp;action=edit_category');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_edit_board method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_edit_board()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Edit Board'), '/index.php?mod=forum&amp;action=edit_board');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_delete_category method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_delete_category()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Delete Category'), '/index.php?mod=forum&amp;action=delete_category');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_delete_board method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_delete_board()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Delete Board'), '/index.php?mod=forum&amp;action=delete_board');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

	public function action_admin_settings ()
	{
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Settings'), '/index.php?mod=forum&amp;sub=admin&amp;action=settings');
        
        $settings = array();
		
        $settings['form']   = array(    'name' => 'forum_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT.'/index.php?mod=forum&amp;sub=admin&amp;action=settings_update');
		
		$settings['forum'][] = array(    
										'id' => 'list_max',
                                        'name' => 'list_max',
                                        'description' => _('list_max'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('list_max', '30'));
										
		$settings['forum'][] = array(    
										'id' => 'char_max',
                                        'name' => 'char_max',
                                        'description' => _('Maximum Textcharacter'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('char_max', '999'));
										
		$settings['forum'][] = array(    
										'id' => 'allow_bb_code',
                                        'name' => 'allow_bb_code',
                                        'description' => _('Allow BBCode'),
                                        'formfieldtype' => 'selectyesno',
                                        'value' => array( 'selected' => $this->getConfigValue('allow_bb_code', '1')));
										
		$settings['forum'][] = array(    
										'id' => 'allow_html',
                                        'name' => 'allow_html',
                                        'description' => _('Allow HTML'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('allow_html', '0'));
										
		$settings['forum'][] = array(    
										'id' => 'allow_geshi_highlight',
                                        'name' => 'allow_geshi_highlight',
                                        'description' => _('Allow Geshi Highlighting'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('allow_geshi_highlight', '1'));
										
        require ROOT_CORE . '/viewhelper/formgenerator.core.php';
        $form = new Clansuite_Array_Formgenerator($settings);

        # display formgenerator object
        #clansuite_xdebug::printR($form); 
        
        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');
        
        # display form html
        #clansuite_xdebug::printR($form->render());
        
        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput(); 
	}
	
    function action_admin_settings_update()
    { 
        # Incomming Data
        # @todo get post via request object, sanitize
        $data = $this->getHttpRequest()->getParameter('forum_settings');

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');
        
        # write config
        $config->confighandler->writeConfig( ROOT_MOD . 'forum'.DS.'forum.config.php', $data);

        # clear the cache / compiled tpls
        # $this->getView()->clear_all_cache();
        $this->getView()->clear_compiled_tpl();

        # Redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=forum&amp;sub=admin', 2, 302, 'The config file has been succesfully updated.');
    }
}

?>
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

# Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Forum_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Forum
 */
class Clansuite_Module_Forum_Admin extends Clansuite_Module_Controller implements Clansuite_Module_Interface
{
    public function initializeModule(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        $this->getModuleConfig();
    }

    public function action_admin_show()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/index.php?mod=forum&amp;action=show');

        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        $this->prepareOutput();
    }

    public function action_admin_create_category()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Create Category'), '/index.php?mod=forum&amp;action=create_category');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        $this->prepareOutput();
    }

    public function action_admin_create_board()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Create Board'), '/index.php?mod=forum&amp;action=create_board');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        $this->prepareOutput();
    }

    public function action_admin_edit_category()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Edit Category'), '/index.php?mod=forum&amp;action=edit_category');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        $this->prepareOutput();
    }

    public function action_admin_edit_board()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Edit Board'), '/index.php?mod=forum&amp;action=edit_board');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        $this->prepareOutput();
    }

    public function action_admin_delete_category()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Delete Category'), '/index.php?mod=forum&amp;action=delete_category');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        $this->prepareOutput();
    }

    public function action_admin_delete_board()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Delete Board'), '/index.php?mod=forum&amp;action=delete_board');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        $this->prepareOutput();
    }

    public function action_admin_settings ()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), '/index.php?mod=forum&amp;sub=admin&amp;action=settings');

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

        include ROOT_CORE . '/viewhelper/formgenerator.core.php';
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

    public function action_admin_settings_update()
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
        $this->getView()->utility->clearCompiledTemplate();

        # Redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=forum&amp;sub=admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>
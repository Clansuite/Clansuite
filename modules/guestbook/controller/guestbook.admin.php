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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Guestbook_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Guestbook
 */
class Clansuite_Module_Guestbook_Admin extends Clansuite_Module_Controller
{
    public function initializeModule()
    {
        parent::initModel('guestbook');
    }

    public function action_admin_show()
    {
        # Incoming Variables
        $currentPage    = (int) $this->request->getParameter('page');
        $resultsPerPage = (int) $this->getConfigValue('resultsPerPage', '10');

        // SmartyColumnSort -- Easy sorting of html table columns.
        include ROOT_LIBRARIES . 'smarty/libs/SmartyColumnSort.class.php';
        // A list of database columns to use in the table.
        $columns = array( 'gb_id', 'gb_added', 'gb_nick', 'gb_email', 'gb_icq', 'gb_website', 'gb_town', 'gb_text', 'gb_admincomment', 'gb_ip');
        // Create the columnsort object
        $columnsort = new SmartyColumnSort($columns);
        // And set the the default sort column and order.
        $columnsort->setDefault('gb_added', 'desc');
        // Get sort order from columnsort
        $sortorder = $columnsort->sortOrder(); // Returns 'name ASC' as default

        # fetch entries
        $guestbookQuery = Doctrine::getTable('CsGuestbook')->fetchAllGuestbookEntries($currentPage, $resultsPerPage, true, $sortorder);

        # import array variables into the current symbol table ($newsQuery is an array('news','pager','pager_layout')
        extract($guestbookQuery);
        unset($guestbookQuery);

        # fetch the BBCode formatter object
        $bbcode = new Clansuite_Bbcode($this->getInjector());

        # Transform RAW text from DB to BB-formatted Text
        foreach( $guestbook_entries as $key => $value )
        {
            $guestbook_entries[$key]['gb_text']     = $bbcode->parse($value['gb_text']);
            $guestbook_entries[$key]['gb_comment']  = $bbcode->parse($value['gb_comment']);
        }

        # get Number of Rows
        $count = count($guestbook_entries);

        # Get view and assign placeholders
        $view = $this->getView();
        $view->assign('guestbook_counter', $count);
        $view->assign('guestbook', $guestbook_entries);
        $view->assign('pager', $pager);
        $view->assign('pager_layout', $pager_layout);

        $this->display();
    }

    public function action_admin_testformgenerator()
    {
        # Create a new form
        $form = new Clansuite_Form('news_create_form', 'POST', 'upload-file.php');
        $form->setId('news_create_form')
             ->setTarget('hidden_upload')
             ->setHeading('News Create Form')
             ->setEncoding('multipart/form-data')
             ->setDescription('My news create form...');

        # Assign some Formlements
        /*$form->addElement('captcha')->setLabel('captcha label');

        $form->addElement('checkbox')->setLabel('checkbox label');
        $form->addElement('checkboxlist')->setLabel('checkboxlist label');
        $form->addElement('confirmsubmitbutton')->setLabel('confirmsubmitbutton label');
        */

        # you can specify several uploadTypes: uploadify, apc, ajaxupload
        # or no uploadType at all (for default upload)
        #$form->addElement('file')->setUploadType('uploadify')->setLabel('file upload label');
/*
        #form->addElement('jqconfirmsubmitbutton')->setFormId('news_create_form')->setLabel('jqconfirmsubmitbutton label');

        $form->addElement('jqselectdate')->setLabel('jqselectdate label'); #->setFormId('news_create_form')

        $form->addElement('hidden')->setLabel('hidden label');

        $form->addElement('radio')->setLabel('radio label');
        $form->addElement('radiolist')->setLabel('radiolist label');

        $form->addElement('selectcountry');
        $form->addElement('selectyesno');
*/
        $form->setDecorator('fieldset')->setLegend('Testform');

        $form->addElement('text')->setLabel('text label')->setDescription('description');
        #$form->setElementDecorator('label');
        $form->setElementDecorator('div')->setClass('Forminside');
        $form->setElementDecorator('div')->setClass('Formline');
        #$form->setElementDecorator('description');
        #Clansuite_Debug::printR($form->getFormelements());

        $form->addElement('textarea')->setCols('70')->setLabel('textarea label');

        #$form->addElement('submitbutton')->setValue('Submit')->setLabel('Submit Button')->setClass('ButtonGreen');
        #$form->addElement('resetbutton')->setValue('Reset')->setLabel('Reset Button');
/*
        $form->addElement('imagebutton')->setValue('Reset')->setLabel('Image Button'); # setSource
*/
        # Debugging Form Object
        #Clansuite_Debug::printR($form);

        # Debugging Form HTML Output
        #Clansuite_Debug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    public function save_comment()
    {
        $gb_id      = $this->request->getParameterFromGet('gb_id');
        $comment    = $this->request->getParameterFromPost('value');

        # Add/Modify comment
        Doctrine_Query::create()
                      ->update('CsComments')
                      ->set('gb_comment', $comment)
                      ->whereIn('gb_id = ?', $gb_id);

        # Transform RAW text to BB-formatted Text
        Clansuite_Loader::loadCoreClass('bbcode');
        $bbcode = new bbcode();
        $parsed_comment = $bbcode->parse($comment);

        #$this->suppress_wrapper = 1;
    }

    public function get_comment()
    {
        $gb_id = $this->request->getParameterFromGet('gb_id');

        $result = Doctrine_Query::create()
                                ->select('gb_comment')
                                ->from('CsGuestbook')
                                ->where('gb_id', $gb_id)->fetchArray();

        #Helptext in Raw from Database
        $result['gb_comment'];

        #$this->suppress_wrapper = true;
    }

    public function action_admin_edit()
    {
        // Permissions check
        if( $perms->check('cc_edit_gb', 'no_redirect') == true )
        {

            // Incoming Vars
            $infos  = $_POST['infos'];
            $submit = isset($_POST['submit']) ? $_POST['submit'] : '';
            $gb_id  = isset($_GET['id']) ? $_GET['id'] : 0;
            $front  = isset($_GET['front']) ? $_GET['front'] : 0;

            if( !empty( $submit ) )
            {
                // Add/Modify comment
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'guestbook
                                       SET  `gb_icq` = :gb_icq,
                                            `gb_nick` = :gb_nick,
                                            `gb_email` = :gb_email,
                                            `gb_website` = :gb_website,
                                            `gb_town` = :gb_town,
                                            `gb_text` = :gb_text,
                                            `gb_ip` = :gb_ip,
                                            `gb_comment` = :gb_comment
                                       WHERE `gb_id` = :gb_id' );
                $stmt->execute( $infos );

                if( $infos['front'] == 1 )
                {
                    // Redirect on finish
                    $functions->redirect( 'index.php?mod=guestbook&action=show', 'metatag|newsite', 3, $lang->t( 'The guestbook entry has been edited.' ) );
                }
                else
                {
                    // Redirect on finish
                    $functions->redirect( 'index.php?mod=guestbook/admin/show', 'metatag|newsite', 3, $lang->t( 'The guestbook entry has been edited.' ), 'admin' );
                }

            }

            $result = Doctrine_Query::create()
                                ->select('gb_comment')
                                ->from('CsGuestbook')
                                ->where('gb_id', $gb_id)->fetchArray();

            $this->getView()->assign('infos', $result);
            $this->getView()->assign('front', $front);
            # $tpl->fetch('guestbook/admin_edit.tpl');
        }
        else
        {
            $this->flashmessage('error', _('You do not have sufficient rights.'));
        }

        $this->display();
    }

    public function action_admin_delete()
    {
        $submit     = $this->request->getParameterFromPOST('submit');
        $confirm    = $this->request->getParameterFromPOST('confirm');
        $abort      = $this->request->getParameterFromPOST('abort');
        $ids        = $this->request->getParameterFromPOST('ids', array());
        #$ids        = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['ids'])) : $ids;
        $delete     = $this->request->getParameterFromPOST('delete', array());
        #$delete     = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['delete'])) : $delete;
     }

    public function admin_action_create()
    {
        // Incoming Vars
        $infos  = $_POST['infos'];
        $submit = isset($_POST['submit']) ? $_POST['submit'] : '';
        $gb_id  = isset($_GET['id']) ? $_GET['id'] : 0;
        $front  = isset($_GET['front']) ? $_GET['front'] : 0;

        if( !empty( $submit ) )
        {
            // Set user stuff
            $infos['gb_ip'] = $_SESSION['client_ip'];
            $infos['gb_added'] = time();
            $infos['user_id'] = $_SESSION['user']['user_id'];

            # Get an image, if existing
            if( $infos['user_id'] != 0 )
            {
                $result = Doctrine_Query::create()
                                        ->select('image_id')
                                        ->from('CsProfiles')
                                        ->whereIn('user_id', $infos['user_id']);

                $infos['image_id'] = $result['image_id'];
            }
            else
            {
                $infos['image_id'] = 0;
            }


            $guestbook = new CsGuestbook();
            $guestbook->gb_added    = $infos['gb_added'];
            $guestbook->gb_icq      = $infos['gb_icq'];
            $guestbook->gb_nick     = $infos['gb_nick'];
            $guestbook->gb_email    = $infos['gb_email'];
            $guestbook->gb_website  = $infos['gb_website'];
            $guestbook->gb_town     = $infos['gb_town'];
            $guestbook->gb_text     = $infos['gb_text'];
            $guestbook->gb_ip       = $infos['gb_ip'];
            $guestbook->user_id     = $infos['user_id'];
            $guestbook->image_id    = $infos['image_id'];
            $guestbook->save();

            $this->flashmessage('success', _( 'Guestbook entry created.'));

            $this->redirectToReferer();
        }

        $result = Doctrine_Query::create()
                                ->from('CsGuestbook')
                                ->whereIn('gb_id', $gb_id)
                                ->fetchArray($params);

        $view->assign( 'infos', $result);
        $view->assign( 'front', $front);
        $view->fetch('guestbook/create.tpl');

        $this->display();
    }

    public function action_admin_show_single()
    {
        $id = $this->request->getParameterFromGet('id');

        if( $perms->check('cc_view_gb', 'no_redirect') == true )
        {
            $result = Doctrine_Query::create()
                                    ->select('CsGuestbook')
                                    ->where('gb_id', $gb_id)
                                    ->fetchArray();

            $this->getView()->assign('infos', $result);
            #$tpl->fetch('guestbook/admin_edit.tpl');
        }
        else
        {
            $lang->t('You are not allowed to view single news.');
        }

        $this->display();
    }

    /**
     * Action for displaying the Settings
     */
    public function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), '/guestbook/admin/settings');

        $settings = array();

        $settings['form']   = array(    'name' => 'guestbook_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT . 'index.php?mod=guestbook/admin/settings_update');

        $settings['guestbook'][] = array(
                                        'id' => 'guestbook_resultsPerPage',
                                        'name' => 'guestbook_resultsPerPage',
                                        'description' => _('Guestbook Items'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('guestbook_resultsPerPage', '12'));

        $form = new Clansuite_Form($settings);

        # display formgenerator object
        #Clansuite_Debug::printR($form);

        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');

        # display form html
        #Clansuite_Debug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    public function action_admin_settings_update()
    {
        # Incomming Data
        # @todo get post via request object, sanitize
        $data = $this->request->getParameter('guestbook_settings');

        # Get Configuration from Injector and write Config
        $this->getInjector()->instantiate('Clansuite_Config')->writeModuleConfig($data);

        # clear the cache / compiled tpls
        $this->getView()->clearCache();

        # Redirect
        $this->response->redirectNoCache('/guestbook/admin', 2, 302, _('The config file has been succesfully updated.'));
    }
}
?>
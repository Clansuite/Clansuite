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
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * This is the Clansuite Module Class - Guestbook Admin
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @author     Florian Wolf    <xsign.dll@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Guestbook
 */
class Module_Guestbook_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    public function __construct(Phemto $injector=null)
    {
        parent::__construct(); # run constructor on controller_base
    }

    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        parent::initRecords('guestbook');
    }

    /**
     * Show all guestbook entries and give the possibility to edit/delete
     */
    function action_admin_show()
    {
        # Incoming Variables
        $currentPage    = (int) $this->injector->instantiate('Clansuite_HttpRequest')->getParameter('page');
        $resultsPerPage = (int) $this->getConfigValue('resultsPerPage_adminshow', '10');

        // SmartyColumnSort -- Easy sorting of html table columns.
        require( ROOT_LIBRARIES . '/smarty/SmartyColumnSort.class.php');
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
        Clansuite_Loader::loadCoreClass('bbcode');
        $bbcode = new Clansuite_Bbcode($this->injector);

        # Transform RAW text from DB to BB-formatted Text
        foreach( $guestbook_entries as $key => $value )
        {
            $guestbook_entries[$key]['gb_text']     = $bbcode->parse($value['gb_text']);
            $guestbook_entries[$key]['gb_comment']  = $bbcode->parse($value['gb_comment']);
        }

        # get Number of Rows
        $count = count($guestbook_entries);

        # Get view and assign placeholders
        $smarty = $this->getView();
        $smarty->assign('guestbook_counter', $count);
        $smarty->assign('guestbook', $guestbook_entries);
        $smarty->assign('pager', $pager);
        $smarty->assign('pager_layout', $pager_layout);

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Debugging Action
     * testformgenerator
     */
    function action_admin_testformgenerator()
    {
        # Load Form Class (@todo autoloader / di)
        require ROOT_CORE . 'viewhelper/form.core.php';
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
        $form->addElement('text')->setLabel('text label');
        $form->addElement('textarea')->setCols('70')->setLabel('textarea label');

        #$form->addElement('submitbutton')->setValue('Submit')->setLabel('Submit Button')->setClass('ButtonGreen');
        #$form->addElement('resetbutton')->setValue('Reset')->setLabel('Reset Button');
/*
        $form->addElement('imagebutton')->setValue('Reset')->setLabel('Image Button'); # setSource
*/
        # Debugging Form Object
        #clansuite_xdebug::printR($form);

        # Debugging Form HTML Output
        #clansuite_xdebug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput();
    }

    /**
    * AJAX request to save the comment
    * 1. save comment in raw with bbcodes on - into database
    * 2. return comment with formatted bbcode = raw to html-style
    *
    * @global $db
    * @global $tpl
    */
    function save_comment()
    {
        global $db, $tpl;
        /**
        * @desc Incoming Vars
        */
        $gb_id      = urldecode($_GET['id']);
        $comment    = urldecode($_POST['value']);

        /**
        * @desc Get comment from DB
        */
        $stmt = $db->prepare( 'SELECT gb_comment FROM ' . DB_PREFIX . 'guestbook
                               WHERE `gb_id` = ?' );
        $stmt->execute( array( $gb_id ) );
        $result = $stmt->fetch(PDO::FETCH_NAMED);

        // Add/Modify comment
        $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'guestbook
                               SET `gb_comment` = ? WHERE `gb_id` = ?' );
        $stmt->execute( array( $comment, $gb_id ) );

        // Transform RAW text to BB-formatted Text
        Clansuite_Loader::loadCoreClass('bbcode');
        $bbcode = new bbcode();
        $parsed_comment = $bbcode->parse($comment);

         $parsed_comment;
        $this->suppress_wrapper = 1;
    }

    /**
    * AJAX request to get the helptext in raw from database
    *
    * @global $db
    */
    function get_comment()
    {
        global $db;

        /**
        * @desc Incoming Vars
        */
        $gb_id = $_GET['id'];

        /**
        * @desc Get comment from DB
        */
        $stmt = $db->prepare( 'SELECT gb_comment FROM ' . DB_PREFIX . 'guestbook
                               WHERE `gb_id` = ?' );
        $stmt->execute( array( $gb_id ) );
        $result = $stmt->fetch(PDO::FETCH_NAMED);

        // Helptext in Raw from Database
         $result['gb_comment'];
        $this->suppress_wrapper = true;
    }

    /**
    * AJAX request to save the comment
    * 1. save comment in raw with bbcodes on - into database
    * 2. return comment with formatted bbcode = raw to html-style
    *
    * @global $db
    * @global $tpl
    * @global $functions
    * @global $lang
    * @global $perms
    */
    function edit()
    {
        global $db, $tpl, $functions, $lang, $perms;

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
                    $functions->redirect( 'index.php?mod=guestbook&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'The guestbook entry has been edited.' ), 'admin' );
                }

            }

            $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX . 'guestbook WHERE gb_id = ?');
            $stmt->execute( array( $gb_id ) );
            $result = $stmt->fetch( PDO::FETCH_NAMED );

            $tpl->assign( 'infos', $result);
            $tpl->assign( 'front', $front);
            $tpl->fetch('guestbook/admin_edit.tpl');
        }
        else
        {
            $lang->t('You do not have sufficient rights.') . '<br /><input class="ButtonRed" type="button" onclick="Dialog.okCallback()" value="Abort"/>';
        }
        $this->suppress_wrapper = 1;
    }

    function delete()
    {
        global $db, $functions, $input, $lang;

        // Init
        $submit     = $_POST['submit'];
        $confirm    = $_POST['confirm'];
        $abort      = $_POST['abort'];
        $ids        = isset($_POST['ids'])      ? $_POST['ids'] : array();
        $ids        = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['ids'])) : $ids;
        $delete     = isset($_POST['delete'])   ? $_POST['delete'] : array();
        $delete     = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['delete'])) : $delete;

        echo 'to delete :' . count($delete);
        var_dump($delete);

        echo 'ids:';
        var_dump($ids);

        // Check, if there is a delete request
        if ( count($delete) < 1 )
        {
            $functions->redirect( 'index.php?mod=guestbook&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'Aborted ! So there were no guestbook entries selected to delete!  ' ), 'admin' );
        }

        // Abort...
        if ( isset( $_POST['abort'] ) )
        {
            $functions->redirect( 'index.php?mod=guestbook&sub=admin&action=show' );
        }

        // Create the select to fetch (the Entries to delete) from DB
        // to have more infos to ask and decide on deletion
        $select = 'SELECT gb_id, gb_nick, gb_text FROM ' . DB_PREFIX . 'guestbook WHERE ';
        foreach ( $delete as $key => $id )
        {
            $select .= 'gb_id = ' . $id . ' OR ';
        }
        // code by xsign
        // @todo explain reason for settings this: [OR user_id = -1000]
        $select .= 'gb_id = -1000';

        // prepare and execute the constructed select
        $stmt = $db->prepare( $select );
        $stmt->execute();
        while( $result = $stmt->fetch(PDO::FETCH_ASSOC) )
        {
            if( in_array( $result['gb_id'], $delete  ) )
            {
                $names = '<br /># ' . $result['gb_id'] . ' by ' . $result['gb_nick'] . ' <b>' .  $result['gb_text'] . '</b>';
            }
            $all_gb_entries_to_delete[] = $result;
        }

        // Delete Groups
        foreach( $all_gb_entries_to_delete as $key => $value )
        {
            if ( count ( $delete ) > 0 )
            {
                if ( in_array( $value['gb_id'], $ids ) )
                {
                    $d = in_array( $value['gb_id'], $delete  ) ? 1 : 0;
                    if ( !isset ( $_POST['confirm'] ) )
                    {
                        $functions->redirect( 'index.php?mod=guestbook&sub=admin&action=delete&ids=' . urlencode(serialize($ids)) . '&delete=' . urlencode(serialize($delete)), 'confirm', 3, $lang->t( 'You have selected the following guestbook entry(ies) to delete: ' . $names ), 'admin' );
                    }
                    else
                    {
                        if ( $d == 1 )
                        {
                            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'guestbook WHERE gb_id = ?' );
                            $stmt->execute( array($value['gb_id']) );
                        }
                    }
                }
            }
        }

        // Redirect to main
        $functions->redirect( 'index.php?mod=guestbook&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'The selected guestbook entr(y/ies) were deleted.' ), 'admin' );
    }

    /**
    * Show a single news
    *
    * @global $db
    * @global $lang
    * @global $functions
    * @global $input
    * @global $tpl
    * @global $cfg
    * @global $perms
    */
    function show_single()
    {
        global $db, $functions, $input, $lang, $tpl, $cfg, $perms;

        // Incoming vars
        $gb_id = $_GET['id'];

        if( $perms->check('cc_view_gb', 'no_redirect') == true )
        {
            $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX . 'guestbook WHERE gb_id = ?');
            $stmt->execute( array( $gb_id ) );
            $result = $stmt->fetch( PDO::FETCH_NAMED );

            $tpl->assign( 'infos', $result);
            $tpl->fetch('guestbook/admin_edit.tpl');
        }
        else
        {
            $lang->t('You are not allowed to view single news.');
        }
        $this->suppress_wrapper = 1;
    }

    /**
     * Action for displaying the Settings of a Module Guestbook
     */
    function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Settings'), '/index.php?mod=guestbook&amp;sub=admin&amp;action=settings');

        $settings = array();

        $settings['form']   = array(    'name' => 'guestbook_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT.'/index.php?mod=guestbook&amp;sub=admin&amp;action=settings_update');

        $settings['guestbook'][] = array(
										'id' => 'guestbook_resultsPerPage',
                                        'name' => 'guestbook_resultsPerPage',
                                        'description' => _('Guestbook Items'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('guestbook_resultsPerPage', '12'));

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
        $data = $this->getHttpRequest()->getParameter('guestbook_settings');

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');

        # write config
        $config->confighandler->writeConfig( ROOT_MOD . 'guestbook/guestbook.config.php', $data);

        # clear the cache / compiled tpls
        # $this->getView()->clear_all_cache();
        $this->getView()->clear_compiled_tpl();

        # Redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=guestbook&amp;sub=admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
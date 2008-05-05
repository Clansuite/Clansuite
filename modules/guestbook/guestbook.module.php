<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
    * http://www.clansuite.com/
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005 - onwards), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * This is the Clansuite Module Class - Guestbook
 *
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005 - onwards)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    module
 * @subpackage  guestbook
 */
class module_guestbook extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Guestbook -> Execute
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }

    /**
    * General Function Hook of guestbook-Modul
    *
    * 1. Set Pagetitle and Breadcrumbs
    * 2. $_REQUEST['action'] determines the switch
    * 3. function title is added to page title, to complete the title
    * 4. switch-functions are called
    *
    * @global $lang
    * @global $trail
    * @return: array ( OUTPUT, ADDITIONAL_HEAD, SUPPRESS_WRAPPER )
    *
    */

    function auto_run()
    {
        switch ($_REQUEST['action'])
        {
            case 'show_avatar':
                $this->show_avatar();
                break;

            case 'create':
                $trail->addStep($lang->t('Add'), '/index.php?mod=guestbook&amp;action=create');
                $this->create();
                break;

            case 'instant_show':
                $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
                break;

        }
    }

    /**
     * Function: Show Guestbook
     * @todo: change setLimit to a Variable for editing by user from (Guestbook Module Settings)
     */
    function action_show()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=guestbook&amp;action=show');

        # Defining initial variables
        # Pager Chapter in Doctrine Manual  -> http://www.phpdoctrine.org/documentation/manual/0_10?one-page#utilities
        $currentPage = $this->injector->instantiate('httprequest')->getParameter('page');
        $resultsPerPage = 3;

        # Load DBAL
        $this->injector->instantiate('clansuite_doctrine')->doctrine_initialize();

        # Load Models (manually)
        /*
        require ROOT . '/myrecords/generated/BaseCsGuestbook.php';
        require ROOT . '/myrecords/CsGuestbook.php';
        require ROOT . '/myrecords/generated/BaseCsUsers.php';
        require ROOT . '/myrecords/CsUsers.php';
        */

        # Load Models (automatic + lazy loading)
        #Doctrine::loadModels(ROOT . '/myrecords/', Doctrine::MODEL_LOADING_CONSERVATIVE);

        # Debug Listing of all loaded Doctrine Models
        #$models = Doctrine::getLoadedModels();
        #print_r($models);

        // get all guestbook entries
        #$stmt = $db->prepare( ' LEFT JOIN ' . DB_PREFIX . 'images i ON i.image_id = g.image_id
        #                       LEFT JOIN ' . DB_PREFIX . 'users u ON g.user_id = u.user_id ORDER BY g.gb_added DESC' );

        // Creating Pager Object with a Query Object inside
        $pager_layout = new Doctrine_Pager_Layout(
                        new Doctrine_Pager(
                            Doctrine_Query::create()
                                    ->select('i.*,g.*,u.nick')
                                    ->from('CsGuestbook g')
                                    ->leftJoin('g.CsImages i')
                                    ->leftJoin('g.CsUsers u')
                                    #->where('c.module_id = 7')
                                    #->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                    ->orderby('g.gb_added DESC'),
                                 # The following is Limit  ?,? =
                                 $currentPage, // Current page of request
                                 $resultsPerPage // (Optional) Number of results per page Default is 25
                             ),
                             new Doctrine_Pager_Range_Sliding(array(
                                 'chunk' => 5
                             )),
                             '?mod=guestbook&action=show&page={%page}'
                             );
        // Assigning templates for page links creation
        $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');
        $pager_layout->setSelectedTemplate('[{%page}]');

        // Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        // Fetching guestbook entries
        #var_dump($pager->getExecuted());
        $guestbook = $pager->execute(array(), Doctrine::FETCH_ARRAY);

        // if array contains data proceed, else show empty message
        if ( !is_array( $guestbook ) )
        {
            $error['gb_empty'] = '1';
        }
        else
        {
            // total number of guestbook entries by counting the array
            $number_of_guestbook_entries = count($guestbook);

            // Finally: assign total number of rows to SmartyPaginate
            #$SmartyPaginate->setTotal($number_of_guestbook_entries);
            // assign the {$paginate} to $tpl (smarty var)
            #$SmartyPaginate->assign($tpl);

            // Get the BB-Code Class
            require_once( ROOT_CORE . '/bbcode.class.php' );
            $bbcode = new bbcode($this->injector);

            // Set 'not specified's
            foreach( $guestbook as $entry_key => $entry_value )
            {
                foreach( $entry_value as $key => $value )
                {
                    switch( $key )
                    {
                        case 'gb_comment':
                            if( empty($value) )
                                unset($guestbook[$entry_key][$key]);
                            else
                                $guestbook[$entry_key][$key] = $bbcode->parse($guestbook[$entry_key][$key]);
                            break;

                        case 'gb_text':
                            $guestbook[$entry_key][$key] = $bbcode->parse($guestbook[$entry_key][$key]);
                            break;

                        default:
                            $guestbook[$entry_key][$key] = empty($value) ? '<span class="not_specified">' . _('not specified') . '</span>' : $value;
                            break;
                    }
                }
            }
        }

        # Get Render Engine
        $smarty = $this->getView();
        // Assign $guestbook array to Smarty for template output
        $smarty->assign( 'guestbook', $guestbook);
        // if error was set, assign it to smarty
        if(isset($error)){$smarty->assign( 'error' , $error );}

        // Pagination
        $smarty->assign_by_ref('pager', $pager);
        $smarty->assign_by_ref('pager_layout', $pager_layout);

        # Prepare the Output
        $this->prepareOutput();
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
    function create()
    {
        global $db, $tpl, $functions, $lang, $perms;

        // Permissions check
        if( $perms->check('create_gb_entries', 'no_redirect') == true )
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

                // Get an image, if existing
                if( $infos['user_id'] != 0 )
                {
                    $stmt = $db->prepare('SELECT image_id FROM ' . DB_PREFIX . 'profiles_general WHERE user_id = ?');
                    $stmt->execute( array($infos['user_id']) );
                    $result = $stmt->fetch(PDO::FETCH_NAMED);
                    $infos['image_id'] = $result['image_id'];
                }
                else
                {
                    $infos['image_id'] = 0;
                }

                // Add gb entry
                $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'guestbook
                                       SET  `gb_added` = :gb_added,
                                            `gb_icq` = :gb_icq,
                                            `gb_nick` = :gb_nick,
                                            `gb_email` = :gb_email,
                                            `gb_website` = :gb_website,
                                            `gb_town` = :gb_town,
                                            `gb_text` = :gb_text,
                                            `gb_ip` = :gb_ip,
                                            `user_id` = :user_id,
                                            `image_id` = :image_id' );
                $stmt->execute( $infos );

                if( $infos['front'] == 1 )
                {
                    // Redirect on finish
                    $functions->redirect( 'index.php?mod=guestbook&action=show', 'metatag|newsite', 3, $lang->t( 'The guestbook entry has been created.' ) );
                }
                else
                {
                    // Redirect on finish
                    $functions->redirect( 'index.php?mod=guestbook&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'The guestbook entry has been created.' ), 'admin' );
                }

            }

            $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX . 'guestbook WHERE gb_id = ?');
            $stmt->execute( array( $gb_id ) );
            $result = $stmt->fetch( PDO::FETCH_NAMED );

            $tpl->assign( 'infos', $result);
            $tpl->assign( 'front', $front);
            $this->output = $tpl->fetch('guestbook/create.tpl');
        }
        else
        {
            $this->output = $lang->t('You do not have sufficient rights.') . '<br /><input class="ButtonRed" type="button" onclick="Dialog.okCallback()" value="Abort"/>';
        }
        $this->suppress_wrapper = 1;
    }

    /**
    * Send a img header
    *
    * @global $db
    */
    function show_avatar()
    {
        global $db;

        // Incoming vars
        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        if( $id != 0 )
        {
            $stmt = $db->prepare( 'SELECT i.*,g.gb_id FROM ' . DB_PREFIX . 'guestbook g LEFT JOIN ' . DB_PREFIX . 'images i ON i.image_id = g.image_id WHERE g.gb_id = ?' );
            $stmt->execute( array( $id ) );
            $result = $stmt->fetch(PDO::FETCH_NAMED);

            require( ROOT_CORE . '/image.class.php' );
            $img = new image( ROOT_UPLOAD . '/' . $result['location'] );
            $img->resize( 150, 100 );
            $img->show();
        }
    }

    /**
    * @desc Function: instant_show
    *
    * This content can be instantly displayed by adding this into a template:
    * {mod name="guestbook" func="instant_show" params="mytext"}
    *
    * You have to add the lines as shown above into the case block:
    * $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
    */
    function instant_show($my_text)
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        /**
        * @desc Handle the output - $lang-t() translates the text.
        */
        $this->output .= $lang->t($my_text);
    }
}
?>
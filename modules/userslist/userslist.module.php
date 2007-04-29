<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf � 2005-2007
    * http://www.clansuite.com/
    *
    * File:         userslist.module.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Module Class - userslist
    *               userslist
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
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

/**
 *  Security Handler
 */
if (!defined('IN_CS')) { die('You are not allowed to view this page.'); }

/**
 * This is the Clansuite Module Class - module_userslist
 *
 * Description:  userslist
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$)
 * @link       
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    module
 * @subpackage  module_userslist
 */
class module_userslist
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
     * @desc General Function Hook of userslist-Modul
     *
     * 1. Set Pagetitle and Breadcrumbs
     * 2. $_REQUEST['action'] determines the switch
     * 3. function title is added to page title, to complete the title
     * 4. switch-functions are called
     *
     * @return: array ( OUTPUT, ADDITIONAL_HEAD, SUPPRESS_WRAPPER )
     */

    function auto_run()
    {

        global $lang, $trail, $perms;
        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Userslist'), 'index.php?mod=userslist');

        //
        switch ($_REQUEST['action'])
        {

            default:
            case 'show':
                $trail->addStep($lang->t('Show'), 'index.php?mod=userslist&amp;action=show');
                $this->show();
                break;

            case 'instant_show':
                $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
                break;

        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
     * Action -> Show
     * Direct Call by URL/index.php?mod=userslist&action=show
     *
     * @global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;
     */

    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;

        // Smarty Pagination load and init
        require(ROOT_CORE . '/smarty/SmartyPaginate.class.php');

        // set URL
        $SmartyPaginate->setUrl('index.php?mod=userlist');
        $SmartyPaginate->setUrlVar('page');
        // set items per page
        $SmartyPaginate->setLimit(25);
        
        $stmt = $db->prepare('SELECT nick, email, user_id
                                FROM '. DB_PREFIX .'users 
                                ORDER BY user_id ASC LIMIT ?,?');

        /* TODO: news with status: draft, published, private, private+protected*/
        $stmt->bindParam(1, $SmartyPaginate->getCurrentIndex(), PDO::PARAM_INT );
        $stmt->bindParam(2, $SmartyPaginate->getLimit(), PDO::PARAM_INT );
        $stmt->execute();
        $userslist = $stmt->fetchAll(PDO::FETCH_NAMED);
 
        // Get Number of Rows
        $rows = $db->prepare('SELECT COUNT(*) FROM '. DB_PREFIX .'users');
        $rows->execute();
        $count = count($rows->fetch(PDO::FETCH_NUM));
        // DEBUG - show total numbers of last Select
        // echo 'Found Rows: ' . $count;

        // Finally: assign total number of rows to SmartyPaginate
        $SmartyPaginate->setTotal($count);
        // assign the {$paginate} to $tpl (smarty var)
        $SmartyPaginate->assign($tpl);

         // give $newslist array to Smarty for template output
        $tpl->assign('userslist', $userslist);
        
        $this->output = $tpl->fetch('userslist/show.tpl');
    }

    /**
     * Instant Show
     *
     * Content of a module can be instantly displayed by adding the
     * {mod name="userslist" sub="admin" func="instant_show" params="mytext"}
     * block into a template.
     *
     * You have to add the lines as shown above into the case block:
     * $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
     *
     * @global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users
    */
    
    function instant_show($my_text)
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;

        // Add $lang-t() translated text to the output.
        $this->output .= $lang->t($my_text);
    }
}
?>
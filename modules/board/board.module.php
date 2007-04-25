<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         board.module.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Module Class - board
    *               The Clansuite Board
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
 * This is the Clansuite Module Class - module_board
 *
 * Description:  The Clansuite Board
 *
 * @author     Jens-Andre Koch  <vain@clansuite.com>
 * @copyright  Jens-Andre Koch  (2005-$LastChangedDate$)
 * @link       http://www.clansuite.com
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    module
 * @subpackage  module_board
 */
class module_board
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc General Function Hook of board-Modul
    *
    * 1. Set Pagetitle and Breadcrumbs
    * 2. $_REQUEST['action'] determines the switch
    * 3. function title is added to page title, to complete the title
    * 4. switch-functions are called
    *
    * @return: array ( OUTPUT, ADDITIONAL_HEAD, SUPPRESS_WRAPPER )
    *
    */

    function auto_run()
    {

        global $lang, $trail, $perms;
        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Board'), '/index.php?mod=board');

        //
        switch ($_REQUEST['action'])
        {

            default:
            case 'show':
                $trail->addStep($lang->t('Overview'), '/index.php?mod=board&amp;action=show');
                $this->overview();
                break;

            
        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }


    /**
    * Action -> Show
    * Direct Call by URL/index.php?mod=board&action=show
    *
    * @global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;
    */

    function overview()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;

        /**
         * Fetch all Forums
         */
        $stmt = $db->prepare('SELECT * FROM '. DB_PREFIX .'board_forums WHERE type = ? AND status = ? ORDER BY displayorder');
        $stmt->execute( array ( 'forum', 'on') );
        $forums = $stmt->fetchAll(PDO::FETCH_NAMED);
        // @todo : lookup subforum for forum id only name of board + id
        
        // give $forums array to Smarty for template output
        $tpl->assign('forums', $forums);
        
        ##############################################
        
        /** 
         * Fetch all Subforums of Forum $forumid
         * dbfield type -> forum | sub
         * dbfield status -> on | off 
         */
        $forumparentid = 1;
        $stmt = $db->prepare('SELECT * FROM '. DB_PREFIX .'board_forums WHERE type = ? AND forumparent = ? AND status = ? ORDER BY displayorder');
        $stmt->execute( array ( 'sub', $forumparentid, 'on' ));
        $subforums = $stmt->fetchAll(PDO::FETCH_NAMED);
               
        $tpl->assign('subforums', $subforums);
        
        ###############################################
        
        $stmt = $db->prepare('SELECT * FROM '. DB_PREFIX .'board_threads');
        $stmt->execute( array ( 'sub', $forumparentid, 'on' ));
        $threads = $stmt->fetchAll(PDO::FETCH_NAMED);
               
        $tpl->assign('threads', $threads);
        
        ###############################################
        
        $stmt = $db->prepare('SELECT * FROM '. DB_PREFIX .'board_posts');
        $stmt->execute( );
        $posts = $stmt->fetchAll(PDO::FETCH_NAMED);
               
        $tpl->assign('posts', $posts);
        
        
        // Output the Board
        $this->output .= $tpl->fetch('board/board.tpl');
    }
   
   
   function viewforum ()
   {
    
    
    
    //Input Variables
    
    $threadid = (isset($threadid) && is_numeric($threadid)) ? (int) $threadid : 0;
    $forumid = (isset($forumid) && is_numeric($forumid)) ? (int) $forumid : 0;
    
    
    // MYSQL 
    
    
    // OUTPUT
    
    
   }
   
}
?>
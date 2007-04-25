<?php
/**
* board
* The Clansuite Board
*
* PHP >= version 5.1.4
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
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @author     Florian Wolf <xsign.dll@clansuite.com>
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @link       http://gna.org/projects/clansuite
*
* @author     JAK  FW
* @copyright  2007 JAK  FW
* @license    LGPL
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

// Security Handler
if (!defined('IN_CS')) { die('You are not allowed to view this page.'); }

/**
* @desc Start module class
*/
class module_board_admin
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * General Function Hook of board-Modul
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

        global $lang, $trail, $perms;
        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Admin'), '/index.php?mod=admin');
        $trail->addStep($lang->t('Board'), '/index.php?mod=board&sub=admin');

        switch ($_REQUEST['action'])
        {

            default:
            case 'show':
                $trail->addStep($lang->t('Show'), '/index.php?mod=board&sub=admin&action=show');
                $this->show();
                break;
            
            case 'create_board':
                $trail->addStep($lang->t('Create a new board'), '/index.php?mod=board&amp;sub=admin&amp;action=create_board');
                $this->create_board();
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
    * Direct Call by URL/index.php?mod=board&sub=admin&action=show
    *
    * @global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;
    */

    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;
        
                
        // Assign and output
        $tpl->assign('boards', $this->build_boardtree());
        $this->output = $tpl->fetch('board/admin_board.tpl');
    }

    /**
     * We love elegant recursive functions until our heads explode.
     * Lets build up the boardtree with all it's nestinlevels/ childelements.
     * This function is similar to build_menueditor 
     */    
    function build_boardtree ( &$result = '', $parent = 0, $level = 0 )
    {
        global $db;

        /**
         * Fetch every Forum around there, if result is empty
         * Means: this is the starting point
         */
        if ( empty($result) )
        {         
            $stmt = $db->prepare('SELECT * FROM '. DB_PREFIX .'board_forums ORDER BY displayorder, forumparent ASC');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        $output = array();        
        $rows = count($result);     
      
        for ($i = 0; $i < $rows; $i++)
        {   
            if ($result[$i]['forumparent'] == $parent )
            {
                
                //new index
                $output[$result[$i]['forumid']] = array(
                                                            'forumid'       => $result[$i]['forumid'],
                                                            'forumparent'   => $result[$i]['forumparent'],
                                                            'name'          => $result[$i]['name'],
                                                            'description'   => $result[$i]['description'],
                                                            
                                                            'nestinglevel'  => $level,
                                                            'type'          => $result[$i]['type'],
                                                            
                                                            'moderator'     => $result[$i]['moderator'],
                                                            'displayorder'  => $result[$i]['displayorder'],
                                                            'posts'         => $result[$i]['posts'],
                                                            'threads'       => $result[$i]['threads'],
                                                            'type'          => $result[$i]['type'],
                                                            'status'        => $result[$i]['status'],
                                                        );
                
                
                // recursion callback with next level
                $output[$result[$i]['forumid']]['children'] = $this->build_boardtree($result, $result[$i]['forumid'], $level + 1);
                
                if( count($output[$result[$i]['forumid']]['children']) == 0)
                {
                    unset($output[$result[$i]['forumid']]['children']);
                }
             }        
        }        
        return array_values($output);
    }
    
    function create_board()
    {    
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;

        /**
         * Init
         */
        $submit     = $_POST['submit'];
        $info       = $_POST['newboard'];
   
        if ( !empty( $submit ) )
        {
            // Forum already stored?
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'board_forums WHERE name = ?' );
            $stmt->execute( array( $info['name'] ) );
            $forums = $stmt->fetch(PDO::FETCH_ASSOC);
            if ( is_array( $forums ) )
            {
                $err['name_already'] = 1;
            }

            // Form filled?
            if( empty($info['name']) OR
                empty($info['description']))
            {
                $err['fill_form'] = 1;
            }
        }
    
        /**
         * No errors - procceed to insert
         */
        if ( !empty($submit) AND count($err) == 0 )
        {
            /**
             * setting of "before" "after" means that "displayorder" has to be dealt with
             * setting of "child" means that "parentid" and "displayorder" have to be dealt with
             * @todo before, after, child handling 
             */
            if (($info['positiontype'] == before) OR ($info['positiontype'] == after))
            {}
                echo "insert";
            // Insert Forum to Db
            $sets =  'name = ?, description = ?, permissions = ?, forumparent = ?';
            $stmt = $db->prepare( 'INSERT ' . DB_PREFIX . 'board_forums SET ' . $sets );
            $stmt->execute( array ( $info['name'],
                                    $info['description'],
                                    $info['permissiontype'],
                                    $info['parentid']                                   
                                     ) );
            
    
        /**
         *  Redirect...
         */
        $functions->redirect( 'index.php?mod=board&sub=admin', 'metatag|newsite', 3, $lang->t( 'Board was created.' ), 'admin' );
        } 
        
         /**
        * @desc Assign & Show template
        */
        $tpl->assign( 'modules'     , $modules );
        $tpl->assign( 'err'         , $err );
        $this->output .= $tpl->fetch('board/admin_board.tpl');
    }
    
    /**
    * Instant Show
    *
    * Content of a module can be instantly displayed by adding the
    * {mod name="board" sub="admin" func="instant_show" params="mytext"}
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
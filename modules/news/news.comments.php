<?php
/**
* Modulename:   newscomments
* Description:  NewsComments
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
* @author     Jens Andre Koch
* @copyright  Jens Andre Koch
* @license    BSD
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

// Security Handler
if (!defined('IN_CS')) { die('You are not allowed to view this page.' ); }

// Begin of class module_newscomments
class module_news_comments
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc General Function Hook of newscomments-Modul 
    * 
    * 1. page title of modul is set
    * 2. $_REQUEST['action'] determines the switch 
    * 3. function title is added to page title, to complete the title
    * 4. switch-functions are called
    *
    * @return: array ( OUTPUT, MOD_PAGE_TITLE, ADDITIONAL_HEAD, SUPPRESS_WRAPPER )
    * 
    */
    function auto_run()
    {
        
        global $lang, $trail;
        $params = func_get_args();
        
        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('News'), '/index.php?mod=admin');
        $trail->addStep($lang->t('Comments'), '/index.php?mod=news&sub=newscomments');
        
        // 
        switch ($_REQUEST['action'])
        { 
            default:
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show comments for News #' );
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
    * @desc Function: Show
    */    
    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;
        
        $news_id = $_REQUEST['id'];
        $this->mod_page_title .= $news_id ;
        
        
        // $news_comments = news_entry mit nick und category
        $stmt = $db->prepare('SELECT n.*, u.nick, c.name as cat_name, c.image as cat_image
                                FROM ' . DB_PREFIX .'news n
                                LEFT JOIN '. DB_PREFIX .'users u USING(user_id)
                                LEFT JOIN '. DB_PREFIX .'categories c 
                                ON ( n.cat_id = c.cat_id AND 
                                     c.module_id = ? )
                                WHERE n.news_hidden = ? 
                                AND n.news_id = ?' );
        
        // TODO: news with status: draft, published, private, private+protected
        $hidden = '0';
        $stmt->execute( array ( $cfg->modules['news']['module_id'] , $hidden, $news_id) );
        $newslist = $stmt->fetch(PDO::FETCH_NAMED);
                
        // add to $newslist array, the number of news_comments for that news_id
        $stmt = $db->prepare('SELECT COUNT(*) FROM '. DB_PREFIX .'news_comments WHERE news_id = ?');
        $stmt->execute( array( $news_id ) );
        $newslist['nr_news_comments'] = $stmt->fetch(PDO::FETCH_COLUMN); 
                
        // add to $newslist array, the nickname of the author of the last comment for that news_id 
        if ($newslist['nr_news_comments'] > 0 ) 
        {               
             $stmt = $db->prepare('SELECT IFNULL(u.nick, c.pseudo) 
                                   FROM '. DB_PREFIX .'news_comments c 
            		               LEFT JOIN '. DB_PREFIX .'users u USING(user_id) 
            			           WHERE c.news_id = ? 
            			           ORDER BY c.comment_id DESC'); 
             $stmt->execute( array( $news_id ) );
             $newslist['lastcomment_by'] = $stmt->fetch(PDO::FETCH_COLUMN); 
        }
        
        // add to $newslist array, the comments for news_id
        $stmt = $db->prepare('SELECT * FROM '. DB_PREFIX .'news_comments 
                              WHERE news_id = ? 
                              ORDER BY added asc');
        $stmt->execute( array ( $news_id ) );
        if ($result = $stmt->fetchAll(PDO::FETCH_NAMED) )
        {
            $newslist[comments] = $result;
        }
        
          
        // give $newslist array to Smarty for template output
        $tpl->assign('news', $newslist);
        
        
        /**
        * @desc Handle the output - $lang-t() translates the text.
        */
        $this->output = $tpl->fetch('news/news_with_comments.tpl');
        
       }
    
    
    /**
    * @desc Function: instant_show
    *
    * This content can be instantly displayed by adding this into a template:
    * {mod name="newscomments" func="instant_show" params="mytext"} 
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
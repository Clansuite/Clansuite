<?php
/**
* Module: news
* Description: The website news
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
* @author     Florian Wolf
* @copyright  clansuite group
* @license    LGPL
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

// Security Handler
if (!defined('IN_CS')) { die('You are not allowed to view this page statically.' ); }

/**
* @desc Start module index class
*/
class module_news
{
    public $output          = '';
    public $mod_page_title  = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loads necessary language files
    */

    function auto_run()
    {
        global $lang;
        
        $this->mod_page_title = $lang->t( ' News ' );
        
        switch ($_REQUEST['action'])
        {   
            
            default:
            case 'show':
                $this->mod_page_title .= $lang->t( ' Show News ' );
                $this->show();
                break;
     
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
    * @desc Show news
    *
    * 1. get news with nick of author and category
    * 2. add general data of comments for each news
    *
    * @output: $newslist ( array for smarty template output )
    */

    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security;
        
        // $newslist = newsentries mit nick und category
        $stmt = $db->prepare('SELECT n.*, u.nick, c.name as cat_name, c.image as cat_image
                                FROM ' . DB_PREFIX .'news n
                                LEFT JOIN '. DB_PREFIX .'users u USING(user_id)
                                LEFT JOIN '. DB_PREFIX .'categories c 
                                ON ( n.cat_id = c.cat_id AND 
                                     c.module_id = ? )
                                WHERE n.news_hidden = ? 
                                ORDER BY news_id DESC' );
        
        /* TODO: news with status: draft, published, private, private+protected*/
        $hidden = '0';
        $stmt->execute( array ( $cfg->modules['news']['module_id'] , $hidden) );
        if ($result = $stmt->fetchAll(PDO::FETCH_NAMED) )
        {
            $newslist = $result;
            #return $newslist;
        }
        else
        {
            #return false;
        }
        
        // add the general news_comments data to the array of fetched news
        foreach ($newslist as $k => $v) 
        { 
            
            // add to $newslist array, the numbers of news_comments for each news_id
            $stmt = $db->prepare('SELECT COUNT(*) FROM '. DB_PREFIX .'news_comments WHERE news_id = ?');
            $stmt->execute( array( $v['news_id'] ) );
            $newslist[$k]['nr_news_comments'] = $stmt->fetch(PDO::FETCH_COLUMN); 
                
            // add to $neslist array, the nickname of the author of the last comment for each news_id 
            if ($newslist[$k]['nr_news_comments'] > 0 ) 
            {               
                 $stmt = $db->prepare('SELECT IFNULL(u.nick, c.pseudo) 
                                       FROM '. DB_PREFIX .'news_comments c 
                		               LEFT JOIN '. DB_PREFIX .'users u USING(user_id) 
                			           WHERE c.news_id = ? 
                			           ORDER BY c.comment_id DESC'); 
                 $stmt->execute( array( $v['news_id'] ) );
                 $newslist[$k]['lastcomment_by'] = $stmt->fetch(PDO::FETCH_COLUMN); 
            }
        
        }
          
        // give $newslist array to Smarty for template output
        $tpl->assign('news', $newslist);
        
        $this->output = $tpl->fetch('news/show.tpl');
    }
}
?>
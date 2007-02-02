<?php
/**
* Module: news
* Description: The website news
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
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2005-2007 Clansuite Group
* @link       http://gna.org/projects/clansuite
*
* @author     Jens-Andre Koch, Florian Wolf
* @copyright  2005-2007 Clansuite Group
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
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loads necessary language files
    */
    function auto_run()
    {
        global $lang, $trail;

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('News'), '/index.php?mod=news');

        switch ($_REQUEST['action'])
        {
            default:
            case 'show':
                $trail->addStep($lang->t('Show'), '/index.php?mod=news&action=show');
                $this->show();
                break;

            case 'archiv':
                $trail->addStep($lang->t('Newsarchiv'), '/index.php?mod=news&action=archiv');
                $this->archiv();
                break;
        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
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
    function instant_show_news($my_text)
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        /**
        * @desc Handle the output - $lang-t() translates the text.
        */
        $this->output .= $lang->t($my_text);
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

        // Smarty Pagination load and init
        require(ROOT . 'core/smarty/SmartyPaginate.class.php');
        // required connect
        SmartyPaginate::connect();
        // set URL
        SmartyPaginate::setUrl('index.php?mod=news');
        SmartyPaginate::setUrlVar('page');
        // set items per page
        SmartyPaginate::setLimit(5);

        // $newslist = newsentries mit nick und category
        $stmt = $db->prepare('SELECT SQL_CALC_FOUND_ROWS
                                n.*, u.nick, c.name as cat_name, c.image as cat_image
                                FROM ' . DB_PREFIX .'news n
                                LEFT JOIN '. DB_PREFIX .'users u USING(user_id)
                                LEFT JOIN '. DB_PREFIX .'categories c
                                ON ( n.cat_id = c.cat_id AND
                                     c.module_id = ? )
                                WHERE n.news_hidden = ?
                                ORDER BY news_id DESC LIMIT ?,?');

        /* TODO: news with status: draft, published, private, private+protected*/
        $hidden = '0';
        $stmt->bindParam(1, $cfg->modules['news']['module_id'], PDO::PARAM_INT);
        $stmt->bindParam(2, $hidden, PDO::PARAM_INT );
        $stmt->bindParam(3, SmartyPaginate::getCurrentIndex(), PDO::PARAM_INT );
        $stmt->bindParam(4, SmartyPaginate::getLimit(), PDO::PARAM_INT );
        $stmt->execute();
        $newslist = $stmt->fetchAll(PDO::FETCH_NAMED);

        // Get Number of Rows
        $rows = $db->prepare('SELECT found_rows() AS rows');
        $rows->execute();
        $rows_array = $rows->fetch(PDO::FETCH_NUM);
        $rows->closeCursor();
        $count = $rows_array[0];
        // DEBUG - show total numbers of last Select
        // echo 'Found Rows: ' . $count;

        // Finally: assign total number of rows to SmartyPaginate
        SmartyPaginate::setTotal($count);
        // assign the {$paginate} to $tpl (smarty var)
        SmartyPaginate::assign($tpl);


        // ---------  Prepared Statements: One time defined, often used!

        // Prepare Statement 1: Count all newst
        $stmt1 = $db->prepare('SELECT COUNT(*) FROM '. DB_PREFIX .'news_comments WHERE news_id = ?');

        // Prepare Statement 2: get the nickname of the last comment for certain news_id
        $stmt2 = $db->prepare('SELECT u.nick, c.pseudo
                                   FROM '. DB_PREFIX .'news_comments c
                                   LEFT JOIN '. DB_PREFIX .'users u USING(user_id)
                                   WHERE c.news_id = ?
                                   ORDER BY c.comment_id DESC');

        // --------- Database Loops over prepared Statements

        // add the general news_comments data to the array of fetched news
        foreach ($newslist as $k => $v)
        {
            // Execute prepared Statement 1
            // add to $newslist array, the numbers of news_comments for each news_id
            $stmt1->execute( array( $v['news_id'] ) );
            $newslist[$k]['nr_news_comments'] = $stmt->fetch(PDO::FETCH_COLUMN);

            // if news_comments exist for that news
            if ($newslist[$k]['nr_news_comments'] > 0 )
            {
                 // Execute prepared Statement 2
                 // add to $newslist array, the nickname of the author of the last comment for each news_id
                 $stmt2->execute( array( $v['news_id'] ) );
                 $newslist[$k]['lastcomment_by'] = $stmt->fetch(PDO::FETCH_COLUMN);
            }

        }

        // give $newslist array to Smarty for template output
        $tpl->assign('news', $newslist);

        $this->output = $tpl->fetch('news/show.tpl');
    }

    function archiv()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security;

         // Smarty Pagination load and init
        require(ROOT . 'core/smarty/SmartyPaginate.class.php');
        // required connect
        SmartyPaginate::connect();
        // set URL
        SmartyPaginate::setUrl('index.php?mod=news&action=archiv');
        SmartyPaginate::setUrlVar('page');
        // set items per page
        SmartyPaginate::setLimit(20);

        // $newsarchiv = newsentries mit nick und category
        $stmt = $db->prepare('SELECT SQL_CALC_FOUND_ROWS
                                     n.news_id,  n.news_title, n.news_added,
                                     n.user_id, u.nick,
                                     n.cat_id, c.name as cat_name, c.image as cat_image
                                FROM ' . DB_PREFIX .'news n
                                LEFT JOIN '. DB_PREFIX .'users u USING(user_id)
                                LEFT JOIN '. DB_PREFIX .'categories c
                                ON ( n.cat_id = c.cat_id AND
                                     c.module_id = ? )
                                WHERE n.news_hidden = ?
                                ORDER BY news_id LIMIT '. SmartyPaginate::getCurrentIndex() .' ,
                                                       '. SmartyPaginate::getLimit() .' ');

        // TODO: news with status: draft, published, private, private+protected
        $hidden = '0';
        $stmt->execute( array ( $cfg->modules['news']['module_id'] , $hidden) );
        $newsarchiv = $stmt->fetchAll(PDO::FETCH_NAMED);

        // Get Number of Rows
        $rows = $db->prepare('SELECT found_rows() AS rows');
        $rows->execute();
        $rows_array = $rows->fetch(PDO::FETCH_NUM);
        $rows->closeCursor();
        $count = $rows_array[0];
        // DEBUG - show total numbers of last Select
        echo 'Found Rows: ' . $count;

        // Finally: assign total number of rows to SmartyPaginate
        SmartyPaginate::setTotal($count);
        // assign the {$paginate} to $tpl (smarty var)
        SmartyPaginate::assign($tpl);

        // $categories for module_news
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'categories WHERE module_id = ?' );
        $stmt->execute( array ( $cfg->modules['news']['module_id'] ) );
        $newscategories = $stmt->fetchAll(PDO::FETCH_NAMED);

        // give $newslist array to Smarty for template output
        $tpl->assign('newsarchiv', $newsarchiv);
        $tpl->assign('newscategories', $newscategories);

        $this->output = $tpl->fetch('news/news_archiv.tpl');

    }

}
?>
<?php
/**
* new.admin.php
* Modul - News - Admin Interface
*
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
* @copyright  2005-2007 Clansuite Group
* @link       http://gna.org/projects/clansuite
*
* @author     Jens-André Koch
* @copyright  Clansuite Group
* @license    GPL v2
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}


/**
* @desc Start module class
*/
class module_news_admin
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
        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Admin'), '/index.php?mod=admin');
        $trail->addStep($lang->t('News'), '/index.php?mod=news&sub=admin');

        switch ($_REQUEST['action'])
        {
            default:
            case 'show':
                $trail->addStep($lang->t('Show'), '/index.php?mod=news&sub=admin&action=show');
                $this->show();
                break;

            case 'instant_show':
                $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
                break;

            case 'create':
                $trail->addStep($lang->t('Add a News'), '/index.php?mod=news&sub=admin&action=show&action=create');
                $this->create_news();
                break;

            case 'edit':
                $trail->addStep($lang->t('Edit News'), '/index.php?mod=news&sub=admin&action=show&action=edit');
                $this->edit_news();
                break;

            case 'delete':
                $trail->addStep($lang->t('Delete News'), '/index.php?mod=news&sub=admin&action=show&action=delete');
                $this->delete_news();
                break;
        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
    * @desc Show Overview of News (ordered: Date, Cats)
    */
    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        // Incoming Vars
        $cat = isset($_POST['cat_id']) ? (int) $_POST['cat_id'] : 0;

        // Smarty Pagination load and init
        require( ROOT_CORE . '/smarty/SmartyPaginate.class.php');

        // required connect
        global $SmartyPaginate;
        $SmartyPaginate = new SmartyPaginate();
        $SmartyPaginate->connect();

        // set URL
        $SmartyPaginate->setUrl('index.php?mod=news&amp;sub=admin');
        $SmartyPaginate->setUrlVar('page');
        // set items per page
        $SmartyPaginate->setLimit(10);

        // SmartyColumnSort -- Easy sorting of html table columns.
        require( ROOT_CORE . '/smarty/SmartyColumnSort.class.php');
        // A list of database columns to use in the table.
        $columns = array( 'n.news_added', 'n.news_title', 'cat_name','u.nick', 'n.draft');
        // Create the columnsort object
        $columnsort = &new SmartyColumnSort($columns);
        // And set the the default sort column and order.
        $columnsort->setDefault('n.news_added', 'desc');
        // Get sort order from columnsort
        $sortorder = $columnsort->sortOrder(); // Returns 'name ASC' as default

        // Category settings
        $sql_cat = $cat == 0 ? '' : 'AND n.cat_id = ' . $cat;

        // $newsarchiv = newsentries mit nick und category
        $stmt = $db->prepare('SELECT n.news_id,  n.news_title, n.news_added, n.draft,
                                     n.user_id, u.nick,
                                     n.cat_id, c.name as cat_name, c.image as cat_image
                                FROM ' . DB_PREFIX .'news n
                                LEFT JOIN '. DB_PREFIX .'users u USING(user_id)
                                LEFT JOIN '. DB_PREFIX .'categories c
                                ON ( n.cat_id = c.cat_id AND
                                     c.module_id = ? )
                                WHERE n.news_hidden = ? ' . $sql_cat . '
                                ORDER BY '. $sortorder .' LIMIT ?,?');

        // TODO: news with status: draft, published, private, private+protected
        $hidden = '0';
        $stmt->bindParam(1, $cfg->modules['news']['module_id'], PDO::PARAM_INT);
        $stmt->bindParam(2, $hidden, PDO::PARAM_INT );
        $stmt->bindParam(3, $SmartyPaginate->getCurrentIndex(), PDO::PARAM_INT );
        $stmt->bindParam(4, $SmartyPaginate->getLimit(), PDO::PARAM_INT );
        $stmt->execute();
        $newsarchiv = $stmt->fetchAll(PDO::FETCH_NAMED);

        // Get Number of Rows
        $rows = $db->prepare('SELECT COUNT(*) FROM '. DB_PREFIX .'news');
        $rows->execute();
        $count = $rows->fetch(PDO::FETCH_NUM);
        // DEBUG - show total numbers of last Select
        // echo 'Found Rows: ' . $count;

        // Finally: assign total number of rows to SmartyPaginate
        $SmartyPaginate->setTotal($count[0]);
        // assign the {$paginate} to $tpl (smarty var)
        $SmartyPaginate->assign($tpl);

        // $categories for module_news
        $stmt = $db->prepare( 'SELECT cat_id, name FROM ' . DB_PREFIX . 'categories WHERE module_id = ?' );
        $stmt->execute( array ( $cfg->modules['news']['module_id'] ) );
        $newscategories = $stmt->fetchAll(PDO::FETCH_NAMED);

        // give $newslist array to Smarty for template output
        $tpl->assign('newsarchiv', $newsarchiv);
        $tpl->assign('newscategories', $newscategories);

        $this->output = $tpl->fetch('news/show_admin.tpl');
    }

    /**
    * @desc This content can be instantly displayed by adding {mod name="admin" func="instant_show" params="mytext"} into a template
    * @desc You have to add the lines as shown above into the case block: $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
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
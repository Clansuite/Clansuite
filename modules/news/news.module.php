<?php
/**
* news
* The website news
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

/*
Tabellenstruktur für Tabelle `suite_news`

CREATE TABLE `cs_news` (
  `news_id` int(11) NOT NULL auto_increment,
  `news_title` varchar(255) NOT NULL default '',
  `news_body` text NOT NULL,
  `news_category` tinyint(4) NOT NULL default '0',
  `user_id` int(11) unsigned NOT NULL default '0',
  `news_added` int(11) unsigned NOT NULL default '0',
  `news_hidden` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`news_id`,`news_category`)
) ENGINE=MyISAM;
*/

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

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
        
        $this->mod_page_title = $lang->t( 'news' );
        
        switch ($_REQUEST['action'])
        {
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show' );
                $this->show();
                break;

            default:
                $this->show();
                break;
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
    * @desc Show the entrance - welcome message etc.
    */

    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security;
        
        // $newslist = newseinträge mit usernick und categorie
        $stmt = $db->prepare('SELECT n.*, u.nick, c.cat_name, c.cat_image_url 
                                FROM ' . DB_PREFIX .'news n
                                LEFT JOIN '. DB_PREFIX .'users u USING(user_id)
                                LEFT JOIN '. DB_PREFIX .'category c 
                                ON ( n.news_category = c.cat_id AND 
                                     c.cat_modulname = ?)
                                WHERE n.news_hidden = ? 
                                ORDER BY news_id DESC' );
        
        $stmt->execute( array ('news', '0') );
        if ($result = $stmt->fetchAll(PDO::FETCH_NAMED) )
        {
            $newslist = $result;
            
            #return $newslist;
        }
        else
        {
            #return false;
        }
        
        // $newslist arrayergänzung, um die anzahl der newskommentare
        foreach ($newslist as $k => $v) 
        { 
        
        $stmt = $db->prepare('SELECT COUNT(*) FROM '. DB_PREFIX .'news_comments WHERE news_id = ?');
        $stmt->execute( array( $v['news_id'] ) );
        if ($result = $stmt->fetch(PDO::FETCH_BOUND) ) { $newslist[$k]['nr_news_comments'] = $result; }
        
        }
        
        /** in pdo umwandeln
        // falls es comments gibt, den usernick des letzten comments holen
        foreach ($newslist as $k => $v) 
        		{ if ($newslist[$k]['nr_news_comments'] > 0 ) {
        		$newslist[$k]['lastcomment_by'] = $Db->getOne("SELECT IFNULL(u.nick, c.pseudo) FROM ".DB_PREFIX."news_comments c 
        										  			   LEFT JOIN ".DB_PREFIX."users u USING(user_id) 
        													   WHERE c.news_id = ? ORDER BY c.comment_id DESC", $v['news_id']); } }
        */
        
        print_r($newslist);
        
        // $newslist an Smarty übergeben und Template ausgeben
        $tpl->assign('news', $newslist);
        
        $this->output = $tpl->fetch('news/viewnews.tpl');
        $this->output .= 'This Module is not completed yet!';
    }
}
?>
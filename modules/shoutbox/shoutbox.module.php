<?php
/**
* shoutbox Configuration
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
* @author     Björn Spiegel <firstlor@yahoo.de>
* @copyright  2006 Clansuite Group
* @link       http://gna.org/projects/clansuite
*
* @author     Jens-Andé Koch, Florian Wolf
* @copyright  Clansuite Group
* @license    GPL v2
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

/*
Tabellenstruktur für Tabelle `cs_shoutbox`

CREATE TABLE `cs_shoutbox` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `name` VARCHAR( 100 ) NOT NULL ,
  `mail` VARCHAR( 100 ) NOT NULL ,
  `msg` TINYTEXT NOT NULL ,
  `time` INT( 10 ) UNSIGNED NOT NULL ,
  `ip` VARCHAR( 15 ) NOT NULL
) ENGINE = MYISAM ;
*/

/**
 + Enable Modul:
 INSERT INTO `cs_modules` ( `module_id` , `name` , `author` , `homepage` , `license` , `copyright` , `title` , `description` , `class_name` , `file_name` , `folder_name` , `enabled` , `image_name` , `version` , `cs_version` , `core` , `subs` )
	VALUES (
	NULL , 'shoutbox', 'Björn Spiegel', 'http://www.clansuite.com', 'GPL v2', 'Clansuite Group', 'Shoutbox Modul', 'This module displays a shoutbox. You can do entries and administrate it ...', 'module_shoutbox', 'shoutbox.module.php', 'shoutbox', '0', 'module_shoutbox.jpg', '0.1', '0', '1', 's:0:"";'
	);
 
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
class module_shoutbox
{
    public $output          = '';
    public $mod_page_title  = '';
    public $additional_head = '';

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loads necessary language files
    */

    function auto_run()
    {
        global $lang;
        
        $this->mod_page_title = $lang->t( 'shoutbox' );
        
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
                      'ADDITIONAL_HEAD' => $this->additional_head );
    }

    /**
    * @desc Show the entrance - welcome message etc.
    */

    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security;
        
        // $newslist = newseinträge mit usernick und categorie
        $stmt = $db->prepare('SELECT 		id, name, mail, msg, time 
							  FROM          cs_shoutbox');
        $stmt->execute();
        if ($result = $stmt->fetchAll(PDO::FETCH_NAMED) )
        {
            $shoutlist = $result;
           
            #return $newslist;
        }
        else
        {
			#return false;
        }
		
    }
}
?>
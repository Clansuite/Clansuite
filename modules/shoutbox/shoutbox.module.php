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
        global $lang, $tpl;
        
        $this->mod_page_title = $lang->t( 'shoutbox' ) . ' &raquo; ';
        
		// Smarty Flags:
		$tpl->assign('showForm'   , false);
		$tpl->assign('isSaved'    , false);
		$tpl->assign('isError'    , false);
		$tpl->assign('errorList'  , array());
		
        switch ($_REQUEST['action'])
        {
		
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
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
        
		// Smarty Flags:
		$tpl->assign('shoutbox_isEmpty', 	true);
		$tpl->assign('showForm', 			true);
		
		// Speichern
		if(isset($_POST['sent'])) {
			// Überprüfe 
			$this->save_entry();			
		}
		// Formular und Einträge anzeigen
		else {
			// Values der Felder:
			$tpl->assign('save_entry'         	, $lang->t('Save Entry'));
			$tpl->assign('field_value_name'   	, $lang->t('Your Name'));
			$tpl->assign('field_value_mail'  	, $lang->t('Your Mail'));
			$tpl->assign('field_value_msg'    	, $lang->t('Your Msg'));
			$tpl->assign('request'				, WWW_ROOT . '/index.php?mod=shoutbox&action=save');
			
			// Einträge auslesen:
	        $stmt = $db->prepare('SELECT 		id, name, mail, msg, time 
								  FROM          ' . DB_PREFIX . 'shoutbox');
	        $stmt->execute();
	       
	        if ($result = $stmt->fetchAll(PDO::FETCH_NAMED) )
	        {
	           $tpl->assign('shoutbox_isEmpty', false);

			   $tpl->assign('shoutbox_entries', $result);
				
	           $output = $tpl->fetch('shoutbox/entries_box.tpl');
				
			   return $output;
	        }
	        else
	        {
				$tpl->assign('no_entries_msg', $lang->t('There are no Entries in the Database!'));
				return $tpl->fetch('shoutbox/entries_box.tpl');
			}
		}
    }
	
	/**
	 * @desc   Save Entry
	 */
	function save_entry()
	{
		global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
		
		// Formularvalidierung
		$name = trim($_POST['name']);
		$mail = trim($_POST['mail']);
		$msg  = trim($_POST['msg']);
		
		
		$errors = array();
		if(!isset($name) || strlen(trim($name)) < 3 || trim($name) == $lang->t('Your Name'))
			$errors[] = $lang->t('Your name hast to be longer than 3 chars');
			
		if(!isset($mail) || strlen(trim($mail)) < 3 || trim($mail) == $lang->t('Your Mail'))
			$errors[] = $lang->t('Your mail-adress hast to longer than 3 chars');
			
		if(!isset($msg) || strlen(trim($msg)) < 5 || trim($msg) == $lang->t('Your Msg'))
			$errors[] = $lang->t('Your message hast to be longer than 5 chars');
			
		if(isset($mail) && strlen(trim($mail)) > 3 && !$input->check($mail, 'is_email'))
			$errors[] = $lang->t('Enter a valid mail-adress');
			
		// Fehler aufgetreten?	
		if(count($errors) > 0) {
			// Smarty Flags setzen:
			$tpl->assign('isError', true);
			$tpl->assign('_errorList', $errors);
		}
		// Speichere Eintrag
		else {
			$tpl->assign('isSaved', true);
			$tpl->assign('save_msg', $lang->t('Thank you - Your entry was saved.'));
			
			$stmt = $db->prepare('	INSERT INTO 		' . DB_PREFIX . 'shoutbox (name, mail, msg, time, ip) 
									VALUES 										   (:1,   :2,   :3,  :4,  :5)');
	        $stmt->execute(array($name, $mail, $msg, time(), $_SERVER['REMOTE_ADDR']));
	        
		}
	}
}
?>
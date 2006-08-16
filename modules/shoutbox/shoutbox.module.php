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
		$tpl->assign('showForm', false);
		$tpl->assign('isSaved', false);
		$tpl->assign('isError', false);
		$tpl->assign('errorList', array());
		
        switch ($_REQUEST['action'])
        {
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show' );
                $this->show();
                break;

			case 'form':
				$this->mod_page_title .= $lang->t('Show Form');
				$this->show_form();
				break;
				
			case 'save':
				$this->mod_page_title .= $lang->t('Save Shoutbox entry');
				$this->save_entry();
				break;
				
            default:
                $this->show();
                break;
        }
        
		$this->output = $tpl->fetch('shoutbox/viewnews.tpl');
        $this->output .= 'This Module is not completed yet!';
		
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
        
        $stmt = $db->prepare('SELECT 		id, name, mail, msg, time 
							  FROM          ' . DB_PREFIX . '_shoutbox');
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
	
	/**
	 * @desc   Show Form 
	 */
	function show_form()
	{
		global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
		
		$tpl->assign('showForm', true);
		$tpl->assign('request', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '&action=save');
		
		// Values der Felder:
		$tpl->assign('save_entry', $lang->t('Save Entry'));
		$tpl->assign('field_value_name', $lang->t('Your Name'));
		$tpl->assign('field_value_mail', $lang->t('Your Mail'));
		$tpl->assign('field_value_msg', $lang->t('Your Msg'));
	}
	
	/**
	 * @desc   Save Entry
	 */
	function save_entry()
	{
		global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
		
		// Formularvalidierung
		$errors = array();
		if(!isset($_POST['name']) || strlen(trim($_POST['name'])) < 3 || trim($_POST['name']) == $lang->t('Your Name'))
			$errors[] = $lang->t('Your name hast to be longer than 3 chars');
			
		if(!isset($_POST['mail']) || strlen(trim($_POST['mail'])) < 3 || trim($_POST['mail']) == $lang->t('Your Mail'))
			$errors[] = $lang->t('Your mail-adress hast to longer than 3 chars');
			
		if(!isset($_POST['msg']) || strlen(trim($_POST['msg'])) < 5 || trim($_POST['msg']) == $lang->t('Your Msg'))
			$errors[] = $lang->t('Your message hast to be longer than 5 chars');
			
		if(isset($_POST['mail']) && strlen(trim($_POST['mail'])) > 3 && !$input->check($_POST['mail'], 'is_email'))
			$errors[] = $lang->t('Enter a valid mail-adress');
			
		// Fehler aufgetreten?	
		if(count($errors) > 0) {
			// Smarty Flags setzten:
			$tpl->assign('isError', true);
			$tpl->assign('_errorList', $errors);
		}
		// Speichere Eintrag
		else {
			$tpl->assign('isSaved', true);
			$tpl->assign('save_msg', $lang->t('Thanks, Your Entry hast been saved'));
			
			$stmt = $db->prepare('INSERT INTO ' . DB_PREFIX . '_shoutbox (name, mail, msg, time, ip) VALUES (:1, :2, :3, :4, :5)');
	        $stmt->execute(array($_POST['name'], $_POST['mail'], $_POST['msg'], time(), $_SERVER['REMOTE_ADDR']));
	        
		}
	}
}
?>
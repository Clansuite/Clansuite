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
* @author     Jens-Andre Koch, Florian Wolf
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
    public $suppress_wrapper= '';

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loads necessary language files
    */

    function auto_run()
    {
        global $lang, $tpl;

        $this->mod_page_title = $lang->t( 'shoutbox' ) . ' &raquo; ';
        
		// Smarty Flags:
		$tpl->assign('show_form'   , false);
		$tpl->assign('is_saved'    , false);
		$tpl->assign('is_error'    , false);
		$tpl->assign('errorList'   , array());
		$tpl->assign('show_error', true);
		
		// Wurde Ajax Reqest gesendet, dann prüfen und ggf. speichern
		/*
        if(isset($_GET['check'])) {
			$this->check();
		}
		else {
			// Falls das Formular ohne JS abgesendet wurde
			if(isset($_POST['sent'])) {
				$this->mod_page_title .= $lang->t( 'Prove your Entry' );
				$this->check();		// prüfen
			}
		}
        */
        
        switch ($_REQUEST['action'])
        {
            case 'check':
                $this->check();
                break;
                
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show Shoutbox' );
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
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
        
		// Smarty Flags:
		$tpl->assign('shoutbox_is_empty', 	true);
		$tpl->assign('show_form', 			true);
		
		// Formular und Einträge anzeigen //

		// Values der Felder:
		$tpl->assign('save_entry'         	, $lang->t('Save Entry'));
		$tpl->assign('field_value_name'   	, $lang->t('Your Name'));
		$tpl->assign('field_value_mail'  	, $lang->t('Your Mail'));
		$tpl->assign('field_value_msg'    	, $lang->t('Your Msg'));
		
		// Einträge auslesen:
		$stmt = $db->prepare('SELECT 		id, name, mail, msg, time 
							  FROM          ' . DB_PREFIX . 'shoutbox ORDER BY time DESC LIMIT 0,5');
		$stmt->execute();
	    $result = $stmt->fetchAll(PDO::FETCH_NAMED);
		if ( is_array( $result ) )
		{
		   $tpl->assign('shoutbox_is_empty', false);
		   $tpl->assign('shoutbox_entries', $result);
		   $tpl->assign('entries_box', $tpl->fetch('shoutbox/entries_box.tpl') );
		}
		else
		{
            $tpl->assign('no_entries_msg', $lang->t('There are no Entries in the Database!'));
			$tpl->assign('entries_box', $tpl->fetch('shoutbox/entries_box.tpl') );
		}
        $this->output .= $tpl->fetch('shoutbox/show_form.tpl');
    }
	
	/**
	 * checks posted values
	 */
	function check()
	{
		global $lang, $input, $tpl;
		
		$name   = trim($_POST['name']);
		$mail   = trim($_POST['mail']);
		$msg    = trim($_POST['msg']);
		$check  = $_GET['check'];
        
		$errors = array();
		
		if(!isset($name) || strlen(trim($name)) < 3 || trim($name) == $lang->t('Your Name'))
			$errors[] = $lang->t('Your name hast to be longer than 3 chars');
			
		if(!isset($mail) || strlen(trim($mail)) < 3 || trim($mail) == $lang->t('Your Mail'))
			$errors[] = $lang->t('Your mail-address hast to longer than 3 chars');
			
		if(!isset($msg) || strlen(trim($msg)) < 5 || trim($msg) == $lang->t('Your Msg'))
			$errors[] = $lang->t('Your message hast to be longer than 5 chars');
			
		if(isset($mail) && strlen(trim($mail)) > 3 && !$input->check($mail, 'is_email'))
			$errors[] = $lang->t('Enter a valid mail-adress');
		
		//print_r($errors); // debug
		
		// Fehler ... 
		if(count($errors) > 0) {
			if( $check == 'true' )	// ajax request
				echo implode('%%%', $errors);		// Zurückgeben (... ajax ...)
			else {	// normaler page request 
				$tpl->assign('is_error', true);
				$tpl->assign('_errorList', $errors);
				$this->output .= $tpl->fetch('shoutbox/show_form.tpl');				
			}
		}
		else {
			$this->save_entry();	// Speichern
		}
		
		#print_r(debug_backtrace());
		if( $check == 'true' )	// nur falls der request per ajax gesendet wurde
			die();
	}
	
	/**
	 * @desc   Save Entry
	 */
	function save_entry()
	{
		global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
		
		// Formularvalidierung
		$name   = trim($_POST['name']);
		$mail   = trim($_POST['mail']);
		$msg    = trim($_POST['msg']);
		$check  = $_GET['check'];
        
		// Db Insert
		$stmt = $db->prepare('INSERT INTO ' . DB_PREFIX . 'shoutbox (name, mail, msg, time, ip) 
		                      VALUES (?, ?, ?, ?, ?)');
		$stmt->execute(array($name, $mail, $msg, time(), $_SERVER['REMOTE_ADDR']));		
		
		// Falls der Request nicht per ajax kommt:
		if( $check != 'true' )
        {
			$tpl->assign('is_saved', true);
			$tpl->assign('save_msg', $lang->t('Your shoutbox entry was saved successfully!'));
			$this->output .= $tpl->fetch('shoutbox/show_form.tpl');
		}
        else
        {
		    // Smarty Flags:
		    $tpl->assign('shoutbox_is_empty', 	true);
		    $tpl->assign('show_form', 			true);
		    
		    // Einträge auslesen:
		    $stmt = $db->prepare('SELECT 		id, name, mail, msg, time 
							      FROM          ' . DB_PREFIX . 'shoutbox ORDER BY time DESC LIMIT 0,5');
		    $stmt->execute();
	        $result = $stmt->fetchAll(PDO::FETCH_NAMED);
		    if ( is_array( $result ) )
		    {
		       $tpl->assign('shoutbox_is_empty', false);

		       $tpl->assign('shoutbox_entries', $result);

		       echo $tpl->fetch('shoutbox/entries_box.tpl');
               die();
		    }            
        }
	}
}
?>
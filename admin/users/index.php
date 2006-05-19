<?php
/*****************************************************************************/
/* Clansuite - just another E-Sport CMS                                      */
/* Copyright (C) 1999 - 2006 Jens-André Koch (jakoch@web.de)                 */
/*                                                                           */
/* Clansuite is free software; you can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by      */
/* the Free Software Foundation; either version 2 of the License, or         */
/* (at your option) any later version.                                       */
/*                                                                           */
/* Clansuite is distributed in the hope that it will be useful,              */
/* but WITHOUT ANY WARRANTY; without even the implied warranty of            */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             */
/* GNU General Public License for more details.                              */
/*                                                                           */
/* You should have received a copy of the GNU General Public License         */
/* along with this program; if not, write to the Free Software               */
/* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA  */
/*****************************************************************************/

require '../../shared/prepend.php';

$TITLE = 'Administration: Users'; 
include '../shared/header.tpl';    // Header einbinden
include '../shared/menuclass.php'; // Adminmenü aus Db holen und einbinden


	/**
    * retrives all users from users database
    *
    * @return bool  Auth was succesful
    */
  function list_all_users(){
  global $Db;
    
    $userslist = $Db->getall("SELECT * FROM " . DB_PREFIX . "users");
    
    return $userslist;
  }

  /**
    * retrives userinfos by user_id 
    *
    * @param userid
    * @return userinfo array  Userinfo for userid
    */
  function get_userinfo($userid){
  global $Db;
    
    $userinfo = $Db->getRow("SELECT * FROM " . DB_PREFIX . "users WHERE user_id = ?", $userid);
    
    return $userinfo;
  }

// MAIN
$_CONFIG['template_dir'] = ROOT.'/admin/users/';

if(!isset($_GET['action'])) $action = "";
else $action = $_GET['action'];
switch ($action):

    default:
     $Page = new SmarterTemplate( "admin_users.tpl" );
     $userslist = list_all_users();
     $Page->assign('userslist', $userslist);
     $Page->output();
	break;

	case 'edit':
     $Page2 = new SmarterTemplate("user_profil.tpl");
     echo $_GET['id'];
	 $userinfo = get_userinfo($_GET['id']);
     var_dump($userinfo);
     $Page2->assign('userinfo', $userinfo);
     $Page2->output();
	break;
	


	case 'update_profil':
	
	$user_id = (int) post('userid');
	
	$email = post('email');
	$nick = post('nick');
	$first_name = post('first_name');
	$last_name = post('last_name');
	
	echo $user_id.'aaaaaaaaaaaaaaaaaaaaaa';
	
    $Db->execute("UPDATE " . DB_PREFIX . "users SET 
						 email = ?, nick = ?, first_name = ?, last_name = ? 
	WHERE user_id = ?", $email, $nick, $first_name, $last_name, $user_id);
	
    
	break;

endswitch;

include '../shared/footer.tpl'; ?>
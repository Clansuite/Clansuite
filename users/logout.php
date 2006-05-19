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

include '../shared/prepend.php';

//header("Cache-control: private"); 
//Session::SessionDestroy();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (isset($_COOKIE[$Session->SessionName])) {
   setcookie($Session->SessionName, '', time()-42000, '/');
   unset($_COOKIE[$Session->SessionName]);
}
// Clean Remember-Me Cookies
setcookie('userid', '', time()-3600*24, '/');
unset($_COOKIE['userid']);
setcookie('password', '', time()-3600*24, '/');
unset($_COOKIE['password']);


unset($user);

$hostname = $_SERVER['HTTP_HOST'];
$path = dirname($_SERVER['PHP_SELF']);
header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/index.php');

//header('Location: '.WWW_ROOT.'/');
?>
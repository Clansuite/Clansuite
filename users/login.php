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

require '../shared/prepend.php';

$email = post('email');
$password = post('password');
$remember = post('remember');

if ($email && $password) { 
	$userid = User::check_user($email, $password); 
	if ($userid != false) { 
		User::login($userid, $remember); 
		header('Location: '.WWW_ROOT.'/admin/index.php');
	    exit;
		}
	else {	// User existiert nicht oder Passwort falsch!
		unset($user['user_id']);
		
		if(!isset($_SESSION['login_attempts'])) 
			 { $_SESSION['login_attempts'] = '1'; }
		else { $_SESSION['login_attempts']++; 
		# ziel : z.b. if login_attempts == '3' -> close session, 15min ip ban? 
		# bin für bessere lösung offen... stunt :D
		}
				
		#echo 'Ihre Anmeldedaten waren nicht korrekt!';
		#echo 'Dies ist Ihr '. $_SESSION['login_attempts'].'ter Versuch sich anzumelden !';
		
	}
} 

$MainPage->assign('title','User :: Login');
$MainPage->display("header.tpl");
?>
    <h1>Login</h1>

    <form action="login.php" method="post">
    <table>
        <tr>
            <td>Email:</td>
            <td><input type="text" name="email" value="<?php echo post('email'); ?>"></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="password" value=""></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Login" onclick="return validateForm(this.form)">
                <input type="checkbox" name="remember" value="1" <?php echo post('remember') ? 'checked="checked"' : ''; ?>>
                remember me
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <a href="register.php">Not yet registered ?</a> <br>
                <a href="forgot-password.php">Forgot password ?</a> <br>
                <a href="activation-email.php">Did not get an activation email ?</a> <br>
            </td>
        </tr>
    </table>
    </form>

    <script type="text/javascript" src="../shared/form.js"></script>
    <script type="text/javascript">
    function validateForm(form) {
        var email = form.elements['email'];
        var password = form.elements['password'];
        if (!email.value || !password.value) {
            alert('Email & password are required');
            return false;
        }
        return true;
    }
    </script>

<?php $MainPage->display("footer.tpl"); ?>
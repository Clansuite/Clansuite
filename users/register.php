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

$email = trim(post('email'));
$email2 = trim(post('email2'));
$nick = trim(post('nick'));

$err = array();

if ($email && $email2 && $nick && isEmail($email) && $email == $email2 && checkNick($nick)) {
    
	//TODO: entfällt der check, wenn datenbank felder UNIQUE KEYs sind ?
	//select count(*) ??? anstelle getrow?
    $user1 = $Db->getRow("SELECT * FROM users WHERE email = ?", $email);
    $user2 = $Db->getRow("SELECT * FROM users WHERE nick = ?", $nick);
    
    if ($user1) { $err['email_exists'] = 1; }
    if ($user2) { $err['nick_exists'] = 1; }
    
    if (count($err) == 0) {
        
        $password = genString(6);
        $Db->execute("INSERT INTO users (email, nick, password, joined) VALUES (?, ?, ?, NOW())", $email, $nick, md5($password));
        $id_user = $Db->insertId();
        
        include ROOT.'/shared/Mail.php';
        $domain = $_SERVER['SERVER_NAME'];

        $body  = "To activate an account click on the link below:\r\n";
        $body .= "http://$domain".WWW_ROOT."/users/activate-account.php?id_user=%s&code=%s\r\n";
        $body .= "Password: %s";
        $body  = sprintf($body, $id_user, md5(md5($password)), $password);
        
        $Mail = new Mail($domain, 'noreply@'.$domain, $email, "Account activation", $body);
        
        if ($Mail->send()) {
            header('Location: register-done.php');
            exit;
        } else {
            header('Location: register-error.php');
            exit;
        }
    }
}

function isEmail($s) {
    return (preg_match('/^\w+@\w+\.[\w.]+$/', $s) && $s[$s[strlen($s)-1]] != ".");
}
function checkNick($s) {
    return (preg_match('/^[a-z0-9~`!@\#$%^&*()\-_=+\\|\[{\]};:\'",.\/?]+$/i', $s)
        && strlen($s) >= 3 && strlen($s) <= 25);
}
function genString($len) {
    $s = '';
    for ($i = 1; $i <= $len; ++$i) {
        $rand = rand(1, 3);
        switch ($rand) {
            case 1:
                $s .= chr(rand(48, 57)); // [0-9]
                break;
            case 2:
                $s .= chr(rand(97, 122)); // [a-z]
                break;
            case 3:
                $s .= chr(rand(65, 90)); // [A-Z]
                break;
        }
    }
    return $s;
}
$MainPage->assign("title", 'Register');
$MainPage->display("header.tpl");
?>
    <h1>Register</h1>

    <?php
        if (isset($err['email_exists'])) { echo '<p class="error">There is already an account with such email.</p>'; }
        if (isset($err['nick_exists'])) { echo '<p class="error">There is already an account with such nick.</p>'; }
    ?>


    <form action="register.php" method="post">
    <table>
        <tr>
            <td>Email:</td>
            <td><input type="text" name="email" value="<?php echo post('email'); ?>"></td>
        </tr>
        <tr>
            <td>Confirm email:</td>
            <td><input type="text" name="email2" value="<?php echo post('email2'); ?>"></td>
        </tr>
        <tr>
            <td>Nick:</td>
            <td><input type="text" name="nick" value="<?php echo post('nick'); ?>"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Register" onclick="return validateForm(this.form)">
            </td>
        </tr>
    </table>
    </form>

    <script type="text/javascript" src="../shared/form.js"></script>
    <script type="text/javascript">
    function validateForm(form) {
        
        var email = form.elements['email'];
        var email2 = form.elements['email2'];
        var nick = form.elements['nick'];

        email.value = email.value.trim();
        email2.value = email2.value.trim();
        nick.value = nick.value.trim();

        if (!email.value.length) {
            alert("Email is required");
            return false;
        }
        if (!isEmail(email.value)) {
            alert("Email is not valid");
            return false;
        }
        if (email.value != email2.value) {
            alert("Emails are different");
            return false;
        }
        if (!checkLength(nick.value, 3, 25)) {
            alert("Nick must be min 3 and max 25 characters long");
            return false;
        }
        if (!nick.value.match(/^[a-z0-9~`!@\#$%^&*()\-_=+\\|\[{\]};:\'",.\/?]+$/i)) {
            alert("Nick contains special characters that are not allowed");
            return false;
        }
        
        return true;
    }
    </script>

    Note: you will need to activate account after registering, details will be sent to your email account.

<?php
$MainPage->display("footer.tpl");
?>
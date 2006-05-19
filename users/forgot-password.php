<?php
/*****************************************************************************/
/* Clansuite - just another E-Sport CMS                                      */
/* Copyright (C) 1999 - 2006 Jens-Andr� Koch (jakoch@web.de)                 */
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

$errorWhileSending = false;
$noSuchAccount = false;
$accountNotActivated = false;

if ($email) {
    $u1 = new User(null, $email);
    if ($u1->exists() && $u1->isActivated()) {

        $password = genString(6);
        $Db->execute("UPDATE users SET new_password = ? WHERE id_user = ?", md5($password), $u1->getId());
       
        include ROOT.'/shared/Mail.php';
        $domain = $_SERVER['SERVER_NAME'];

        $body  = "To activate new password click on the link below:\r\n";
        $body .= "http://$domain".WWW_ROOT."/users/activate-password.php?id_user=%s&code=%s\r\n";
        $body .= "New Password: %s";
        $body  = sprintf($body, $u1->getId(), md5(md5($password)), $password);
        
        $Mail = new Mail($domain, 'noreply@'.$domain, $email, "New password", $body);
        
        if ($Mail->send()) {
            header('location: forgot-password-sent.php');
            exit;
        } else {
            $errorWhileSending = true;
        }

    } else {
        if (!$u1->exists()) $noSuchAccount = true;
        if ($u1->exists() && !$u1->isActivated()) $accountNotActivated = true;
    }
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

$MainPage->assign('title','User :: Forgot password');
$MainPage->display("header.tpl");
?>
    <h1>Forgot Password</h1>

    <p>Enter your email below and a new password will be generated for your account and sent to you by email.</p>

    <?php if ($noSuchAccount) { echo '<p class="error">There is no account with such email.</p>'; } ?>
    <?php if ($accountNotActivated) { echo '<p class="error">Account with this email has not been yet activated.</p>'; } ?>
    <?php if ($errorWhileSending) { echo '<p class="error">There was an error while sending an email. Please, try again later.</p>'; } ?>
    
    <form action="forgot-password.php" method="post">
    <table>
    <tr>
        <td>Email:</td>
        <td><input type="text" name="email" value="<?php echo $email; ?>"></td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="submit" name="submit" value="Send new password" onclick="return validateForm(this.form)">
        </td>
    </tr>
    </table>
    </form>

    <script type="text/javascript" src="../shared/form.js"></script>
    <script type="text/javascript">
    function validateForm(form) {
        var email = form.elements['email'];
        if (!isEmail(email.value)) {
            alert('Email is not valid.');
            return false;
        }
        return true;
    }
    </script>

<?php
$MainPage->display("footer.tpl");
?>
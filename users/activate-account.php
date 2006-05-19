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

$id_user = (int) get('id_user');
$code = get('code');

$alreadyActivated = false;
$success = false;

if ($id_user && $code) {
    $user = $Db->getRow("SELECT * FROM users WHERE id_user = ?", $id_user);
    if ($user) {
        if ($user['level'] != 0) {
            $alreadyActivated = true;
        } else if ($code == md5($user['password'])) {
            $Db->execute("UPDATE users SET level = 1 WHERE id_user = ?", $id_user);
            $success = true;
        }
    }
}
$MainPage->assign('title','User :: Activate account');
$MainPage->display("header.tpl");
?>
    <h1>Activate account</h1>
    <p>
        <?php
            if ($alreadyActivated) echo '<p>Your account is already activated.</p>';
            else if ($success) echo '<p>Your account has been activated.</p>';
            else echo '<p class="error">Account activation failed. Check if the code and user id are valid in the URL.</p>';
        ?>

    </p>
<?php
$MainPage->display("footer.tpl");
?>
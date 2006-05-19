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

$user = $Db->getRow("SELECT email, nick FROM suite_users WHERE user_id = ?", (int) get('user_id'));
if (!$user) {
    include ROOT.'/shared/error.php';
}

function toAscii($s) {
    $ret = '';
    for ($i = 0; $i < strlen($s); $i++) {
        $ret .= ("&#".ord($s[$i]).";");
    }
    return $s;
}
$MainPage->assign('title','User :: '. $user['nick']);
$MainPage->display("header.tpl");
?>
    <h1>User info</h1>

    <table>
    <tr>
        <td class="block">Nick:</td>
        <td class="block"><?php echo $user['nick']; ?></td>
    </tr>
    <tr>
        <td class="block">Email:</td>
        <td class="block"><a href="mailto:<?php echo toAscii($user['email']); ?>"><?php echo toAscii($user['email']); ?></a></td>
    </tr>
    </table>

<?php $MainPage->display("footer.tpl"); ?>
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

$MainPage->assign('title','User :: Account created');
$MainPage->display("header.tpl");
?>
    <h1>Account created</h1>
    <p>
        Account has been created, but it still requires activation. <br>
        Details has been sent to your email account.
    </p>

<?php $MainPage->display("footer.tpl"); ?>
<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
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
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005-$Date$), Florian Wolf (2006-2007)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

/**
 * Security Handler
 */
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}
?>
<html>
    <head>
        <title>{t}Warning: Intrusion Detection{/t}</title>
    </head>
<body>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <div align="center">
        <img src="<php echo WWW_ROOT_THEMES_CORE?>/images/security_breach.jpg" border="0">
        <br>
        <font face="Verdana" size="2" color="red">
            <strong><?php echo _('Warning: Intrusion Detection'); ?></strong>
            <br>
            <php echo _('Sorry! But your latest action got classified as an possible hacking attempt or there is an unusual failure in progress.'); ?>
            <br>
            <php echo _('User actions were logged and reported to the webmaster.'); ?>
        </font>
    </div>
</body>
</html>
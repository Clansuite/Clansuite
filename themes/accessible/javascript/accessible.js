/**
 * Clansuite - just an E-Sport CMS
 * Jens-Andre Koch, Florian Wolf
 * http://www.clansuite.com/
 * All rights reserved
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
 * @license    GNU/GPL, see COPYING.txt
 *
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-2007)
 *
 * @link       http://www.clansuite.com
 * @link       http://gna.org/projects/clansuite
 * @since      File available since Release 0.2
 *
 * @version    SVN: $Id: accessible.js 1508 2007-11-12 20:25:20Z vyper $
 */

function passTest()
{
	var l_factor = 5;
    var g_factor = 20;
    var max_length = 100;
    var reg_ex = /^[\w]*$/;

    if (reg_ex.test(jQuery('#password').val())) {
        l_factor = 4;
        g_factor = 20;
    } else {
        l_factor = 9;
        g_factor = 30;
    }

    var pass1 = jQuery('#password').val();
    var pass2 = jQuery('#password2').val();

    if (pass1 == pass2 && pass1 != '' && pass2 != '') {
        jQuery('#password').css({ background: 'lightblue' });
        jQuery('#password2').css({ background: 'lightblue' });
    } else {
        jQuery('#password').css({ background: '#fff' });
        jQuery('#password2').css({ background: '#fff' });
    }

    var length = jQuery('#password').val().length;
    var new_length = l_factor * length + l_factor;

    if (new_length > max_length) {
        new_length = max_length;
    }

    var new_color = g_factor * length;

    if (pass1 == '') {
        new_length = 0;
    }

    jQuery('#password_verification').css({ width: new_length + 'px' });
    jQuery('#password_verification').css({ background: 'rgb( ' + (255 - 20 * length) + ', ' + new_color + ', 0 )' });
}

function mailTest()
{
	var pass1 = jQuery('#email').val();
    var pass2 = jQuery('#email2').val();

    if (pass1 == pass2 && pass1 != '' && pass2 != '') {
        jQuery('#email').css({ background: 'lightblue' });
        jQuery('#email2').css({ background: 'lightblue' });
    } else {
        jQuery('#email').css({ background: '#fff' });
        jQuery('#email2').css({ background: '#fff' });
    }
}

$(document).ready(function() {
	$('#sidebar').accordion({
		header: 'h3'
	});
})
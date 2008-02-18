/**
* @desc Initialize objects, create DB link, load templates, clean input
*
* PHP versions 5.1.4
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
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @author     Florian Wolf <xsign.dll@clansuite.com>
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @license    see COPYING.txt
* @version    SVN: $Id$
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/

function passTest()
{
	l_factor = 5;
    g_factor = 20;
    max_length = 100;
    reg_ex = /^[\w]*$/;
    if( reg_ex.test(document.getElementById('password').value) )
    { 
        l_factor = 4;
        g_factor = 20;    
    }
    else
    {
        l_factor = 9;
        g_factor = 30;    
    }
    
    pass1 = document.getElementById('password').value;
    pass2 = document.getElementById('password2').value;
    
    if( pass1 == pass2 && pass1!='' && pass2!='' )
    {
        document.getElementById('password').style.background = 'lightblue';
        document.getElementById('password2').style.background = 'lightblue';
    }
    else
    {
        document.getElementById('password').style.background = 'white';
        document.getElementById('password2').style.background = 'white';    
    }
    
    length = document.getElementById('password').value.length;
    new_length = l_factor*length+l_factor;
    
    if( new_length > max_length )
    { new_length = max_length; }
    new_color = g_factor*length;
    
    if( pass1=='' )
    {
        new_length = 0;
    }

    document.getElementById('password_verification').style.width = new_length+'px';
    document.getElementById('password_verification').style.background = 'rgb( '+(255-20*length)+', '+new_color+', 0 )';
}

function mailTest()
{
	pass1 = document.getElementById('email').value;
    pass2 = document.getElementById('email2').value;
    if( pass1 == pass2 && pass1!='' && pass2!='' )
    {
        document.getElementById('email').style.background = 'lightblue';
        document.getElementById('email2').style.background = 'lightblue';
    }
    else
    {
        document.getElementById('email').style.background = 'white';
        document.getElementById('email2').style.background = 'white';
    }
}
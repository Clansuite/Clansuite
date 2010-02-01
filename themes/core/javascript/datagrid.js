/**
* Clansuite - just an eSports CMS
* Jens-André Koch © 2005 - onwards
* http://www.clansuite.com/
*
* This file is part of "Clansuite - just an eSports CMS".
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
*
* @author     Jens-André Koch   <vain@clansuite.com>
* @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
*
* @link       http://www.clansuite.com
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 2.0alpha
*
* @version    SVN: $Id$
*/

/**
* Purpose:
* Supply jQuery functions for the clansuite datagrid
*
* @author Florian Wolf <xsign.dll@clansuite.com>
*/


/**
* Colorize Rows
*/
$(document).ready(function() {
    // Hovers
    $(".DatagridRow").mouseover( function() {
        $(this).toggleClass('DatagridRowHover');
    }).mouseout( function() {
        $(this).toggleClass('DatagridRowHover');
    });


    // Row Clicks
    $(".DatagridRow").click( function(e) {

        // Set color
        $(this).toggleClass('DatagridRowSelected');

        // Toggle checkboxes
        var Checkbox = $(".DatagridCheckbox", $(this))[0];

        if( Checkbox && (Checkbox != e.target) )
        {
            oCheckbox = $(Checkbox);

            if( oCheckbox.attr('checked') )
            {
                $(this).removeClass('DatagridRowSelected');
                oCheckbox.removeAttr('checked');
            }
            else
            {
                $(this).addClass('DatagridRowSelected');
                oCheckbox.attr('checked', 'checked');
            }
        }
    });

    // Checkbox changes
    $(".DatagridCheckbox").change( function(e) {
       if($(this).attr('checked'))
       {
           $(this).parents(".DatagridRow").addClass('DatagridRowSelected');
       }
       else
       {
           $(this).parents(".DatagridRow").removeClass('DatagridRowSelected');
       }
    });

    // Select all click
    $(".DatagridSelectAll").click( function(e) {

        if( $(this).attr('checked') )
        {
            $(".DatagridSelectAll").attr('checked', 'checked');
            $(".DatagridCheckbox").attr('checked', 'checked').change();
            //$(".DatagridCheckbox").change();
        }
        else
        {
            $(".DatagridSelectAll").removeAttr('checked');
            $(".DatagridCheckbox").removeAttr('checked').change();
            //$(".DatagridCheckbox").change();
        }
    });
});
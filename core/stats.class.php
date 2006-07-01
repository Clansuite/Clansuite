<?php
/**
* Stats Handler Class
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
* @version    SVN: $Id: input.class.php 132 2006-06-09 22:47:30Z xsign $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/


//----------------------------------------------------------------
// Security Handler
//----------------------------------------------------------------
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

//----------------------------------------------------------------
// Start stats class
//----------------------------------------------------------------
class stats
{
    //----------------------------------------------------------------
    // Create the needed variables and assign them to the tpl
    //----------------------------------------------------------------
    function create_stats_vars()
    {
        global $tracker, $tpl;
        
        $all_impressions = $tracker->get( array('api_call'  => 'page_impressions',
                                                'range'     => 'total' ) );
        
        $page_impressions = $tracker->get(array('api_call'  => 'page_impressions',
                                                'range'     => 'total',
                                                'constraints' => array('document' => $_SESSION['_phpOpenTracker_Container']['document_url'] ) ) );
        
        $online = $tracker->get( array( 'api_call'  => 'visits',
                                        'start'     => time()-300,
                                        'end'       => time(),
                                        'interval'  => 3600 ) );
        
        $tpl->assign('stats_online'             , $online );
        $tpl->assign('stats_all_impressions'    , $all_impressions );
        $tpl->assign('stats_page_impressions'   , $page_impressions );
    }
}
?>
<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         calendar.module.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Module Class - calendar
    *               Calendar with Eventmanagement
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
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

/**
 *  Security Handler
 */
if (!defined('IN_CS')) { die('You are not allowed to view this page.'); }

/**
 * This is the Clansuite Module Class - module_calendar
 *
 * Description:  Calendar with Eventmanagement
 *
 * @author     Jens-Andre Koch
 * @copyright  Jens-Andre Koch, 2005-2007
 * @link       http://www.clansuite.com
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    module
 * @subpackage  module_calendar
 */
class module_calendar
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
     * @desc General Function Hook of calendar-Modul
     *
     * 1. Set Pagetitle and Breadcrumbs
     * 2. $_REQUEST['action'] determines the switch
     * 3. function title is added to page title, to complete the title
     * 4. switch-functions are called
     *
     * @return: array ( OUTPUT, ADDITIONAL_HEAD, SUPPRESS_WRAPPER )
     */

    function auto_run()
    {

        global $lang, $trail, $perms;
        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Calendar'), 'index.php?mod=calendar');

        //
        switch ($_REQUEST['action'])
        {

            default:
            case 'show':
                $trail->addStep($lang->t('Show'), 'index.php?mod=calendar&amp;action=show');
                $this->show();
                break;

            case 'ajax_getdateinfos':
                $this->ajax_getdateinfos();
                break;

            case 'instant_show':
                $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
                break;

        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
     * Action -> Show
     * Direct Call by URL/index.php?mod=calendar&action=show
     *
     * @global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;
     */

    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;

         // Add $lang-t() translated text to the output.
        $this->output .= $lang->t('You have created a new module, that currently handles this message');
    }

  
    /**
     *  Ajax Function to update the jsCalendar dateInfo array
     */
    function ajax_getdateinfos()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;

        // whatever happens -> suppress the mainframe
        $this->suppress_wrapper = true;

        // get input values month and year       
        $month = (int) $_GET['month'];
        $year  = (int) $_GET['year'];

        /**
         *  jscalendar uses month=0=januar, so we have to increase month++
         *  this is now handled in javascript
         */
        // $month++;

        // get calendardata by month and year from db
        $stmt = $db->prepare('SELECT * FROM '. DB_PREFIX .'calendar WHERE year = ? AND month = ? ORDER BY day ASC');
        $stmt->execute( array ( $year, $month ) );
        $caldata = $stmt->fetchAll(PDO::FETCH_NAMED);
        #var_dump($caldata);
       
        /**
         * Reconstruct the Array 
         *
         * 1. combined date
         * 2. eventindex
         * 3. data for eventindex
         *
         * [yearmonthday][id] = array([eventname][description][link])        
         *
         * That will look like:
         *
         * array
         *   20070419 => 
         *       array
         *        1 => 
         *           array
         *             'eventname' => string 'birthday' (length=6)
         *             'description' => string 'birthday' (length=5)
         *             'link' => string 'birthday' (length=6)
         *         2 => 
         *           array
         *             'eventname' => string 'going to a party' (length=16)
         *             'description' => string 'going to a party' (length=18)
         *             'link' => string 'going to a party' (length=16)
         *
         *
         *
         * @todo: check if the array reconstuction/reassignments could be optimized!
         */
        for($x=0;$x<count($caldata);$x++){
        
            // add the missing zero to month
            if ($caldata[$x][month] < 10) { $caldata[$x][month] = '0'.$caldata[$x][month]; }
            
            // combine the date y+m+d
            $combined_date = $caldata[$x][year].$caldata[$x][month].$caldata[$x][day];
        
            // count if there are elements in this array?
            // if so, increase the index (i) for the subarray 
            // else no subelements (subarray of combined_date=0) present and set $i to 1 for first entry
            if ( count($restructured_caldata[$combined_date]) > 0 )
            {
             $i++;  
            }
            else
            {
             $i = 1;
            }
            
            // datas for event from $caldata[$x]
            $infos = array( 'eventname'      => $caldata[$x][eventname],
                            'description'    => $caldata[$x][description],
                            'link'           => $caldata[$x][link]
                           );
            
            // Finally stick all 3 parts together
            // 1. pos is combined date
            // 2. pos is subarray id $i
            // 3. assign $infos 
            $restructured_caldata[$combined_date][$i] = $infos;
        }            
        #var_dump($restructured_caldata);
        
        // check if we got an restructured calendar dates array
        if(is_array($restructured_caldata))
        {   
            //clear output array
            $json_dateInfos = array();
         
            // Check if an output format was requested, and if it was XML
            // Return JSON output by default
            if( isset($_GET['output']) && strcasecmp($_GET['output'], 'xml') == 0 ) 
            {   
                var_dump($restructured_caldata);
                // Send our XML content-type header
                header('Content-type: text/xml');
                // And print out our formatted response
                echo xmlrpc_encode_request(null,$restructured_caldata);
            }
            else
            {   
                /**
                 * Notice by vain:
                 * That nice json_encode support is avaiable from php v5.2
                 * and then only when extension is installed!
                 * Up to this version we have to use a external json-parser class.
                 */
                if (PHPVERSION() < 5.2 && !extension_loaded('json'))
                {
                    // create a new instance of Services_JSON
                    require_once( ROOT_CORE . '/json/JSON.php');
                    $json = new Services_JSON();
                   
                    $json_dateInfos = $json->encode($restructured_caldata);
                }
                else
                {
                    // php 5.2
                    $json_dateInfos = json_encode($restructured_caldata);   
                }
                
                // return the json array !!
                #print_r($json_dateInfos);  
                header('Content-type: application/x-json');
                //encode and return json data...
                echo $json_dateInfos;                
            }
         }
   }

    /**
     * Instant Show
     *
     * Content of a module can be instantly displayed by adding the
     * {mod name="calendar" sub="admin" func="instant_show" params="mytext"}
     * block into a template.
     *
     * You have to add the lines as shown above into the case block:
     * $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
     *
     * @global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users
    */

    function instant_show($my_text)
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms, $users;

        // Add $lang-t() translated text to the output.
        $this->output .= $lang->t($my_text);
    }
}
?>
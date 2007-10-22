<?php
/**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         benchmark.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Benchmarking
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
    
//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Class Benchmark
 * @author Boots @ Smarty Forum
 * @copyright Boots, 2003
 *
 * Benchmark implements the Timemarker Functionality
 *
 * Example Usage:
 *
 * php
 *       begin of php
 *       include_once 'benchmark.class.php';
 *       benchmark::timemarker('begin', 'Clansuite Initialization');
 *       assign function to smarty
 *       $tpl->register_modifier('timemarker',  array('benchmark', 'timemarker'));
 *
 *       end of php
 *       benchmark::timemarker('list'); echos a history of timemarkers
 *       DEBUG ? benchmark::timemarker('list') : ''; # returns a history of timemarkers in case of debug
 *
 * tpl
 *
 *       start a new timemarker with name "template block"
 *       {"begin"|timemarker:"template block"}
 *
 *       will print the timemarker name and formatted time of it
 *       {"end"|timemarker:true}
 *
 *       start two timemarkers
 *       {"begin"|timemarker:"level2"}
 *       {"begin"|timemarker:"level3"}
 *
 *       stop all your timemarkers at once using the 'stop' action. this will stop also php-started timers
 *       {"stop"|timemarker:true|nl2br}
 *
 */
class benchmark
{   
    # overall db query exectime
    public static $db_exectime = 0;
    # counter registering the numbers of used timemarkers
    public static $timers_counter = 0;
    
    # array for db timers
    public static $db_timer_blocks = array();
    # array for normal timers   
    public $timer_blocks = array();
    
    # history array
    public static $db_timer_histroy = array();
    public static $timer_history = array();
    
    public static function returnDbexectime()
    {
        return self::$db_exectime;   
    }
         
    public static function timemarker($mode='begin', $msg='')
    { 
        switch ($mode)
        {
            /**
             *  Database Timemarkers - db_begin, db_end, db_list
             */
             
            case 'db_begin':
                            $time = time()+microtime();
                            $db_timer_blocks[] = array( $time , $msg);
                            ++self::$timers_counter;
                            #var_dump($db_timer_blocks);
                            break;

            case 'db_end':
                            $last = array_pop(self::$db_timer_blocks);
                            $_starttime = $last[0];
                            $_msg = $last[1];

                            # $elapsed contains the calculated difference of Start to Endtime
                            $elapsed = (round((time() + microtime()) - $_starttime, 4));
                            
                            $db_timer_history[] += $elapsed;
                            
                            $db_exectime = $db_timer_history[0][1];
                            
                            $db_timer_history[] = array( $elapsed, "$_msg [". $elapsed ."s]");

                            // add elapsed time to db overall counter


                            if ($msg)
                            {
                                return "$_msg: {$elapsed}s \n";

                            }
                            return $elapsed;
                            break;

             case 'db_list':

                            $o = '<strong>Database Timemarkers History:</strong> <br />';
                            if(isset($db_timer_history) && !empty($db_timer_history))
                            {

                                foreach ($db_timer_history as $mark)
                                {
                                    $o .= $mark[1] . " \n <br />";

                                }
                                echo $o;
                            }

                            #var_dump($db_timer_history);
                            break;

            /**
             *  Standard Timemarkers - begin, end, list, stop
             */
            default:
            case 'begin':
                            $timer_blocks[] = array(time()+microtime(), $msg);
                            var_dump($timer_blocks);
                            break;

            case 'end':    
                            var_dump($timer_blocks);
                            $last = array_pop(self::$timer_blocks);
                            $_starttime = $last[0];
                            $_msg = $last[1];                            

                            $elapsed = (round((time() + microtime()) - $_starttime, 4));

                            /**
                             * Now this is entered into the $_timmer_history array.
                             * And displayed in the following manner: [0.0022s 454.55/s]
                             *
                             * First: the elapsed time in seconds (known as the period)
                             * Second: how many times that First element can occur
                             *         sequentially in a single second (known as the frequency)
                             * Means with example above: 0.0022 Seconds * 454.55 Times = 1 Second
                             *
                             * :) - Strange but true - So... What's the Frequency, Kenneth?
                             */
                            var_dump($timer_history);
                            $timer_history[] = array($elapsed, $_msg, "$_msg [". $elapsed ."s ".(round(1/$elapsed, 2))."/s]");


                            if ($msg) 
                            {
                            return "$_msg: {$elapsed}s \n";
                            }
                            return $elapsed; 

                            break;

            case 'list':
                            $o = '<strong>Timemarkers History:</strong> <br />';
                            if(isset($timer_history) && !empty($timer_history))
                            {
                                foreach ($timer_history as $mark)
                                {
                                    $o .= $mark[2] . "\n <br />";
                                }
                                echo $o;
                            }

                            var_dump($timer_history);
                            break;

            case 'stop':
                            $result = '';
                            while(!empty($timer_blocks))
                            {
                                $result .= benchmark::timemarker('end', $msg);
                            }

                            return $result;
                            break;
        }

    }
}
?>
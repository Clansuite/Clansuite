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
    function timemarker($mode='begin', $msg='')
    {
        global $_timer_blocks, $_timer_history; 
        
        switch ($mode) 
        {
            case 'begin':
                            $_timer_blocks[] =array(microtime(), $msg);
                            break;
    
            case 'end':
                            $last = array_pop($_timer_blocks);
                            $_start = $last[0];
                            $_msg = $last[1];
                            list($a_micro, $a_int) = explode(' ', $_start);
                            list($b_micro, $b_int) = explode(' ', microtime());
                            $elapsed = ($b_int - $a_int) + ($b_micro - $a_micro);
                            $_timer_history[] = array($elapsed, $_msg, "$_msg [".round($elapsed, 4)."s ".(round(1/$elapsed, 2))."/s]");
                            
                            if ($msg) 
                            {
                                return "$_msg: {$elapsed}s \n";
                            }
                            
                            return $elapsed;
                            break;
    
            case 'list':
            
                            $o = 'Timemarkers History: ';
                            if(isset($_timer_history) && !empty($_timer_history))
                            {
                                foreach ($_timer_history as $mark) 
                                {
                                    $o .= $mark[2] . " \n";
                                }
                                echo $o;
                            }
                            break;
    
            case 'stop':
                        
                            $result = '';
                            while(!empty($_timer_blocks)) 
                            {
                                $result .= benchmark::timemarker('end', $msg);
                            }
                
                            return $result;
                            break;
        }
    
    }
}
?>
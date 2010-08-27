<?php
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Core Class for the Initialization of the phpDoctrine Profiler
 * Its an debugging utility.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Doctrine
 */
class Clansuite_Doctrine_Profiler
{
    public function __construct()
    {
        $this->connection = Doctrine_Manager::connection();
    }
    
    /**
     * Returns Doctrine's connection profiler
     *
     * @return Doctrine_Connection_Profiler
     */
    public function getProfiler()
    {
        return $this->connection->getListener();
    }

    /**
     * Attached the Doctrine Profiler as Listener to the connection
     */
    public function attachProfiler()
    {
        # instantiate Profiler and attach to doctrine connection
        $this->connection->setListener(new Doctrine_Connection_Profiler);
        
        register_shutdown_function(array($this,'shutdown'));  
    }

    /**
     * Displayes all Doctrine Querys with profiling Informations
     *
     * Because this is debug output, it's ok that direct output breaks the abstraction.
     * @return Direct HTML Output
     */
    public function displayProfilingHTML()
    {
        /**
         * @var int total number of database queries performed
         */
        $query_counter = 0;
        /**
         * @var int time in seconds, counting the elapsed time for all queries
         */
        $time = 0;


        echo '<!-- Disable Debug Mode to remove this!-->
              <style type="text/css">
              /*<![CDATA[*/
                table.doctrine-profiler {
                    background: none repeat scroll 0 0 #FFFFCC;
                    border-width: 1px;
                    border-style: outset;
                    border-color: #BF0000;
                    border-collapse: collapse;
                    font-size: 11px;
                    color: #222;
                 }
                table.doctrine-profiler th {
                    border:1px inset #BF0000;
                    padding: 3px;
                    padding-bottom: 3px;
                    font-weight: bold;
                    background: #E03937;
                }
                table.doctrine-profiler td {
                    border:1px inset grey;
                    padding: 2px;
                }
                table.doctrine-profiler tr:hover {
                    background: #ffff88;
                }
                fieldset.doctrine-profiler legend {
                    background:#fff;
                    border:1px solid #333;
                    font-weight:700;
                    padding:2px 15px;
                }
                /*]]>*/
                </style>';

        echo '<p>&nbsp;</p><fieldset class="doctrine-profiler"><legend>Debug Console for Doctrine Queries</legend>';
        echo '<table class="doctrine-profiler" width="95%">';
        echo '<tr>
                <th>Query Counter</th>
                <th>Command</th>
                <th>Time</th>
                <th>Query with placeholder (?) for parameters</th>
                <th>Parameters</th>
              </tr>';

        foreach ( $this->getProfiler() as $event )
        {
            /**
             * By activiating the following lines, only the "execute" queries are shown.
             * It's usefull for debugging a certain type of database statement.
             */
            /*
            if ($event->getName() != 'execute')
            {
                continue;
            }
            */

            # increase query counter
            $query_counter++;

            # increase time
            $time += $event->getElapsedSecs();

            echo '<tr>';
            echo '<td style="text-align: center;">' . $query_counter . '</td>';
            echo '<td style="text-align: center;">' . $event->getName() . '</td>';
            echo '<td>' . sprintf('%f', $event->getElapsedSecs() ) . '</td>';
            echo '<td>' . $event->getQuery() . '</td>';
            $params = $event->getParams();
            if ( empty($params) == false)
            {
                  echo '<td>';
                  echo wordwrap(join(', ', $params),150,"\n",true);
                  echo '</td>';
            }
            else
            {
                  echo '<td>';
                  echo '&nbsp;';
                  echo '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        echo '<p style="font-weight: bold;">&nbsp; &raquo; &nbsp; '.$query_counter.' statements in ' . sprintf('%2.5f', $time) . ' secs.</p>';
        echo '</fieldset>';
    }

    /**
     * shutdown function for register_shutdown_function
     */
    public function shutdown()
    {
        # append Doctrine's SQL-Profiling Report
        $this->displayProfilingHTML();

        # save session before exit
        session_write_close();
    }
}
?>
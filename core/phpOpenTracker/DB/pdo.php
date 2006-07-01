<?php
//----------------------------------------------------------------
//
// phpOpenTracker - The Website Traffic and Visitor Analysis Solution
//
// Copyright 2000 - 2005 Sebastian Bergmann. All rights reserved.
//
// Licensed under the Apache License, Version 2.0(the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//   http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//
//----------------------------------------------------------------

/**
* @author     Jens-André Koch <vain@clansuite.com>
* @author     Florian Wolf <xsign.dll@clansuite.com>
* @copyright  Copyright &copy; 2006 Clansuite Group
* @license    see COPYING.txt
* @version    SVN: $Id $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/
class phpOpenTracker_DB_pdo extends phpOpenTracker_DB
{
    //----------------------------------------------------------------
    // Contructor
    //----------------------------------------------------------------
    function phpOpenTracker_DB_pdo()
    {
        $this->phpOpenTracker_DB();
    }
    
    //----------------------------------------------------------------
    // Debug handler (removed)
    //----------------------------------------------------------------
    function debugQuery($query) { }

    //----------------------------------------------------------------
    // Fetch a row
    //----------------------------------------------------------------
    function fetchRow()
    {
        global $db;
        
        $row = $this->result->fetch(PDO::FETCH_ASSOC);
        $this->result->closeCursor();
        
        if (is_array($row))
        {
            return $row;
        }
        
        return false;
    }

    //----------------------------------------------------------------
    // Performs an SQL Query
    //----------------------------------------------------------------
    function query($query, $limit = false, $warnOnFailure = true)
    {
        global $db;
        
        if ($limit != false)
        {
            $query .= ' LIMIT ' . $limit;
        }
        
        if ($this->config['debug_level'] > 1)
        {
            $this->debugQuery($query);
        }
        
        $query = preg_replace("/(\r\n)+/", '', $query);
        
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_SILENT);
        $this->result = $db->prepare($query);
        $this->result->execute();
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        if (!$this->result && $warnOnFailure)
        {
            phpOpenTracker::handleError('SQL ERROR:' . $query,
            E_USER_ERROR
            );
        }
    }
    
    //----------------------------------------------------------------
    // Escapes a Querystring
    //----------------------------------------------------------------
    function escapeString($string)
    {
        global $db;
        
        return addslashes($string);
    }
    
    //----------------------------------------------------------------
    // Nested Queries (returns always false)
    //----------------------------------------------------------------
    function supportsNestedQueries()
    {
        return false;
    }
    
    //----------------------------------------------------------------
    // Get the microtime
    //----------------------------------------------------------------
    function _getMicrotime()
    {
        $microtime = explode(' ', microtime());
        return $microtime[1] . substr($microtime[0], 1);
    }
    
    //----------------------------------------------------------------
    // Get the elapsed time
    //----------------------------------------------------------------
    function _getTimeElapsed($start, $end)
    {
        if (function_exists('bcsub'))
        {
            return bcsub($end, $start, 12);
        }
        else
        {
            return $end - $start;
        }
    }
}
?>
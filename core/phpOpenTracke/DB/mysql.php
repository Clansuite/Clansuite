<?php
//
// phpOpenTracker - The Website Traffic and Visitor Analysis Solution
//
// Copyright 2000 - 2005 Sebastian Bergmann. All rights reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
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

/**
 * phpOpenTracker MySQL Database Handler
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class phpOpenTracker_DB_mysql extends phpOpenTracker_DB {
  /**
  * Constructor.
  *
  * @access public
  */
  function phpOpenTracker_DB_mysql() {
    $this->phpOpenTracker_DB();

    $connectionString = sprintf(
      '%s:%s',

      $this->config['db_host'],
      (($this->config['db_port'] == 'default') ? '3306' : $this->config['db_port'])
    );

    if ($this->config['db_socket'] != 'default') {
      $connectionString .= ':' . $this->config['db_socket'];
    }

    $this->connection = @mysql_connect(
      $connectionString,
      $this->config['db_user'],
      $this->config['db_password'],
      true
    );

    if (!$this->connection ||
        !@mysql_select_db($this->config['db_database'], $this->connection)) {
      return phpOpenTracker::handleError(
        'Could not connect to database.',
        E_USER_ERROR
      );
    }
  }

  /**
  * Prints debug information for an SQL query.
  *
  * @param  string  $query
  * @access public
  */
  function debugQuery($query) {
    if ($explainQuery = stristr($query, 'SELECT')) {
      $start       = $this->_getMicrotime();
      $result      = @mysql_query('EXPLAIN ' . $explainQuery, $this->connection);
      $timeElapsed = $this->_getTimeElapsed($start, $this->_getMicrotime());

      while ($row = @mysql_fetch_assoc($result)) {
        $explain[] = $row;
      }
    } else {
      $timeElapsed = '&nbsp;';
    }

    $debugQuery  = explode("\n", $query);

    for ($i = 0; $i < sizeof($debugQuery); $i++) {
      $debugQuery[$i] = trim($debugQuery[$i]);
    }

    $debugQuery = implode("\n", $debugQuery);

    printf(
      '<table border="1" width="100%%"><tr><td valign="top">%d</td><td valign="top">%s</td><td valign="top" colspan="%d"><pre>%s</pre></td></tr>',

      ++$this->numQueries,
      $timeElapsed,
      isset($explain[0]) ? sizeof($explain[0]) : 1,
      $debugQuery
    );

    if (isset($explain)) {
      foreach ($explain as $row) {
        print '<tr><td>&nbsp;</td>';

        if (isset($row['Comment'])) {
          print '<td>' . $row['Comment'] . '</td>';
        } else {
          foreach ($row as $field => $value) {
            printf(
              '<td valign="top">%s:<br /><nobr>%s</nobr></td>',

              $field,
              $value
            );
          }
        }

        print '</tr>';
      }
    }

    print '</table>';
  }

  /**
  * Fetches a row from the current result set.
  *
  * @access public
  * @return array
  */
  function fetchRow() {
    $row = @mysql_fetch_assoc($this->result);

    if (is_array($row)) {
      return $row;
    }

    return false;
  }

  /**
  * Performs an SQL query.
  *
  * @param  string  $query
  * @param  mixed   $limit
  * @param  boolean $warnOnFailure
  * @access public
  */
  function query($query, $limit = false, $warnOnFailure = true) {
    if ($limit != false) {
      $query .= ' LIMIT ' . $limit;
    }

    if ($this->config['debug_level'] > 1) {
      $this->debugQuery($query);
    }

    @mysql_free_result($this->result);
    $this->result = @mysql_unbuffered_query($query, $this->connection);

    if (!$this->result && $warnOnFailure) {
      phpOpenTracker::handleError(
        @mysql_error($this->connection),
        E_USER_ERROR
      );
    }
  }

  /**
  * Escapes the string for an SQL query.
  *
  * @param  string $string
  * @return string
  * @access public
  * @since  1.5.1
  */
  function escapeString($string) {
    if (function_exists('mysql_real_escape_string')) {
      return mysql_real_escape_string($string, $this->connection);
    } else {
      return mysql_escape_string($string);
    }
  }

  /**
  * Returns TRUE if the database supports nested queries
  * and FALSE otherwise.
  *
  * @return boolean
  * @access public
  * @since  phpOpenTracker 1.1.0
  */
  function supportsNestedQueries() {
    if (substr(mysql_get_server_info($this->connection), 0, 3) >= 4.1) {
      return true;
    }

    return false;
  }

  /**
  * @return float
  * @access private
  * @since  phpOpenTracker 1.3.0
  */
  function _getMicrotime() {
    $microtime = explode(' ', microtime());
    return $microtime[1] . substr($microtime[0], 1);
  }

  /**
  * @return float
  * @access private
  * @since  phpOpenTracker 1.3.0
  */
  function _getTimeElapsed($start, $end) {
    if (function_exists('bcsub')) {
      return bcsub($end, $start, 12);
    } else {
      return $end - $start;
    }
  }
}

?>

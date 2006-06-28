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

require POT_INCLUDE_PATH . 'DB/mysql.php';

/**
 * phpOpenTracker MySQL Database Handler (using Merge Tables)
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.2.0
 */
class phpOpenTracker_DB_mysql_merge extends phpOpenTracker_DB_mysql {
  /**
  * Schema for the pot_accesslog table.
  *
  * @var    string
  * @access private
  */
  var $_accesslogSchema =
      "CREATE %s TABLE %s %s (
         accesslog_id      INT(11)                NOT NULL,
         timestamp         INT(10)    UNSIGNED    NOT NULL,
         weekday           TINYINT(1) UNSIGNED    NOT NULL,
         `hour`            TINYINT(2) UNSIGNED    NOT NULL,
         document_id       INT(11)                NOT NULL,
         exit_target_id    INT(11)    DEFAULT '0' NOT NULL,
         entry_document    TINYINT(1) UNSIGNED    NOT NULL,

         KEY accesslog_id   (accesslog_id),
         KEY timestamp      (timestamp),
         KEY document_id    (document_id),
         KEY exit_target_id (exit_target_id)
       ) %s;";

  /**
  * Schema for the pot_visitors table.
  *
  * @var    string
  * @access private
  */
  var $_visitorsSchema =
      "CREATE %s TABLE %s %s (
         accesslog_id        INT(11)             NOT NULL,
         visitor_id          INT(11)             NOT NULL,
         client_id           INT(10)    UNSIGNED NOT NULL,
         operating_system_id INT(11)             NOT NULL,
         user_agent_id       INT(11)             NOT NULL,
         host_id             INT(11)             NOT NULL,
         referer_id          INT(11)             NOT NULL,
         timestamp           INT(10)    UNSIGNED NOT NULL,
         weekday             TINYINT(1) UNSIGNED NOT NULL,
         `hour`              TINYINT(2) UNSIGNED NOT NULL,
         returning_visitor   TINYINT(1) UNSIGNED NOT NULL,

         PRIMARY KEY         (accesslog_id),
         KEY client_time     (client_id, timestamp)
       ) %s;";

  /**
  * Performs an SQL query.
  *
  * @param  string  $query
  * @param  mixed   $limit
  * @param  boolean $warnOnFailure
  * @param  boolean $tablesAlreadyCreated
  * @access public
  */
  function query($query, $limit = false, $warnOnFailure = true, $tablesAlreadyCreated = false) {
    if ($this->isManip($query)) {
      if (!$tablesAlreadyCreated) {
        $query = $this->_replaceTableNames($query);
      }

      if (isset($this->result) && is_resource($this->result)) {
        @mysql_free_result($this->result);
      }

      if ($this->config['debug_level'] > 1) {
        $this->debugQuery($query);
      }

      $this->result = @mysql_query($query, $this->connection);

      if (!$this->result) {
        $throwError = $warnOnFailure ? true : false;

        if (!$tablesAlreadyCreated &&
            mysql_errno($this->connection) == 1146) {
          $this->_createNewTables();
          $this->query($query, $limit, $warnOnFailure, true);
          $throwError = false;
        }

        if ($throwError) {
          phpOpenTracker::handleError(
            @mysql_error($this->connection),
            E_USER_ERROR
          );
        }
      }
    } else {
      $query = $this->_rewriteSelectQuery($query);

      if ($limit != false) {
        $query .= ' LIMIT ' . $limit;
      }

      parent::query($query);
    }
  }

  /**
  * Alters an existing merge table.
  *
  * @param  string  $name
  * @access private
  */
  function _alterMergeTable($name) {
    parent::query(
      sprintf(
        'ALTER TABLE %s UNION=(%s)',

        $name,
        $this->_getMergeTables($name)
      )
    );
  }

  /**
  * Creates a merge table.
  *
  * @param  string  $name
  * @param  string  $type
  * @param  array   $tables
  * @param  boolean $mayExist
  * @param  boolean $temporary
  * @access private
  */
  function _createMergeTable($name, $type, $tables, $mayExist = true, $temporary = false) {
    if ($mayExist && $this->_tableExists($name)) {
      $this->_alterMergeTable($name);
      return;
    }

    if ($temporary) {
      parent::query(
        sprintf(
          'DROP TABLE IF EXISTS %s',

          $name
        )
      );
    }

    parent::query(
      sprintf(
        ($type == 'accesslog') ? $this->_accesslogSchema : $this->_visitorsSchema,
        $temporary  ? 'TEMPORARY'     : '',
        !$temporary ? 'IF NOT EXISTS' : '',
        $name,
        'TYPE=MRG_MyISAM UNION=(' . implode(',', $tables) . ')'
      )
    );
  }

  /**
  * Creates new pot_accesslog and pot_visitors tables
  * to accomodate INSERTs for the current month
  * (when in "day" mode) or the current year (when running
  * in "month" mode).
  *
  * @access private
  */
  function _createNewTables() {
    $accesslogTables = array();
    $visitorsTables  = array();

    $currentMonth = date('n');
    $currentYear  = date('Y');

    switch ($this->config['merge_tables_mode']) {
      case 'day': {
        $numDaysInCurrentMonth = $this->_numDaysInMonth($currentMonth, $currentYear);

        for ($day = 1; $day <= $numDaysInCurrentMonth; $day++) {
          $newAccesslogTable = $this->_getMergeTableName(
            'accesslog',
            $currentYear,
            $currentMonth,
            $day
          );

          $accesslogTables[] = $newAccesslogTable;

          $this->_createNewTable(
            'accesslog',
            $newAccesslogTable
          );

          $newVisitorsTable = $this->_getMergeTableName(
            'visitors',
            $currentYear,
            $currentMonth,
            $day
          );

          $visitorsTables[] = $newVisitorsTable;

          $this->_createNewTable(
            'visitors',
            $newVisitorsTable
          );
        }

        // Create merge tables for current month.
        $this->_createMergeTable(
          $this->_getMergeTableName(
            'accesslog',
            $currentYear,
            $currentMonth
          ),
          'accesslog',
          $accesslogTables,
          false
        );

        $this->_createMergeTable(
          $this->_getMergeTableName(
            'visitors',
            $currentYear,
            $currentMonth
          ),
          'visitors',
          $visitorsTables,
          false
        );
      }
      break;

      case 'month': {
        for ($month = $currentMonth; $month <= 12; $month++) {
          $newAccesslogTable = $this->_getMergeTableName(
            'accesslog',
            $currentYear,
            $month
          );

          $accesslogTables[] = $newAccesslogTable;

          $this->_createNewTable(
            'accesslog',
            $newAccesslogTable
          );

          $newVisitorsTable = $this->_getMergeTableName(
            'visitors',
            $currentYear,
            $month
          );

          $visitorsTables[] = $newVisitorsTable;

          $this->_createNewTable(
            'visitors',
            $newVisitorsTable
          );
        }
      }
      break;
    }

    $this->_createMergeTable(
      $this->config['accesslog_table'],
      'accesslog',
      $accesslogTables
    );

    $this->_createMergeTable(
      $this->config['visitors_table'],
      'visitors',
      $visitorsTables
    );
  }

  /**
  * Helper method for _createNewTables().
  *
  * @param  string  $type
  * @param  string  $name
  * @access private
  * @see _createNewTables()
  */
  function _createNewTable($type, $name) {
    parent::query(
      sprintf(
        ($type == 'accesslog') ? $this->_accesslogSchema : $this->_visitorsSchema,
        '',
        'IF NOT EXISTS',
        $name,
        ($type == 'accesslog') ? 'TYPE=MyISAM DELAY_KEY_WRITE=1 PACK_KEYS=1' : 'TYPE=MyISAM DELAY_KEY_WRITE=1'
      )
    );
  }

  /**
  * Returns the first field of the next row.
  *
  * @return string
  * @access private
  */
  function _fetchFirstField() {
    if (is_resource($this->result)) {
      $row = @mysql_fetch_row($this->result);

      if (is_array($row)) {
        return $row[0];
      }
    }

    return false;
  }

  /**
  * Returns the name of a merge table.
  *
  * @param  string  $type
  * @param  integer $year
  * @param  integer $month
  * @param  integer $day
  * @return string
  * @access private
  */
  function _getMergeTableName($type, $year, $month = false, $day = false) {
    $name = sprintf(
      '%s_%d',

      ($type == 'accesslog') ? $this->config['accesslog_table'] : $this->config['visitors_table'],
      $year
    );

    if ($month !== false) {
      $name .= sprintf(
        '%02d',

        $month
      );
    }

    if ($day !== false) {
      $name .= sprintf(
        '%02d',

        $day
      );
    }

    return $name;
  }

  /**
  * Returns a comma-separated list of existing merge tables.
  *
  * @param  string  $name
  * @return string
  * @access private
  */
  function _getMergeTables($name) {
    $tables = array();

    switch ($this->config['merge_tables_mode']) {
      case 'day': {
        $tableNameLength = strlen($name) + 9;
      }
      break;

      case 'month': {
        $tableNameLength = strlen($name) + 7;
      }
      break;
    }

    parent::query('SHOW TABLES');

    while ($field = $this->_fetchFirstField()) {
      if (strstr($field, $name) &&
          strlen($field) == $tableNameLength) {
        $tables[] = $field;
      }
    }

    sort($tables);

    return implode(',', $tables);
  }

  /**
  * Returns the number of days in a given month.
  *
  * @param  integer $month
  * @param  integer $year
  * @return integer
  * @access private
  */
  function _numDaysInMonth($month, $year) {
    return date(
      't',
      mktime(0, 0, 0, $month, 1, $year)
    );
  }

  /**
  * Replaces the names of the pot_accesslog and pot_visitors
  * merge tables with the ones for a given day or month.
  *
  * @param  string  $query
  * @param  string  $suffix
  * @return string
  * @access private
  */
  function _replaceTableNames($query, $suffix = '') {
    if ($suffix == '') {
      switch ($this->config['merge_tables_mode']) {
        case 'day': {
          $suffix = date('Y') . date('m') . date('d');
        }
        break;

        case 'month': {
          $suffix = date('Y') . date('m');
        }
        break;
      }
    }

    return str_replace(
      array(
        $this->config['accesslog_table'],
        $this->config['visitors_table']
      ),

      array(
        $this->config['accesslog_table'] . '_' . $suffix,
        $this->config['visitors_table']  . '_' . $suffix
      ),

      $query
    );
  }

  /**
  * Rewrites an SQL SELECT query.
  *
  * @param  string $query
  * @return string
  * @access private
  */
  function _rewriteSelectQuery($query) {
    $parsedQuery = explode(' ', $query);
    $between     = array_search('BETWEEN', $parsedQuery);

    if ($between == false) {
      return $query;
    }

    $accesslogTables = array();
    $visitorsTables  = array();

    $startDay   = $day   = date('d', (integer)$parsedQuery[$between + 1]);
    $startMonth = $month = date('m', (integer)$parsedQuery[$between + 1]);
    $startYear  = $year  = date('Y', (integer)$parsedQuery[$between + 1]);
    $endDay              = date('d', (integer)$parsedQuery[$between + 3]);
    $endMonth            = date('m', (integer)$parsedQuery[$between + 3]);
    $endYear             = date('Y', (integer)$parsedQuery[$between + 3]);

    $done                  = false;
    $numDaysInCurrentMonth = $this->_numDaysInMonth($month, $year);

    switch ($this->config['merge_tables_mode']) {
      case 'day': {
        // start/end on the same day: use single day table
        if ($startDay   == $endDay &&
            $startMonth == $endMonth &&
            $startYear  == $endYear) {
          return $this->_replaceTableNames(
            $query,
            $startYear . $startMonth . $startDay
          );
        }

        // month range: use single month table
        if ($startDay   == 1 &&
            $endDay     >= 28 &&
            $startMonth == $endMonth &&
            $startYear  == $endYear) {
          return $this->_replaceTableNames(
            $query,
            $startYear . $startMonth
          );
        }

        while (!$done) {
          $accesslogTables[] = $this->_getMergeTableName(
            'accesslog',
            $year,
            $month,
            $day
          );

          $visitorsTables[] = $this->_getMergeTableName(
            'visitors',
            $year,
            $month,
            $day
          );

          if ($day   == $endDay &&
              $month == $endMonth &&
              $year  == $endYear) {
            $done = true;
          }

          else if ($day == $numDaysInCurrentMonth) {
            $day = 1;

            if ($month < 12) {
              $month++;
            } else {
              $month = 1;
              $year++;
            }

            $numDaysInCurrentMonth = $this->_numDaysInMonth($month, $year);
          }

          else {
            $day++;
          }
        }
      }
      break;

      case 'month': {
        // start/end in same month: use single month table
        if ($startMonth == $endMonth &&
            $startYear  == $endYear) {
          return $this->_replaceTableNames(
            $query,
            $startYear . $startMonth
          );
        }

        while (!$done) {
          $accesslogTables[] = $this->_getMergeTableName(
            'accesslog',
            $year,
            $month
          );

          $visitorsTables[] = $this->_getMergeTableName(
            'visitors',
            $year,
            $month
          );

          if ($month == $endMonth &&
              $year  == $endYear) {
            $done = true;
          }

          else if ($month < 12) {
            $month++;
          } else {
            $month = 1;
            $year++;
          }
        }
      }
      break;
    }

    $this->_createMergeTable(
      $this->config['accesslog_table'] . '_temporary',
      'accesslog',
      $accesslogTables,
      true,
      true
    );

    $this->_createMergeTable(
      $this->config['visitors_table'] . '_temporary',
      'visitors',
      $visitorsTables,
      true,
      true
    );

    return $this->_replaceTableNames(
      $query,
      'temporary'
    );
  }

  /**
  * Checks whether a table exists.
  *
  * @param  string $name
  * @return boolean
  * @access private
  */
  function _tableExists($name) {
    parent::query('SHOW TABLES');

    while ($field = $this->_fetchFirstField()) {
      if ($field == $name) {
        return true;
      }
    }

    return false;
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

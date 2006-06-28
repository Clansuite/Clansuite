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
 * Base Class for phpOpenTracker Database Handlers.
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class phpOpenTracker_DB {
  /**
  * Config
  *
  * @var array $config
  */
  var $config = array();

  /**
  * Connection
  *
  * @var  integer $connection
  */
  var $connection;

  /**
  * Number of performed queries
  *
  * @var  integer $numQueries
  */
  var $numQueries = 0;

  /**
  * Result
  *
  * @var  integer $result
  */
  var $result;

  /**
  * Constructor.
  *
  * @access public
  */
  function phpOpenTracker_DB() {
    $this->config = &phpOpenTracker_Config::getConfig();
  }

  /**
  * Returns an instance of phpOpenTracker_DB.
  *
  * @access public
  * @return phpOpenTracker_DB
  * @static
  */
  function &getInstance() {
    static $db;

    if (!isset($db)) {
      $config  = &phpOpenTracker_Config::getConfig();
      $dbClass = 'phpOpenTracker_DB_' . $config['db_type'];

      if (!@include(POT_INCLUDE_PATH . 'DB/' . $config['db_type'] . '.php')) {
        phpOpenTracker::handleError(
          sprintf(
            'Unknown database handler "%s".',
            $config['db_type']
          ),
          E_USER_ERROR
        );
      }

      $db = new $dbClass;
    }

    return $db;
  }

  /**
  * Prints debug information for an SQL query.
  *
  * @param  string  $query
  * @access public
  */
  function debugQuery($query) {
    printf(
      '<table border="1" width="100%%"><tr><td valign="top" width="50">%s</td><td valign="top"><pre>%s</pre></td></tr>',

      ++$this->numQueries,
      $query
    );
  }

  /**
  * Returns true for manipulating SQL queries.
  *
  * @param  string  $query
  * @return boolean
  * @access public
  * @since  phpOpenTracker 1.5.0
  */
  function isManip($query) {
    if (preg_match('/^\s*"?(INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|ALTER|GRANT|REVOKE|LOCK|UNLOCK)\s+/i', $query)) {
      return true;
    }

    return false;
  }

  /**
  * Stores additional data associated with a given accesslog_id.
  *
  * @param  integer $accesslogID
  * @param  array   $addData
  * @access public
  */
  function storeAddData($accesslogID, $addData) {
    foreach ($addData as $field => $value) {
      $this->query(
        sprintf(
          "INSERT
             INTO %s
                  (accesslog_id,
                   data_field, data_value)
            VALUES(%d,
                   '%s', '%s')",

          $this->config['additional_data_table'],
          $accesslogID,
          $field,
          $value
        )
      );
    }
  }

  /**
  * Stores a string into the database.
  *
  * @param  string $table
  * @param  string $string1
  * @param  string $string2
  * @return integer
  */
  function storeIntoDataTable($table, $string1, $string2 = '') {
    if (empty($string1)) {
      return 0;
    }

    if ($table == $this->config['documents_table']) {
      $urlField = ', document_url';
      $urlValue = ", '" . $this->prepareString($string2) . "'";
    } else {
      $urlField = '';
      $urlValue = '';
    }

    $dataID = crc32(strtolower($string1));

    $this->query(
      sprintf(
        "INSERT
           INTO %s
                (data_id, string%s)
         VALUES ('%d', '%s'%s)",

        $table,
        $urlField,
        $dataID,
        $this->prepareString($string1),
        $urlValue
      ),
      false,
      false
    );

    return $dataID;
  }

  /**
  * Prepares a string for an SQL query.
  *
  * @param  string $string
  * @return string
  * @access public
  */
  function prepareString($string) {
    if (ini_get('magic_quotes_gpc')) {
      $string = stripslashes($string);
    }

    return $this->escapeString(substr($string, 0, 254));
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
    return str_replace("'", "''", $string);
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
    return true;
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

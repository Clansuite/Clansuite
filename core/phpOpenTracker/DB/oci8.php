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
 * phpOpenTracker Oracle Database Handler
 *
 * @author      Thomas Fromm <tf@tfromm.com>
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class phpOpenTracker_DB_oci8 extends phpOpenTracker_DB {
  /**
  * Constructor.
  *
  * @access public
  */
  function phpOpenTracker_DB_oci8() {
    $this->phpOpenTracker_DB();

    $database = ($this->config['db_database'] == 'default') ? '' : $this->config['db_database'];

    $this->connection = @ocilogon(
      $this->config['db_user'],
      $this->config['db_password'],
      $database
    );

    if (!$this->connection) {
      return phpOpenTracker::handleError(
        'Could not connect to database.',
        E_USER_ERROR
      );
    }
  }

  /**
  * Fetches a row from the current result set.
  *
  * @access public
  * @return array
  */
  function fetchRow() {
    $row = @OCIFetchInto($this->result, $result, OCI_ASSOC);

    if (is_array($row)) {
      return array_change_key_case($result, CASE_LOWER);
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
      $query = sprintf(
        'SELECT * FROM (%s) WHERE ROWNUM <= %d',

        $query,
        $limit
      );
    }

    if ($this->config['debug_level'] > 1) {
      $this->debugQuery($query);
    }

    @OCIFreeStatement($this->result);
    $this->result = @OCIParse($this->connection, $query);

    if (!$this->result) {
      $error = OCIError($this->result);

      phpOpenTracker::handleError(
        $error['code'] . $error['message'],
        E_USER_ERROR
      );
    }

    @OCIExecute($this->result);

    if (!$this->result && $warnOnFailure) {
      $error = OCIError($this->result);

      phpOpenTracker::handleError(
        $error['code'] . $error['message'],
        E_USER_ERROR
      );
    }
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

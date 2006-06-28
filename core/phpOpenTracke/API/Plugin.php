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
 * Base Class for phpOpenTracker API plugins
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class phpOpenTracker_API_Plugin {
  /**
  * Configuration
  *
  * @var array $config
  */
  var $config = array();

  /**
  * Container
  *
  * @var array $container
  */
  var $container = array();

  /**
  * DB
  *
  * @var object $db
  */
  var $db;

  /**
  * Constructor.
  *
  * @access public
  */
  function phpOpenTracker_API_Plugin() {
    $this->config    = &phpOpenTracker_Config::getConfig();
    $this->container = &phpOpenTracker_Container::getInstance();
    $this->db        = &phpOpenTracker_DB::getInstance();
  }

  /**
  * Builds constraint clause.
  *
  * @param  array   $constraints
  * @param  boolean $selfJoinPossiblyRequired
  * @return mixed
  * @access protected
  * @since  phpOpenTracker 1.1.0
  */
  function _constraint($constraints, $selfJoinPossiblyRequired = false) {
    $constraint       = '';
    $selfJoinRequired = false;

    foreach ($constraints as $field => $value) {
      switch ($field) {
        case 'document': {
          $constraint .= sprintf(
            " AND accesslog%s.document_id = %d",

            ($selfJoinPossiblyRequired) ? '2' : '',
            $value,
            ($selfJoinPossiblyRequired) ? '2' : ''
          );

          if ($selfJoinPossiblyRequired) {
            $selfJoinRequired = true;
          }
        }
        break;

        case 'entry_document': {
          $constraint .= sprintf(
            " AND accesslog%s.document_id = %d AND accesslog%s.entry_document = 1",

            ($selfJoinPossiblyRequired) ? '2' : '',
            $value,
            ($selfJoinPossiblyRequired) ? '2' : ''
          );

          if ($selfJoinPossiblyRequired) {
            $selfJoinRequired = true;
          }
        }
        break;

        case 'exit_document': {
          $constraint .= sprintf(
            ' AND accesslog%s.document_id = %d AND accesslog%s.exit_target_id <> 0',

            ($selfJoinPossiblyRequired) ? '2' : '',
            $value,
            ($selfJoinPossiblyRequired) ? '2' : ''
          );

          if ($selfJoinPossiblyRequired) {
            $selfJoinRequired = true;
          }
        }
        break;

        case 'exit_target': {
          $constraint .= sprintf(
            ' AND accesslog%s.exit_target_id = %d',

            ($selfJoinPossiblyRequired) ? '2' : '',
            $value
          );

          if ($selfJoinPossiblyRequired) {
            $selfJoinRequired = true;
          }
        }
        break;

        case 'host':
        case 'operating_system':
        case 'referer':
        case 'user_agent': {
          $constraint .= sprintf(
            ' AND visitors.%s_id = %d',

            $field,
            $value
          );
        }
        break;

        case 'hour':
        case 'weekday': {
          $constraint .= sprintf(
            ' AND accesslog.%s = %d',

            $field,
            $value
          );
        }
        break;
      }
    }

    if ($selfJoinPossiblyRequired) {
      return array(
        $constraint,
        $selfJoinRequired
      );
    } else {
      return $constraint;
    }
  }

  /**
  * Builds timerange where clause for interval (start, end).
  *
  * @param  integer start
  * @param  integer end
  * @param  string  table
  * @return string
  * @access protected
  */
  function _whereTimerange($start, $end, $table = 'visitors') {
    $table    .= '.';
    $timerange = ' AND ';

    if ($start && $end) {
      $timerange .= $table . "timestamp BETWEEN $start AND $end";
    }

    elseif ($start && !$end) {
      $timerange .= $table . "timestamp >= $start";
    }

    elseif (!$start && $end) {
      $timerange .= $table . "timestamp <= $end";
    }

    elseif (!$start && !$end) {
      return '';
    }

    return $timerange;
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

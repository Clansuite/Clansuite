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
 * phpOpenTracker API - Access Statistics
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.2.0
 */
class phpOpenTracker_API_access_statistics extends phpOpenTracker_API_Plugin {
  /**
  * API Calls
  *
  * @var array $apiCalls
  */
  var $apiCalls = array(
    'page_impressions',
    'visits'
  );

  /**
  * API Type
  *
  * @var string $apiType
  */
  var $apiType = 'get';

  /**
  * Runs the phpOpenTracker API call.
  *
  * @param  array $parameters
  * @return mixed
  * @access public
  */
  function run($parameters) {
    $parameters['interval'] = isset($parameters['interval']) ? $parameters['interval'] : false;

    $intervalStrings = array();
    $timestamps      = array();
    $values          = array();

    switch ($parameters['result_format']) {
      case 'csv': {
        if ($parameters['api_call'] == 'page_impressions') {
          $csv = "Interval;Page Impressions\n";
        } else {
          $csv = "Interval;Visits\n";
        }
      }
      break;

      case 'xml':
      case 'xml_object': {
        $tree = new XML_Tree;

        if ($parameters['api_call'] == 'page_impressions') {
          $root = &$tree->addRoot('pageimpressions');
        } else {
          $root = &$tree->addRoot('visits');
        }
      }
      break;

      default: {
        $result = array();
      }
    }

    if ($parameters['interval'] != false) {
      $start = $parameters['start'] ? $parameters['start'] : 0;
      $end   = $parameters['end']   ? $parameters['end']   : time();

      for ($startTimestamp = $start; $startTimestamp < $end; $startTimestamp += $parameters['interval']) {
        $correct      = ((mktime(0, 0, 0, date('m', $startTimestamp), date('d', $startTimestamp) + 1, date('Y', $startTimestamp))
                        - mktime(0, 0, 0, date('m', $startTimestamp), date('d', $startTimestamp),     date('Y', $startTimestamp)))
                        * ($parameters['interval'] / 86400))
                        - $parameters['interval'];
        $endTimestamp = $startTimestamp + $parameters['interval'] + $correct - 1;

        $intervalStrings[] = sprintf(
          '%s - %s',

          date('d-m-Y', $startTimestamp),
          date('d-m-Y', $endTimestamp)
        );

        $values[] = phpOpenTracker::get(
          array(
            'client_id'   => $parameters['client_id'],
            'api_call'    => $parameters['api_call'],
            'start'       => $startTimestamp,
            'end'         => $endTimestamp,
            'constraints' => $parameters['constraints']
          )
        );

        $timestamps[]    = $startTimestamp;
        $startTimestamp += $correct;
      }
    } else {
      $this->db->query(
        sprintf(
          "SELECT %s AS result
             FROM %s accesslog,
                  %s visitors
            WHERE visitors.client_id    = '%d'
              AND visitors.accesslog_id = accesslog.accesslog_id
                  %s
                  %s",

          ($parameters['api_call'] == 'page_impressions') ? 'COUNT(*)' : 'COUNT(DISTINCT(visitors.accesslog_id))',
          $this->config['accesslog_table'],
          $this->config['visitors_table'],
          $parameters['client_id'],
          $this->_constraint($parameters['constraints']),
          $this->_whereTimerange(
            $parameters['start'],
            $parameters['end'],
            'accesslog'
          )
        )
      );

      if ($row = $this->db->fetchRow()) {
        $values = array(intval($row['result']));
      } else {
        $values = array(0);
      }

      if ($parameters['start'] != false &&
          $parameters['end']   != false) {
        $intervalStrings = array(
          sprintf(
            '%s - %s',

            date('d-m-Y', $parameters['start']),
            date('d-m-Y', $parameters['end'])
          )
        );
      } else {
        $intervalStrings = array('');
      }
    }

    switch ($parameters['result_format']) {
      case 'csv': {
        for ($i = 0; $i < sizeof($values); $i++) {
          $csv .= sprintf(
            "%s;%d\n",

            $intervalStrings[$i],
            $values[$i]
          );
        }

        return $csv;
      }
      break;

      case 'xml':
      case 'xml_object': {
        for ($i = 0; $i < sizeof($values); $i++) {
          $intervalChild = &$root->addChild('interval');

          $intervalChild->addChild('interval', $intervalStrings[$i]);
          $intervalChild->addChild('value',    $values[$i]);
        }

        if ($parameters['result_format'] == 'xml') {
          return $root->get();
        } else {
          return $root;
        }
      }
      break;

      default: {
        if (sizeof($values) == 1) {
          return $values[0];
        } else {
          $result = array();

          for ($i = 0; $i < sizeof($values); $i++) {
            $result[] = array(
              'timestamp' => $timestamps[$i],
              'value'     => $values[$i]
            );
          }
        }

        return $result;
      }
    }
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

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
 * phpOpenTracker API - Returning Visitors
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.5
 */
class phpOpenTracker_API_returning_visitors extends phpOpenTracker_API_Plugin {
  /**
  * API Calls
  *
  * @var array $apiCalls
  */
  var $apiCalls = array(
    'average_time_between_visits',
    'average_visits',
    'num_one_time_visitors',
    'num_return_visits',
    'num_returning_visitors',
    'num_unique_visitors',
    'returning_visitors'
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
      switch ($parameters['api_call']) {
        case 'average_time_between_visits': {
          return $this->_averageTimeBetweenVisits($parameters);
        }
        break;

        case 'average_visits': {
          return $this->_averageVisits($parameters);
        }
        break;

        case 'num_one_time_visitors': {
          return $this->_numOneTimeVisitors($parameters);
        }
        break;

        case 'num_return_visits': {
          return $this->_numReturnVisits($parameters);
        }
        break;

        case 'num_returning_visitors': {
          return $this->_numReturningVisitors($parameters);
        }
        break;

        case 'num_unique_visitors': {
          return $this->_numUniqueVisitors($parameters);
        }
        break;

        case 'returning_visitors': {
          return $this->_returningVisitors($parameters);
        }
        break;
      }
  }

  /**
  * Returns the average time between visits of
  * returning visitors.
  *
  * @param  array $parameters
  * @return integer
  * @access private
  * @author Sven Weih <sven@weih.de>
  * @since  phpOpenTracker 1.3.0
  */
  function _averageTimeBetweenVisits($parameters) {
    $timeCount  = 0;
    $visitCount = 0;
    $visitors   = $this->_queryReturningVisitors($parameters);

    if (!empty($visitors)) {
      $keys    = array_keys($visitors);
      $numKeys = sizeof($keys);

      for ($i = 0; $i < $numKeys; $i++) {
        $visitors[$keys[$i]]['num_visits'] = sizeof($visitors[$keys[$i]]['accesslog_ids']);
        $visitors[$keys[$i]]['visitor_id'] = $keys[$i];
        
        if ($visitors[$keys[$i]]['num_visits'] > 1) {
          for ($j = 0; $j < $visitors[$keys[$i]]['num_visits'] - 1; $j++) {
            $timeCount += ($visitors[$keys[$i]]['timestamps'][$j + 1] -
            $visitors[$keys[$i]]['timestamps'][$j]);
            $visitCount++;
          }
        }
      }

      return ($visitCount == 0) ? 0 : floor($timeCount / $visitCount);
    }

    return 0;
  }

  /**
  * Returns the average number of visits of
  * returning visitors.
  *
  * @param  array $parameters
  * @return integer
  * @access private
  * @since  phpOpenTracker 1.3.0
  */
  function _averageVisits($parameters) {
    $returnVisitsParameters             = $parameters;
    $returnVisitsParameters['api_call'] = 'num_return_visits';

    $returningVisitorsParameters             = $parameters;
    $returningVisitorsParameters['api_call'] = 'num_returning_visitors';

    $returnVisits      = phpOpenTracker::get($returnVisitsParameters);
    $returningVisitors = phpOpenTracker::get($returningVisitorsParameters);

    return ($returningVisitors == 0) ? 0 : floor($returnVisits / $returningVisitors);
  }

  /**
  * Returns the number of one time visitors.
  *
  * @param  array $parameters
  * @return integer
  * @access private
  * @author Sven Weih <sven@weih.de>
  * @since  phpOpenTracker 1.3.0
  */
  function _numOneTimeVisitors($parameters) {
    $returningVisitorsParameters             = $parameters;
    $returningVisitorsParameters['api_call'] = 'num_returning_visitors';

    $visitorsParameters             = $parameters;
    $visitorsParameters['api_call'] = 'num_unique_visitors';

    $returningVisitors = phpOpenTracker::get($returningVisitorsParameters);
    $visitors          = phpOpenTracker::get($visitorsParameters);

    return $visitors - $returningVisitors;
  }

  /**
  * Returns the number of return visits.
  *
  * @param  array $parameters
  * @return integer
  * @access private
  * @since  phpOpenTracker 1.3.0
  */
  function _numReturnVisits($parameters) {
    $oneTimeVisitorsParameters             = $parameters;
    $oneTimeVisitorsParameters['api_call'] = 'num_one_time_visitors';

    $visitsParameters             = $parameters;
    $visitsParameters['api_call'] = 'visits';

    return phpOpenTracker::get($visitsParameters) -
           phpOpenTracker::get($oneTimeVisitorsParameters);
  }

  /**
  * Returns the number of returning visitors.
  *
  * @param  array $parameters
  * @return integer
  * @access private
  * @since  phpOpenTracker 1.3.0
  */
  function _numReturningVisitors($parameters) {
    $this->db->query(
      sprintf(
        "SELECT COUNT(visitors.visitor_id) AS returning_visitors
           FROM %s accesslog,
                %s visitors
          WHERE visitors.client_id         = '%d'
            AND visitors.accesslog_id      = accesslog.accesslog_id
            AND visitors.returning_visitor = '1'
                %s
                %s",

        $this->config['accesslog_table'],
        $this->config['visitors_table'],
        $parameters['client_id'],
        $this->_constraint($parameters['constraints']),
        $this->_whereTimerange(
          $parameters['start'],
          $parameters['end']
        )
      )
    );

    if ($row = $this->db->fetchRow()) {
      return $row['returning_visitors'];
    } else {
      return 0;
    }
  }

  /**
  * Returns the number of unique visitors.
  *
  * @param  array $parameters
  * @return integer
  * @access private
  * @since  phpOpenTracker 1.3.0
  */
  function _numUniqueVisitors($parameters) {
    $this->db->query(
      sprintf(
        "SELECT COUNT(DISTINCT(visitors.visitor_id)) AS unique_visitors
           FROM %s accesslog,
                %s visitors
          WHERE visitors.client_id    = '%d'
            AND visitors.accesslog_id = accesslog.accesslog_id
                %s
                %s",

        $this->config['accesslog_table'],
        $this->config['visitors_table'],
        $parameters['client_id'],
        $this->_constraint($parameters['constraints']),
        $this->_whereTimerange(
          $parameters['start'],
          $parameters['end']
        )
      )
    );

    if ($row = $this->db->fetchRow()) {
      return $row['unique_visitors'];
    } else {
      return 0;
    }
  }

  /**
  * Returns information for the returning visitors.
  *
  * @param  array $parameters
  * @return array
  * @access private
  */
  function _returningVisitors($parameters) {
    $visitors = $this->_queryReturningVisitors($parameters);

    if (!empty($visitors)) {
      $keys    = array_keys($visitors);
      $numKeys = sizeof($keys);

      for ($i = 0; $i < $numKeys; $i++) {
        $visitors[$keys[$i]]['num_visits'] = sizeof($visitors[$keys[$i]]['accesslog_ids']);
        $visitors[$keys[$i]]['visitor_id'] = $keys[$i];

        if ($visitors[$keys[$i]]['num_visits'] > 1) {
          $time = 0;

          for ($j = 0; $j < $visitors[$keys[$i]]['num_visits'] - 1; $j++) {
            $time += ($visitors[$keys[$i]]['timestamps'][$j+1] -
                      $visitors[$keys[$i]]['timestamps'][$j]);
          }

          $visitors[$keys[$i]]['average_time_between_visits'] = floor($time / ($visitors[$keys[$i]]['num_visits'] - 1));
        } else {
          unset ($visitors[$keys[$i]]);
        }
      }

      if (!empty($visitors)) {
        foreach($visitors as $visitor) $tmp[] = $visitor['num_visits'];
        array_multisort($tmp, SORT_DESC, $visitors);
      }
    }

    switch ($parameters['result_format']) {
      case 'xml':
      case 'xml_object': {
        $tree = new XML_Tree;
        $root = &$tree->addRoot('returningVisitors');

        $numVisitors = sizeof($visitors);

        for ($i = 0; $i < $numVisitors; $i++) {
          $visitorChild = &$root->addChild('visitor');
          $visitsChild  = &$visitorChild->addChild('visits');

          $numVisits = sizeof($visitors[$i]['accesslog_ids']);

          for ($j = 0; $j < $numVisits; $j++) {
            $visitChild = &$visitsChild->addChild('visit');

            $visitChild->addChild('accesslogID', $visitors[$i]['accesslog_ids'][$j]);
            $visitChild->addChild('timestamp',   $visitors[$i]['timestamps'][$j]);
          }

          $visitorChild->addChild('numVisits', $visitors[$i]['num_visits']);
          $visitorChild->addChild('visitorID', $visitors[$i]['visitor_id']);
        }

        if ($parameters['result_format'] == 'xml') {
          return $root->get();
        } else {
          return $root;
        }
      }
      break;

      default: {
        return $visitors;
      }
    }
  }

  function _queryReturningVisitors($parameters) {
    $visitors = array();

    $this->db->query(
      sprintf(
        "SELECT visitors.accesslog_id AS accesslog_id,
                visitors.visitor_id   AS visitor_id,
                visitors.timestamp    AS timestamp
           FROM %s accesslog,
                %s visitors
          WHERE visitors.client_id    = '%d'
            AND visitors.accesslog_id = accesslog.accesslog_id
                %s
                %s
          ORDER BY visitors.visitor_id,
                   visitors.timestamp",

        $this->config['accesslog_table'],
        $this->config['visitors_table'],
        $parameters['client_id'],
        $this->_constraint($parameters['constraints']),
        $this->_whereTimerange(
          $parameters['start'],
          $parameters['end']
        )
      )
    );

    while ($row = $this->db->fetchRow()) {
      $visitors[$row['visitor_id']]['accesslog_ids'][] = $row['accesslog_id'];
      $visitors[$row['visitor_id']]['timestamps'][]    = $row['timestamp'];
    }

    return $visitors;
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

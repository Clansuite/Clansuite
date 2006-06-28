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

require_once POT_INCLUDE_PATH . 'API/Clickpath.php';

/**
 * phpOpenTracker API - Clickpath Analysis
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class phpOpenTracker_API_clickpath_analysis extends phpOpenTracker_API_Plugin {
  /**
  * API Calls
  *
  * @var array $apiCalls
  */
  var $apiCalls = array(
    'all_paths',
    'average_clickpath_length',
    'individual_clickpath',
    'top_paths',
    'longest_paths',
    'shortest_paths'
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
      case 'average_clickpath_length': {
        return $this->_averageClickpathLength($parameters);
      }
      break;

      case 'individual_clickpath': {
        return $this->_individualClickpath($parameters);
      }
      break;

      case 'all_paths':
      case 'top_paths':
      case 'longest_paths':
      case 'shortest_paths': {
        return $this->_clickpathAnalysis($parameters);
      }
      break;
    }
  }

  /**
  * @param  array $parameters
  * @return mixed
  * @access private
  * @since  phpOpenTracker 1.3.0
  */
  function _clickpathAnalysis($parameters) {
    if (!@include_once('Image/GraphViz.php')) {
      phpOpenTracker::handleError(
        'Could not find PEAR Image_GraphViz package, exiting.',
        E_USER_ERROR
      );
    }

    $parameters['document_color']    = isset($parameters['document_color'])    ? $parameters['document_color']    : 'black';
    $parameters['exit_targets']      = isset($parameters['exit_targets'])      ? $parameters['exit_targets']      : false;
    $parameters['exit_target_color'] = isset($parameters['exit_target_color']) ? $parameters['exit_target_color'] : 'red';
    $parameters['from']              = isset($parameters['from'])              ? crc32($parameters['from'])       : 0;
    $parameters['length']            = isset($parameters['length'])            ? $parameters['length']            : false;
    $parameters['referers']          = isset($parameters['referers'])          ? $parameters['referers']          : false;
    $parameters['referer_color']     = isset($parameters['referer_color'])     ? $parameters['referer_color']     : 'green';
    $parameters['result_format']     = isset($parameters['result_format'])     ? $parameters['result_format']     : 'graphviz';
    $parameters['subpaths']          = isset($parameters['subpaths'])          ? $parameters['subpaths']          : false;
    $parameters['to']                = isset($parameters['to'])                ? crc32($parameters['to'])         : 0;

    if ($parameters['api_call'] == 'all_paths') {
      $parameters['subpaths'] = true;
    }

    $data['documents']         = array();
    $data['paths']             = array();
    $data['subpathStatistics'] = array();

    switch ($parameters['result_format']) {
      case 'graphviz':
      case 'graphviz_object': {
        $result = new Image_GraphViz;

        if ($parameters['exit_targets']) {
          $result->addCluster('exit_targets', 'Exit Targets');
        }

        if ($parameters['referers']) {
          $result->addCluster('referers', 'Referers');
        }
      }
      break;

      default: {
        $result = array();
      }
    }

    $accesslogID =  0;
    $visitor     = -1;

    $this->db->query(
      sprintf(
        "SELECT accesslog.accesslog_id AS accesslog_id,
                accesslog.document_id  AS document_id,
                accesslog.timestamp    AS timestamp,
                documents.string       AS document,
                documents.document_url AS document_url
           FROM %s accesslog,
                %s visitors,
                %s documents
          WHERE visitors.client_id    = '%d'
            AND visitors.accesslog_id = accesslog.accesslog_id
            AND accesslog.document_id = documents.data_id
                %s
                %s
          ORDER BY accesslog.accesslog_id,
                   accesslog.timestamp",

        $this->config['accesslog_table'],
        $this->config['visitors_table'],
        $this->config['documents_table'],
        $parameters['client_id'],
        $this->_constraint($parameters['constraints']),
        $this->_whereTimerange(
          $parameters['start'],
          $parameters['end'],
          'accesslog'
        )
      )
    );

    while ($row = $this->db->fetchRow()) {
      if (!isset($data['documents'][$row['document_id']])) {
        $data['documents'][$row['document_id']] = array(
          $row['document'],
          $row['document_url'],
          'document'
        );
      }

      if ($accesslogID != $row['accesslog_id']) {
        $accesslogID = $row['accesslog_id'];
        $node        = 0;
        $visitor++;

        unset($previousTimestamp);

        $accesslog_ids[$visitor] = $row['accesslog_id'];
      }

      $clickpaths[$visitor][$node]['document']   = $row['document_id'];
      $clickpaths[$visitor][$node]['time_spent'] = 1;

      if (isset($previousTimestamp)) {
        $clickpaths[$visitor][$node-1]['time_spent'] = $row['timestamp'] - $previousTimestamp;
      }

      $previousTimestamp = $row['timestamp'];
      $node++;
    }

    if (!isset($clickpaths)) {
      return $result;
    }

    $numClickpaths = sizeof($clickpaths) - 1;

    if ($parameters['exit_targets']) {
      for ($i = 0; $i <= $numClickpaths; $i++) {
        $this->db->query(
          sprintf(
            "SELECT accesslog.document_id    AS document_id,
                    accesslog.exit_target_id AS exit_target_id,
                    exit_targets.string      AS exit_target
               FROM %s accesslog,
                    %s exit_targets
              WHERE accesslog.accesslog_id   = '%d'
                AND accesslog.exit_target_id = exit_targets.data_id",

            $this->config['accesslog_table'],
            $this->config['exit_targets_table'],
            $accesslog_ids[$i]
          )
        );

        while ($row = $this->db->fetchRow()) {
          $data['documents'][$row['exit_target_id']] = array(
            $row['exit_target'],
            'http://' . $row['exit_target'],
            'exit_target'
          );

          $visitor = sizeof($clickpaths);

          $clickpaths[$visitor][0]['document']   = $row['document_id'];
          $clickpaths[$visitor][0]['time_spent'] = 0;

          $clickpaths[$visitor][1]['document']   = $row['exit_target_id'];
          $clickpaths[$visitor][1]['time_spent'] = 0;
        }
      }
    }

    for ($i = 0; $i < sizeof($clickpaths) - 1; $i++) {
      $pathLength = sizeof($clickpaths[$i]);

      if ($parameters['referers'] && $i <= $numClickpaths) {
        $this->db->query(
          sprintf(
            "SELECT referers.string     AS referer,
                    visitors.referer_id AS referer_id
               FROM %s visitors,
                    %s referers
              WHERE visitors.accesslog_id = '%d'
                AND visitors.referer_id   = referers.data_id",

            $this->config['visitors_table'],
            $this->config['referers_table'],
            $accesslog_ids[$i]
          )
        );

        if ($row = $this->db->fetchRow()) {
          $data['documents'][$row['referer_id']] = array(
            $row['referer'],
            'http://' . $row['referer'],
            'referer'
          );

          array_unshift(
            $clickpaths[$i],
            array(
              'document'   => $row['referer_id'],
              'time_spent' => 0,
            )
          );
        }
      }

      if (!$parameters['subpaths']) {
        $this->_processPath(
          $data,
          $clickpaths[$i],
          $parameters['from'],
          $parameters['to'],
          $parameters['length']
        );
      } else {
        for ($j = 2; $j <= $pathLength; $j++) {
          $subpath       = array_slice($clickpaths[$i], 0, $j);
          $subpathLength = sizeof($subpath);

          for ($k = 2; $k <= $subpathLength; $k++) {
            $this->_processPath(
              $data,
              array_slice($subpath, (0 - $k)),
              $parameters['from'],
              $parameters['to'],
              $parameters['length']
            );
          }
        }
      }
    }

    if (empty($data['paths'])) {
      return $result;
    }

    foreach ($data['subpathStatistics'] as $fromNode => $toNodes) {
      foreach ($toNodes as $toNode => $nodeData) {
        if ($parameters['api_call'] == 'all_paths') {
          $fromColor = $parameters['document_color'];
          $fromGroup = 'default';
          $fromID    = $data['documents'][$fromNode][0];
          $fromRank  = 'same';

          $toColor   = $parameters['document_color'];
          $toGroup   = 'default';
          $toID      = $data['documents'][$toNode][0];
          $toRank    = 'same';

          $edgeColor = $parameters['document_color'];

          if ($data['documents'][$toNode][2] == 'exit_target') {
            $toColor   = $parameters['exit_target_color'];
            $toGroup   = 'exit_targets';
            $toID      = 'exit_target_' . $toID;
            $toRank    = 'sink';

            $edgeColor = $parameters['exit_target_color'];
          }

          if ($data['documents'][$fromNode][2] == 'referer') {
            $fromColor = $parameters['referer_color'];
            $fromGroup = 'referers';
            $fromID    = 'referer_' . $fromID;
            $fromRank  = 'source';

            $edgeColor = $parameters['referer_color'];
          }

          $result->addNode(
            $fromID,
            array(
              'label' => $data['documents'][$fromNode][0],
              'url'   => $data['documents'][$fromNode][1],
              'color' => $fromColor,
              'rank'  => $fromRank,
              'shape' => 'box'
            ),
            $fromGroup
          );

          $result->addNode(
            $toID,
            array(
              'label' => $data['documents'][$toNode][0],
              'url'   => $data['documents'][$toNode][1],
              'color' => $toColor,
              'rank'  => $toRank,
              'shape' => 'box'
            ),
            $toGroup
          );

          $result->addEdge(
            array(
              $fromID => $toID
            ),
            array(
              'color' => $edgeColor,
              'label' => $nodeData['count']
            )
          );
        }

        else if (isset($nodeData['count'])) {
          $data['subpathStatistics'][$fromNode][$toNode]['time_spent'] = floor($nodeData['time_spent'] / $nodeData['count']);
        }
      }
    }

    if ($parameters['api_call'] != 'all_paths') {
      switch ($parameters['api_call']) {
        case 'shortest_paths': {
          $field = 'length';
          $sort  = SORT_ASC;
        }
        break;

        case 'longest_paths': {
          $field = 'length';
          $sort  = SORT_DESC;
        }
        break;

        case 'top_paths': {
          $field = 'count';
          $sort  = SORT_DESC;
        }
        break;
      }

      foreach($data['paths'] as $path) $tmp[] = $path[$field];
      array_multisort($tmp, $sort, $data['paths']);

      if ($parameters['limit'] && sizeof($data['paths']) > $parameters['limit']) {
        $data['paths'] = array_slice($data['paths'], 0, $parameters['limit']);
      }

      $rank = 1;

      foreach ($data['paths'] as $path => $statistics) {
        $documents    = array();
        $documentURLs = array();
        $path         = explode(':', $path);
        $pathLength   = sizeof($path);
        $where        = '';

        if ($parameters['subpaths']) {
          $subpathStatistics = array();

          for ($i = 0; $i < $pathLength - 1; $i++) {
            $subpathStatistics[] = array(
              'count'      => intval($data['subpathStatistics'][$path[$i]][$path[$i+1]]['count']),
              'time_spent' => intval($data['subpathStatistics'][$path[$i]][$path[$i+1]]['time_spent'])
            );
          }
        } else {
          $subpathStatistics = false;
        }

        for ($i = 0; $i < $pathLength; $i++) {
          $documents[$i]    = $data['documents'][$path[$i]][0];
          $documentURLs[$i] = $data['documents'][$path[$i]][1];
        }

        if ($parameters['result_format'] == 'array') {
          $result[] = new phpOpenTracker_Clickpath(
            $documents,
            $documentURLs,
            $subpathStatistics,
            intval($statistics['count'])
          );
        } else {
          $result->addCluster(
            $rank,
            sprintf(
              '%s. Taken by %s visitors.',

              $rank,
              $statistics['count']
            )
          );

          for ($i = 0; $i < $pathLength - 1; $i++) {
            $fromID = $rank . '_' . $documents[$i];
            $toID   = $rank . '_' . $documents[$i+1];

            $result->addNode(
              $fromID,
              array(
                'label' => $documents[$i],
                'url'   => $documentURLs[$i],
                'color' => $parameters['document_color'],
                'shape' => 'box'
              ),
              $rank
            );

            $result->addNode(
              $toID,
              array(
                'label' => $documents[$i+1],
                'url'   => $documentURLs[$i+1],
                'color' => $parameters['document_color'],
                'shape' => 'box'
              ),
              $rank
            );

            $result->addEdge(
              array(
                $fromID => $toID
              ),
              array(
                'color' => $parameters['document_color']
              )
            );
          }
        }

        $rank++;
      }
    }

    if ($parameters['result_format'] == 'graphviz') {
      return $result->parse();
    } else {
      return $result;
    }
  }

  /**
  * Returns the average clickpath length.
  *
  * @param  array $parameters
  * @return integer
  * @access private
  * @author Sven Weih <sven@weih.de>
  * @since  phpOpenTracker 1.3.0
  */
  function _averageClickpathLength($parameters) {
    $pageImpressionsParameters             = $parameters;
    $pageImpressionsParameters['api_call'] = 'page_impressions';

    $visitsParameters             = $parameters;
    $visitsParameters['api_call'] = 'visits';

    $pageImpressions = phpOpenTracker::get($pageImpressionsParameters);
    $visits          = phpOpenTracker::get($visitsParameters);
    
    if (is_array($pageImpressions)) {
      for ($i = 0; $i < sizeof($pageImpressions); $i++) {
        $result[$i] = array(
          'timestamp' => $pageImpressions[$i]['timestamp'],
          'value'     => ($pageImpressions[$i]['value'] / (($visits[$i]['value'] ==0 ) ? 1 : $visits[$i]['value']))
        );
      }
    } else {
      if ($visits != 0) { 
      	return $pageImpressions / $visits;	
      } else {
        return 0;	
      }
    }

    return $result; 
  }

  /**
  * Returns an individual clickpath.
  *
  * @param  array $parameters
  * @return mixed
  * @access private
  * @since  phpOpenTracker 1.3.0
  */
  function _individualClickpath($parameters) {
    if (!isset($parameters['accesslog_id'])) {
      return phpOpenTracker::handleError(
        'Required parameter "accesslog_id" missing.'
      );
    }

    $parameters['resolve_ids'] = isset($parameters['resolve_ids']) ? $parameters['resolve_ids'] : true;

    if ($parameters['resolve_ids']) {
      $this->db->query(
        sprintf(
          "SELECT data_values.string       AS document,
                  data_values.document_url AS document_url,
                  accesslog.timestamp      AS timestamp
             FROM %s accesslog,
                  %s data_values
            WHERE accesslog.accesslog_id = '%d'
              AND accesslog.document_id  = data_values.data_id
            ORDER BY timestamp",

          $this->config['accesslog_table'],
          $this->config['documents_table'],
          $parameters['accesslog_id']
        )
      );
    } else {
      $this->db->query(
        sprintf(
          "SELECT accesslog.document_id AS document,
                  accesslog.timestamp
             FROM %s accesslog
            WHERE accesslog.accesslog_id = '%d'
            ORDER BY timestamp",

          $this->config['accesslog_table'],
          $parameters['accesslog_id']
        )
      );
    }

    $i = 0;

    while ($row = $this->db->fetchRow()) {
      $documents[$i]    = $row['document'];
      $documentURLs[$i] = $row['document_url'];

      if (isset($previousTimestamp)) {
        $timeSpent[$i-1] = $row['timestamp'] - $previousTimestamp;
      }

      $previousTimestamp = $row['timestamp'];
      $i++;
    }

    if (!isset($documents)) {
      return new phpOpenTracker_Clickpath(array());
    }

    $timeSpent[sizeof($documents)-1] = 1;

    $clickpath = new phpOpenTracker_Clickpath(
      $documents,
      $documentURLs,
      $timeSpent
    );

    switch ($parameters['result_format']) {
      case 'graphviz': {
        return $clickpath->toGraph();
      }
      break;

      case 'graphviz_object': {
        return $clickpath->toGraph(true);
      }
      break;

      case 'xml': {
        return $clickpath->toXML();
      }
      break;

      case 'xml_object': {
        return $clickpath->toXML(true);
      }
      break;

      default: {
        return $clickpath;
      }
    }
  }

  /**
  * @param  array   $data
  * @param  string  $path
  * @param  integer $from
  * @param  integer $to
  * @param  integer $length
  * @access private
  */
  function _processPath(&$data, $path, $from, $to, $length) {
    $pathLength = sizeof($path);

    $first = $path[0]['document'];
    $last  = $path[$pathLength - 1]['document'];

    if ($pathLength > 1 &&
        ((  $from == false && $to     == false) ||
         (  $from != false && $from   == $first && $to == false) ||
         (  $from == false && $to     != false  && $to == $last) ||
         (  $from != false && $from   == $first && $to != false && $to == $last)) &&
         ($length == false || $length == $pathLength)) {
      $id = '';

      for ($i = 0; $i < $pathLength; $i++) {
        $separator = empty($id) ? '' : ':';
        $id .= $separator . $path[$i]['document'];
      }

      if (!isset($data['paths'][$id])) {
        $data['paths'][$id]['count']  = 1;
        $data['paths'][$id]['length'] = $pathLength;

        if ($pathLength == 2) {
          $data['subpathStatistics'][$first][$last]['count']      = 1;
          $data['subpathStatistics'][$first][$last]['time_spent'] = $path[0]['time_spent'];
        }
      } else {
        $data['paths'][$id]['count']++;

        if ($pathLength == 2) {
          $data['subpathStatistics'][$first][$last]['count']++;
          $data['subpathStatistics'][$first][$last]['time_spent'] += $path[0]['time_spent'];
        }
      }
    }
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

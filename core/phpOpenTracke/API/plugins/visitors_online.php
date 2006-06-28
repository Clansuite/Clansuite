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
 * phpOpenTracker API - Visitors Online
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class phpOpenTracker_API_visitors_online extends phpOpenTracker_API_Plugin {
  /**
  * API Calls
  *
  * @var array $apiCalls
  */
  var $apiCalls = array(
    'num_visitors_online',
    'visitors_online'
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
    $parameters['session_lifetime'] = isset($parameters['session_lifetime']) ? $parameters['session_lifetime'] : 3;

    switch ($parameters['api_call']) {
      case 'num_visitors_online': {
        return $this->_numVisitorsOnline($parameters);
      }
      break;

      case 'visitors_online': {
        return $this->_visitorsOnline($parameters);
      }
      break;
    }
  }

  /**
  * Returns the number of visitors currently online.
  *
  * @param  array $parameters
  * @return integer
  * @access private
  * @since  phpOpenTracker 1.3.0
  */
  function _numVisitorsOnline($parameters) {
    $this->db->query(
      sprintf(
        "SELECT COUNT(DISTINCT(accesslog.accesslog_id)) AS num_visitors_online
           FROM %s accesslog,
                %s visitors
          WHERE visitors.client_id    = '%d'
            AND visitors.accesslog_id = accesslog.accesslog_id
            AND accesslog.timestamp   >= '%d'
                %s",

        $this->config['accesslog_table'],
        $this->config['visitors_table'],
        $parameters['client_id'],
        time() - ($parameters['session_lifetime'] * 60),
        $this->_constraint($parameters['constraints'])
      )
    );

    if ($row = $this->db->fetchRow()) {
      return intval($row['num_visitors_online']);
    } else {
      return 0;
    }
  }

  /**
  * Returns information about the visitors currently online.
  *
  * @param  array $parameters
  * @return mixed
  * @access private
  * @since  phpOpenTracker 1.3.0
  */
  function _visitorsOnline($parameters) {
    switch ($parameters['result_format']) {
      case 'xml':
      case 'xml_object': {
        $tree     = new XML_Tree;
        $root     = &$tree->addRoot('visitorsonline');
        $children = array();
      }
      break;

      default: {
        $result = array();
      }
    }

    $accesslogIDs = array();

    $this->db->query(
      sprintf(
        "SELECT DISTINCT(accesslog.accesslog_id) AS accesslog_id
           FROM %s accesslog,
                %s visitors
          WHERE visitors.client_id    = '%d'
            AND visitors.accesslog_id = accesslog.accesslog_id
            AND accesslog.timestamp  >= '%d'",

        $this->config['accesslog_table'],
        $this->config['visitors_table'],
        $parameters['client_id'],
        time() - ($parameters['session_lifetime'] * 60)
      )
    );

    while ($row = $this->db->fetchRow()) {
      $accesslogIDs[] = $row['accesslog_id'];
    }

    for ($i = 0, $max = sizeof($accesslogIDs); $i < $max; $i++) {
      switch ($parameters['result_format']) {
        case 'xml':
        case 'xml_object': {
          $visitorNode = &$root->addChild('visitor');

          $visitorNode->addChild(
            phpOpenTracker::get(
              array(
                'client_id'     => $parameters['client_id'],
                'api_call'      => 'individual_clickpath',
                'accesslog_id'  => $accesslogIDs[$i],
                'result_format' => 'xml_object'
              )
            )
          );
        }
        break;

        default: {
          $result[$i]['clickpath'] = phpOpenTracker::get(
            array(
              'client_id'    => $parameters['client_id'],
              'api_call'     => 'individual_clickpath',
              'accesslog_id' => $accesslogIDs[$i]
            )
          );
        }
      }

      $this->db->query(
        sprintf(
          "SELECT MAX(timestamp) as last_access
             FROM %s
            WHERE accesslog_id = '%s'",

          $this->config['accesslog_table'],
          $accesslogIDs[$i]
        )
      );

      if ($row = $this->db->fetchRow()) {
        switch ($parameters['result_format']) {
          case 'xml':
          case 'xml_object': {
            $visitorNode->addChild('last_access', $row['last_access']);
          }
          break;

          default: {
            $result[$i]['last_access'] = $row['last_access'];
          }
        }
      } else {
        return phpOpenTracker::handleError(
          'Database query failed.'
        );
      }

      $this->db->query(
        sprintf(
          "SELECT hosts.string       AS host,
                  user_agents.string AS user_agent
             FROM %s visitors,
                  %s hosts,
                  %s user_agents
            WHERE visitors.accesslog_id  = '%d'
              AND visitors.host_id       = hosts.data_id
              AND visitors.user_agent_id = user_agents.data_id",

          $this->config['visitors_table'],
          $this->config['hostnames_table'],
          $this->config['user_agents_table'],
          $accesslogIDs[$i]
        )
      );

      if ($row = $this->db->fetchRow()) {
        switch ($parameters['result_format']) {
          case 'xml':
          case 'xml_object': {
            $visitorNode->addChild('host',       $row['host']);
            $visitorNode->addChild('user_agent', $row['user_agent']);
          }
          break;

          default: {
            $result[$i]['host']       = $row['host'];
            $result[$i]['user_agent'] = $row['user_agent'];
          }
        }
      } else {
        return phpOpenTracker::handleError(
          'Database query failed.'
        );
      }

      $this->db->query(
        sprintf(
          "SELECT referers.string AS referer
             FROM %s visitors,
                  %s referers
            WHERE visitors.accesslog_id = '%d'
              AND visitors.referer_id   = referers.data_id",

          $this->config['visitors_table'],
          $this->config['referers_table'],
          $accesslogIDs[$i]
        )
      );

      if ($row = $this->db->fetchRow()) {
        $referer = $row['referer'];
      } else {
        $referer = '';
      }

      switch ($parameters['result_format']) {
        case 'xml':
        case 'xml_object': {
          $visitorNode->addChild('referer', $referer);
        }
        break;

        default: {
          $result[$i]['referer'] = $referer;
        }
      }

      $this->db->query(
        sprintf(
          "SELECT data_field,
                  data_value
             FROM %s
            WHERE accesslog_id  = '%d'",

          $this->config['additional_data_table'],
          $accesslogIDs[$i]
        )
      );

      switch ($parameters['result_format']) {
        case 'xml':
        case 'xml_object': {
          $additionalDataNode = &$visitorNode->addChild('additional_data');
        }
        break;

        default: {
          $result[$i]['additional_data'] = array();
        }
      }

      while ($row = $this->db->fetchRow()) {
        switch ($parameters['result_format']) {
          case 'xml':
          case 'xml_object': {
            $additionalDataNode->addChild($row['data_field'], $row['data_value']);
          }
          break;

          default: {
            $result[$i]['additional_data'][$row['data_field']] = $row['data_value'];
          }
        }
      }
    }

    switch ($parameters['result_format']) {
      case 'xml': {
        return $root->get();
      }
      break;

      case 'xml_object': {
        return $root;
      }
      break;

      default: {
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

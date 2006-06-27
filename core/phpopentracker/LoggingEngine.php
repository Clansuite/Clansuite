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

require POT_INCLUDE_PATH . 'LoggingEngine/Plugin.php';

/**
 * phpOpenTracker Logging Engine
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class phpOpenTracker_LoggingEngine {
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
  * Plugins
  *
  * @var array $plugins
  */
  var $plugins = array();

  /**
  * Constructor.
  *
  * @param  array $parameters
  * @access public
  */
  function phpOpenTracker_LoggingEngine($parameters = array()) {
    $parameters['init'] = true;

    $this->config    = &phpOpenTracker_Config::getConfig();
    $this->container = &phpOpenTracker_Container::getInstance($parameters);
    $this->db        = &phpOpenTracker_DB::getInstance();

    $this->_loadPlugins($parameters);
  }

  /**
  * Logs an access.
  *
  * @param  array   $addData
  * @return boolean
  * @access public
  */
  function log($addData) {
    if ($this->_isLocked()) {
      return false;
    }

    if ($this->config['track_returning_visitors'] &&
        !empty($this->config['returning_visitors_cookie'])) {
      $cookie = sprintf(
        '%s_%d',

        $this->config['returning_visitors_cookie'],
        $this->container['client_id']
      );
    } else {
      $cookie = false;
    }

    if ($this->container['first_request']) {
      if ($cookie &&
          isset($_COOKIE[$cookie])) {
        $this->container['visitor_id']        = $_COOKIE[$cookie];
        $this->container['returning_visitor'] = true;
      } else {
        $this->container['visitor_id']        = $this->container['accesslog_id'];
        $this->container['returning_visitor'] = false;

        if ($cookie) {
          @setcookie(
            $cookie,
            $this->container['visitor_id'],
            $this->container['timestamp'] + ($this->config['returning_visitors_cookie_lifetime'] * 24 * 60 * 60)
          );
        }
      }

      $this->_storeVisitData($addData);
      $this->_storeRequestData();
    } else {
      if ($this->config['log_reload'] ||
          $this->container['document'] != $this->container['last_document']) {
        $this->_storeRequestData();
      }
    }

    return true;
  }

  /**
  * Checks if a locking rule applies to this visitor's data.
  *
  * @return boolean true, if a locking rule applies and the
  *                 current request must not be counted.
  * @access private
  */
  function _isLocked() {
    if ($this->config['locking']) {
      if ($rules = @file(POT_CONFIG_PATH . 'lock.ini', 1)) {
        foreach ($rules as $rule) {
          $field   = substr($rule, 0, strpos($rule, ' '));
          $pattern = chop(substr($rule, strpos($rule, ' ') + 1));

          if (substr($field, 0, 1) != '#' &&
              preg_match($pattern, $this->container[$field . '_orig'])) {
            return true;
          }
        }
      } else {
        return phpOpenTracker::handleError(
          sprintf(
            'Could not open "%s", Locking disabled.',

            POT_CONFIG_PATH . 'lock.ini'
          )
        );
      }
    }

    $result = false;

    foreach ($this->plugins as $plugin) {
      if (!$plugin->pre()) {
        $result = true;
      }
    }

    return $result;
  }

  /**
  * Loads the phpOpenTracker Logging Engine plugins.
  *
  * @param  array $parameters
  * @access private
  */
  function _loadPlugins($parameters) {
    if ($this->config['logging_engine_plugins'] == '') {
      return;
    }

    $plugins = explode(
      ',',
      str_replace(
        ' ',
        '',
        $this->config['logging_engine_plugins']
      )
    );

    foreach ($plugins as $pluginName) {
      if (@include(POT_INCLUDE_PATH . 'LoggingEngine/plugins/' . $pluginName . '.php')) {
        $pluginClass     = 'phpOpenTracker_LoggingEngine_Plugin_' . $pluginName;
        $this->plugins[] = new $pluginClass($parameters);
      } else {
        phpOpenTracker::handleError(
          sprintf(
            'Could not load plugin "%s".',

            $pluginName
          )
        );
      }
    }
  }

  /**
  * Runs the phpOpenTracker Logging Engine 'post' plugins.
  *
  * @access private
  */
  function _runPostPlugins() {
    $addData = array();

    foreach ($this->plugins as $plugin) {
      $addData = array_merge($addData, $plugin->post());
    }

    if ($this->container['first_request']) {
      $this->db->storeAddData($this->container['accesslog_id'], $addData);
    }
  }

  /**
  * Stores the request information.
  *
  * @access private
  */
  function _storeRequestData() {
    if (!$this->container['first_request']) {
      $this->_runPostPlugins();
    }

    $this->db->query(
      sprintf(
        "INSERT
           INTO %s
                (accesslog_id,
                 timestamp, weekday, hour,
                 document_id, entry_document)
         VALUES ('%d', '%d', '%d', '%d', '%d', '%d')",

        $this->config['accesslog_table'],
        $this->container['accesslog_id'],
        $this->container['timestamp'],
        date('w', $this->container['timestamp']),
        date('H', $this->container['timestamp']),
        $this->container['document_id'],
        $this->container['first_request'] ? 1 : 0
      )
    );
  }

  /**
  * Stores the visit information.
  *
  * @param  array   $addData
  * @access private
  */
  function _storeVisitData(&$addData) {
    $this->_runPostPlugins();

    $this->db->query(
      sprintf(
        "INSERT
           INTO %s
                (client_id, accesslog_id,  visitor_id,
                 operating_system_id, user_agent_id, host_id, referer_id,
                 timestamp, weekday, hour,
                 returning_visitor)
         VALUES ('%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d')",

        $this->config['visitors_table'],
        $this->container['client_id'],
        $this->container['accesslog_id'],
        $this->container['visitor_id'],
        $this->container['operating_system_id'],
        $this->container['user_agent_id'],
        $this->container['host_id'],
        $this->container['referer_id'],
        $this->container['timestamp'],
        date('w', $this->container['timestamp']),
        date('H', $this->container['timestamp']),
        $this->container['returning_visitor'] ? 1 : 0
      )
    );

    $this->db->storeAddData($this->container['accesslog_id'], $addData);
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

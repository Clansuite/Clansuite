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
 * Base Class for phpOpenTracker LoggingEngine plugins
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class phpOpenTracker_LoggingEngine_Plugin {
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
  * Parameters
  *
  * @var array $parameters
  */
  var $parameters = array();

  /**
  * Constructor.
  *
  * @param  array $parameters
  * @access public
  */
  function phpOpenTracker_LoggingEngine_Plugin($parameters) {
    $this->config     = &phpOpenTracker_Config::getConfig();
    $this->container  = &phpOpenTracker_Container::getInstance();
    $this->db         = &phpOpenTracker_DB::getInstance();
    $this->parameters = $parameters;
  }

  /**
  * @return boolean
  * @access public
  */
  function pre() {
    return true;
  }

  /**
  * @return array
  * @access public
  */
  function post() {
    return array();
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

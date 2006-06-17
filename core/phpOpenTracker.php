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

if (!defined('POT_INCLUDE_PATH')) {
  define('POT_INCLUDE_PATH', dirname(__FILE__) . '/phpOpenTracker/');
}

if (!defined('POT_CONFIG_PATH')) {
  define('POT_CONFIG_PATH', POT_INCLUDE_PATH  . 'conf/');
}

require POT_INCLUDE_PATH . 'Config.php';
@include POT_CONFIG_PATH . 'phpOpenTracker.php';

require POT_INCLUDE_PATH . 'Container.php';
require POT_INCLUDE_PATH . 'DB.php';
require POT_INCLUDE_PATH . 'Version.php';

/**
 * phpOpenTracker
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class phpOpenTracker {
  /**
  * Wrapper for phpOpenTracker_API::get().
  *
  * @param  array $parameters
  * @return mixed
  * @access public
  * @static
  */
  function &get($parameters) {
    include_once POT_INCLUDE_PATH . 'API.php';

    $api    = &phpOpenTracker_API::getInstance();
    $result = $api->get($parameters);

    return $result;
  }

  /**
  * Handles an error according to the debug_level setting.
  *
  * @param  string  $errorMessage
  * @param  integer $errorType
  * @return boolean
  * @access public
  * @static
  * modified by vain to fit errorhandling fo clansuite
  */
  function handleError($errorMessage, $errorType = E_USER_WARNING) {
  // >>> inserted by vain
  global $error, $lang; // <<<
  
  
    $config = &phpOpenTracker_Config::getConfig();

    $prefix = 'phpOpenTracker ' . (($errorType == E_USER_ERROR) ? 'Error' : 'Warning') . ': ';
	
	/* Original 		
    if ($config['debug_level'] > 0) {
      print $prefix . $errorMessage;
    }
    */
    // >>> inserted by vain
     if ($config['debug_level'] > 0) {
      $error->show( $lang->t('Statistik'), $prefix . $errorMessage, 1 );
	}// <<<

    if ($config['log_errors']) {
      @error_log(
        sprintf(
          "%s: %s\n",

          date('d-m-Y H:i:s', time()),
          $errorMessage
        ),

        3,
        $config['log_errors'] != 'error.log' ? $config['log_errors'] : dirname(__FILE__) . '/' . 'error.log'
      );
    }

    if ($config['exit_on_fatal_errors'] && $errorType == E_USER_ERROR) {
      exit;
    }

    return false;
  }

  /**
  * Invokes the phpOpenTracker Logging Engine.
  *
  * @param  array $parameters
  * @return boolean
  * @access public
  * @static
  */
  function log($parameters = array()) {
    static $called;

    if (!isset($called)) {
      $called = true;
    } else {
      return phpOpenTracker::handleError(
        'phpOpenTracker::log() may only be called once per request.'
      );
    }

    include POT_INCLUDE_PATH . 'LoggingEngine.php';

    $le = new phpOpenTracker_LoggingEngine($parameters);

    return $le->log(
      isset($parameters['add_data']) ? $parameters['add_data'] : array()
    );
  }

  /**
  * Wrapper for phpOpenTracker_API::plot().
  *
  * @param  array $parameters
  * @return mixed
  * @access public
  * @static
  */
  function plot($parameters) {
    include_once POT_INCLUDE_PATH . 'API.php';

    $api = &phpOpenTracker_API::getInstance();

    $api->plot($parameters);
  }
}

?>

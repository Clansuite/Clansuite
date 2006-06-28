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
 * phpOpenTracker Configuration Container
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class phpOpenTracker_Config {
  /**
  * Returns the configuration array.
  *
  * @return array
  * @access public
  * @static
  * @since  phpOpenTracker 1.4.0
  */
  function &getConfig() {
    static $config;

    if (!isset($config)) {
      $config = array(
        'db_type'                             => 'mysql',
        'db_host'                             => 'localhost',
        'db_port'                             => 'default',
        'db_socket'                           => 'default',
        'db_user'                             => 'clansuite',
        'db_password'                         => 'toop',
        'db_database'                         => 'clansuite',
        'additional_data_table'               => 'pot_add_data',
        'accesslog_table'                     => 'pot_accesslog',
        'documents_table'                     => 'pot_documents',
        'exit_targets_table'                  => 'pot_exit_targets',
        'hostnames_table'                     => 'pot_hostnames',
        'operating_systems_table'             => 'pot_operating_systems',
        'referers_table'                      => 'pot_referers',
        'user_agents_table'                   => 'pot_user_agents',
        'visitors_table'                      => 'pot_visitors',
        'merge_tables_mode'                   => 'month',
        'document_env_var'                    => 'REQUEST_URI',
        'clean_referer_string'                => false,
        'clean_query_string'                  => false,
        'get_parameter_filter'                => '',
        'resolve_hostname'                    => true,
        'group_hostnames'                     => true,
        'group_user_agents'                   => true,
        'track_returning_visitors'            => false,
        'returning_visitors_cookie'           => 'pot_visitor_id',
        'returning_visitors_cookie_lifetime'  => 365,
        'locking'                             => false,
        'log_reload'                          => false,
        'jpgraph_path'                        => '',
        'logging_engine_plugins'              => '',
        'query_cache'                         => 'false',
        'query_cache_dir'                     => '/tmp',
        'query_cache_lifetime'                => 3600,
        'debug_level'                         => 1,
        'exit_on_fatal_errors'                => true,
        'log_errors'                          => false,
        'clients'                             => array(
          1 => $_SERVER['HTTP_HOST']
        )
      );
    }

    return $config;
  }

  /**
  * Gets the current value of a configuration directive.
  *
  * @param  string $directive
  * @return mixed
  * @access public
  * @static
  * @since  phpOpenTracker 1.2.0
  */
  function get($directive) {
    $config = &phpOpenTracker_Config::getConfig();

    return isset($config[$directive]) ? $config[$directive] : false;
  }

  /**
  * Sets the value of a configuration directive.
  *
  * @param  string $directive
  * @param  mixed  $value
  * @access public
  * @static
  * @since  phpOpenTracker 1.2.0
  */
  function set($directive, $value) {
    $config = &phpOpenTracker_Config::getConfig();

    $config[$directive] = $value;
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

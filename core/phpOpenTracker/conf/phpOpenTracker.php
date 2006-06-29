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

$PHPOPENTRACKER_CONFIGURATION = &phpOpenTracker_Config::getConfig();

/**
* phpOpenTracker Configuration File
*
* This file contains global configuration settings for phpOpenTracker.
* Values may be safely edited by hand.
* Uncomment only values that you intend to change.
*
* Strings should be enclosed in 'quotes'.
* Integers should be given literally (without quotes).
* Boolean values may be true or false (never quotes).
*/

// Database Type
// Available values: 'mssql', 'mysql', 'mysql_merge', 'oci8', 'pgsql'
$PHPOPENTRACKER_CONFIGURATION['db_type'] = 'pdo';

// The host of your database server
$PHPOPENTRACKER_CONFIGURATION['db_host'] = 'localhost';

// The port your database server listens on
$PHPOPENTRACKER_CONFIGURATION['db_port'] = 'default';

// The socket your database server uses
$PHPOPENTRACKER_CONFIGURATION['db_socket'] = 'default';

// Username to connect with to your database server
$PHPOPENTRACKER_CONFIGURATION['db_user'] = 'clansuite';

// Password to connect with to your database server
$PHPOPENTRACKER_CONFIGURATION['db_password'] = 'toop';

// Name of the database to use.
$PHPOPENTRACKER_CONFIGURATION['db_database'] = 'clansuite';

// Name of the Additional Data Table
// Default: 'pot_add_data'
$PHPOPENTRACKER_CONFIGURATION['additional_data_table'] = DB_PREFIX . 'pot_add_data';

// Name of the Access Log Table
// Default: 'pot_accesslog'
$PHPOPENTRACKER_CONFIGURATION['accesslog_table'] = DB_PREFIX . 'pot_accesslog';

// Name of the Documents Table
// Default: 'pot_documents'
$PHPOPENTRACKER_CONFIGURATION['documents_table'] = DB_PREFIX . 'pot_documents';

// Name of the Exit Targets Table
// Default: 'pot_exit_targets'
$PHPOPENTRACKER_CONFIGURATION['exit_targets_table'] = DB_PREFIX . 'pot_exit_targets';

// Name of the Hostnames Table
// Default: 'pot_hostnames'
$PHPOPENTRACKER_CONFIGURATION['hostnames_table'] = DB_PREFIX . 'pot_hostnames';

// Name of the Operating Systems Table
// Default: 'pot_operating_systems'
$PHPOPENTRACKER_CONFIGURATION['operating_systems_table'] = DB_PREFIX . 'pot_operating_systems';

// Name of the Referers Table
// Default: 'pot_referers'
$PHPOPENTRACKER_CONFIGURATION['referers_table'] = DB_PREFIX . 'pot_referers';

// Name of the User Agents Table
// Default: 'pot_user_agents'
$PHPOPENTRACKER_CONFIGURATION['user_agents_table'] = DB_PREFIX . 'pot_user_agents';

// Name of the Visitors Table
// Default: 'pot_visitors'
$PHPOPENTRACKER_CONFIGURATION['visitors_table'] = DB_PREFIX . 'pot_visitors';

// Resolution for Merge Tables backend
// 'day':   One pot_accesslog/pot_visitors table per day.
// 'month': One pot_accesslog/pot_visitors table per month.
// Default: 'month'
 $PHPOPENTRACKER_CONFIGURATION['merge_tables_mode'] = 'month';

// Name of the environment variable to be used to 
// determine the current document.
// For instance, 'PATH_INFO' or 'REQUEST_URI' are possible here.
$PHPOPENTRACKER_CONFIGURATION['document_env_var'] = 'REQUEST_URI';

// When enabled, phpOpenTracker will strip away all HTTP GET
// parameters from the referer's URL before it gets stored
// in the database.
$PHPOPENTRACKER_CONFIGURATION['clean_referer_string'] = false;

// When enabled, phpOpenTracker will strip away all HTTP GET
// parameters from the URL, before it gets stored in the
// database.
// A Session ID will be stripped from the URL in either case.
// $PHPOPENTRACKER_CONFIGURATION['clean_query_string'] = false;

// While enabling clean_query_string to will clean the
// document's URL of any HTTP GET parameters, you can define
// with the get_parameter_filter array a list of HTTP GET
// parameters that you would like to be stripped from the URL.
// $PHPOPENTRACKER_CONFIGURATION['get_parameter_filter'] = '';

// Resolving of the hostname can be turned off.
// $PHPOPENTRACKER_CONFIGURATION['resolve_hostname'] = true;

// Grouping of hostnames can be turned off.
// $PHPOPENTRACKER_CONFIGURATION['group_hostnames'] = true;

// Grouping and parsing of user agents can be turned off.
// $PHPOPENTRACKER_CONFIGURATION['group_user_agents'] = true;

// Detect and log returning visitors.
// $PHPOPENTRACKER_CONFIGURATION['track_returning_visitors'] = false;

// Name of the cookie to use for returning visitors detection.
// $PHPOPENTRACKER_CONFIGURATION['returning_visitors_cookie'] = 'pot_visitor_id';

// The 'returning_visitors_cookie' cookie expires after
// 'returning_visitors_cookie_lifetime' days.
// $PHPOPENTRACKER_CONFIGURATION['returning_visitors_cookie_lifetime'] = 365;

// With this directive you can turn on or off the locking of
// certain IPs and/or user agents.
// $PHPOPENTRACKER_CONFIGURATION['locking'] = false;

// With this directive you can turn on or off the logging of
// reloaded documents.
// $PHPOPENTRACKER_CONFIGURATION['log_reload'] = false;

// The path to your JPGraph installation.
//$PHPOPENTRACKER_CONFIGURATION['jpgraph_path'] = '../API/';

// With this directive you can define the names, separated by commas,
// of plugins for the phpOpenTracker Logging Engine, that should be
// loaded.
// $PHPOPENTRACKER_CONFIGURATION['logging_engine_plugins'] = '';

// When enabled, the result of a phpOpenTracker API query which is
// limited to a timerange that lies completely in the past will be
// stored in a cache.
//$PHPOPENTRACKER_CONFIGURATION['query_cache'] = false;

// The directory where the phpOpenTracker API Query Cache should
// store its files.
//$PHPOPENTRACKER_CONFIGURATION['query_cache_dir'] = '/tmp';

// The lifetime of a phpOpenTracker API Query Cache entry in seconds.
//$PHPOPENTRACKER_CONFIGURATION['query_cache_lifetime'] = 3600;

// 0: Don't output error and warning messages.
// 1: Output error and warning messages. (default)
// 2: Output additional debugging messages.
$PHPOPENTRACKER_CONFIGURATION['debug_level'] = DEBUG ? 1 : 0;

// When enabled, phpOpenTracker will exit on fatal errors.
// $PHPOPENTRACKER_CONFIGURATION['exit_on_fatal_errors'] = true;

// When enabled, phpOpenTracker will log debugging, error and warning
// messages to a logfile.
// $PHPOPENTRACKER_CONFIGURATION['log_errors'] = false;

// Path/Filename for the above logfile.
// $PHPOPENTRACKER_CONFIGURATION['logfile'] = 'error.log';

// Mapping of client ids to client names.
// Currently only used by the simple_report example application.
// $PHPOPENTRACKER_CONFIGURATION['clients'][1] = $_SERVER['HTTP_HOST'];

?>
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

require 'phpOpenTracker.php';

if (isset($_GET['url'])) {
  $exitURL = str_replace('&amp;', '&', base64_decode($_GET['url']));

  $config    = &phpOpenTracker_Config::getConfig();
  $db        = &phpOpenTracker_DB::getInstance();

  $container = &phpOpenTracker_Container::getInstance(
    array(
      'initNoSetup' => true
    )
  );

  $db->query(
    sprintf(
      "UPDATE %s
          SET exit_target_id = '%d'
        WHERE accesslog_id   = '%d'
          AND document_id    = '%d'
          AND timestamp      = '%d'",

      $config['accesslog_table'],
      $db->storeIntoDataTable($config['exit_targets_table'], $exitURL),
      $container['accesslog_id'],
      $container['document_id'],
      $container['timestamp']
    )
  );

  header('Location: http://' . $exitURL);
}
?>

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

$config = &phpOpenTracker_Config::getConfig();
$db     = &phpOpenTracker_DB::getInstance();

printf(
  "Benchmark for phpOpenTracker %s\n\n",

  PHPOPENTRACKER_VERSION
);

$db->query(
  sprintf(
    'SELECT COUNT(*) AS num_rows
       FROM %s',

    $config['accesslog_table']
  )
);

$row = $db->fetchRow();

printf(
  "  %-24s %s\n",

  'Rows in pot_acceslog:',
  $row['num_rows']
);

$db->query(
  sprintf(
    'SELECT COUNT(*) AS num_rows
       FROM %s',

    $config['visitors_table']
  )
);

$row = $db->fetchRow();

printf(
  "  %-24s %s\n\n",

  'Rows in pot_visitors:',
  $row['num_rows']
);

// ---

function getMicrotime() {
    $microtime = explode(' ', microtime());
    return $microtime[1] . substr($microtime[0], 1);
}

function printResult($test, $start, $end) {
    printf(
      "  %-24s %s seconds\n",

      $test . ':',
      bcsub($end, $start, 12)
    );
}

// --- Page Impressions ---

$start = getMicrotime();

phpOpenTracker::get(
  array(
   'api_call' => 'page_impressions'
  )
);


$end = getMicrotime();

printResult(
  'Page Impressions',
  $start,
  $end
);

// --- Unique Visitors ---

$start = getMicrotime();

phpOpenTracker::get(
  array(
   'api_call' => 'num_unique_visitors'
  )
);


$end = getMicrotime();

printResult(
  'Unique Visitors',
  $start,
  $end
);

// --- Visits ---

$start = getMicrotime();

phpOpenTracker::get(
  array(
   'api_call' => 'visits'
  )
);


$end = getMicrotime();

printResult(
  'Visits',
  $start,
  $end
);

// --- Top Documents ---

$start = getMicrotime();

phpOpenTracker::get(
  array(
   'api_call' => 'top',
   'what'     => 'document'
  )
);

$end = getMicrotime();

printResult(
  'Top Documents',
  $start,
  $end
);

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

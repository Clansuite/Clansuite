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

ob_start();

header('Content-type: image/gif');
header('P3P: CP="NOI NID ADMa OUR IND UNI COM NAV"');
header('Expires: Sat, 22 Apr 1978 02:19:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

printf(
  '%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%',
  71,73,70,56,57,97,1,0,1,0,128,255,0,192,192,192,0,0,0,33,249,4,1,0,0,0,0,44,0,0,0,0,1,0,1,0,0,2,2,68,1,0,59
);

require 'phpOpenTracker.php';

if ( isset($_GET['document_url']) &&
    !empty($_GET['document_url'])) {
  $parameters['document_url'] = base64_decode($_GET['document_url']);
}

else if (isset($_SERVER['HTTP_REFERER'])) {
  $parameters['document_url'] = $_SERVER['HTTP_REFERER'];
}

if (!isset($parameters['document_url'])) {
  exit;
}

if ( isset($_GET['document']) &&
    !empty($_GET['document'])) {
  $parameters['document'] = $_GET['document'];
} else {
  $parameters['document'] = $parameters['document_url'];
}

$parameters['client_id'] = isset($_GET['client_id']) ? $_GET['client_id']              : 1;
$parameters['referer']   = isset($_GET['referer'])   ? base64_decode($_GET['referer']) : '';

if (   isset($_GET['add_data']) &&
    is_array($_GET['add_data'])) {
  foreach ($_GET['add_data'] as $data) {
    list($field, $value) = explode('::', $data);

    $parameters['add_data'][$field] = $value;
  }
}

phpOpenTracker::log($parameters);
?>

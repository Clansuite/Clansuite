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

switch ($_GET['what']) {
  case 'access_statistics': {
    $time = time();

    phpOpenTracker::plot(
      array(
        'api_call'  => 'access_statistics',
        'client_id' => isset($_GET['client_id']) ? $_GET['client_id'] : 1,
        'start'     => isset($_GET['start'])     ? $_GET['start']     : mktime( 0, 0, 0, date('m', $time),   1, date('Y', $time)),
        'end'       => isset($_GET['end'])       ? $_GET['end']       : mktime( 0, 0, 0, date('m', $time)+1, 0, date('Y', $time)),
        'interval'  => isset($_GET['interval'])  ? $_GET['interval']  : 'day',
        'width'     => isset($_GET['width'])     ? $_GET['width']     : 640,
        'height'    => isset($_GET['height'])    ? $_GET['height']    : 480
      )
    );
  }
  break;
}
?>

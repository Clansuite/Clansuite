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
 * phpOpenTracker API - Plot Top
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class phpOpenTracker_API_plot_top extends phpOpenTracker_API_Plugin {
  /**
  * API Calls
  *
  * @var array $apiCalls
  */
  var $apiCalls = array('top');

  /**
  * API Type
  *
  * @var string $apiType
  */
  var $apiType = 'plot';

  /**
  * Runs the phpOpenTracker API call.
  *
  * @param  array $parameters
  * @return mixed
  * @access public
  */
  function run($parameters) {
    $parameters['api_call']      = 'top';
    $parameters['result_format'] = 'separate_result_arrays';

    list($names, $values, $percent, $total) = phpOpenTracker::get(
      $parameters
    );

    $title = 'Top ' . $parameters['limit'] . ' ';

    switch ($parameters['what']) {
      case 'document': {
        $title .= 'Pages';
      }
      break;

      case 'entry_document': {
        $title .= 'Entry Pages';
      }
      break;

      case 'exit_document': {
        $title .= 'Exit Pages';
      }
      break;

      case 'exit_target': {
        $title .= 'Exit Targets';
      }
      break;

      case 'host': {
        $title .= 'Hosts';
      }
      break;

      case 'operating_system': {
        $title .= 'Operating Systems';
      }
      break;

      case 'referer': {
        $title .= 'Referers';
      }
      break;

      case 'user_agent': {
        $title .= 'User Agents';
      }
      break;
    }

    $title .= " (Total: $total)";

    for ($i = 0, $numValues = sizeof($values); $i < $numValues; $i++) {
      $legend[$i] = sprintf(
        '%s (%s, %s%%%%)',

        $names[$i],
        $values[$i],
        $percent[$i]
      );
    }

    $graph = new PieGraph($parameters['width'], $parameters['height'], 'auto');
    $graph->SetShadow();

    $graph->title->Set($title);
    $graph->title->SetFont($parameters['font'], $parameters['font_style'], $parameters['font_size']);
    $graph->title->SetColor('black');
    $graph->legend->Pos(0.1, 0.2);

    $plot = new PiePlot3d($values);
    $plot->SetTheme('sand');
    $plot->SetCenter(0.4);
    $plot->SetAngle(30);
    $plot->value->SetFont($parameters['font'], $parameters['font_style'], $parameters['font_size'] - 2);
    $plot->SetLegends($legend);

    $graph->Add($plot);
    $graph->Stroke();
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

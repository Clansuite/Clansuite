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
 * phpOpenTracker Parser for Hostname,
 * Operating System and User Agent information.
 *
 * The regular expressions used in this class are taken from
 * the ModLogAn (http://jan.kneschke.de/projects/modlogan/)
 * project.
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class phpOpenTracker_Parser {
  /**
  * Parses a given string for Hostname information.
  *
  * @param  string $string
  * @return string
  * @access public
  * @static
  */
  function hostname($string) {
    return phpOpenTracker_Parser::match(
      $string,
      phpOpenTracker_Parser::readRules(POT_CONFIG_PATH . 'hosts.ini')
    );
  }

  /**
  * Parses a given string for Operating System and
  * User Agent information.
  *
  * @param  string $string
  * @return array
  * @access public
  * @static
  */
  function userAgent($string) {
    if (preg_match('#\((.*?)\)#', $string, $tmp)) {
      $elements   = explode(';', $tmp[1]);
      $elements[] = $string;
    } else {
      $elements = array($string);
    }

    if ($elements[0] != 'compatible') {
      $elements[] = substr($string, 0, strpos($string, '('));
    }

    $result['operating_system'] = phpOpenTracker_Parser::match(
      $elements,
      phpOpenTracker_Parser::readRules(
        POT_CONFIG_PATH . 'operating_systems.ini'
      )
    );

    $result['user_agent'] = phpOpenTracker_Parser::match(
      $elements,
      phpOpenTracker_Parser::readRules(
        POT_CONFIG_PATH . 'user_agents.ini'
      )
    );

    return $result;
  }

  /**
  * Matches a string against a set of regular expressions.
  *
  * @param  mixed   $elements
  * @param  array   $rules
  * @return string
  * @access public
  * @static
  */
  function match($elements, $rules) {
    if (!is_array($elements)) {
      $noMatch  = $elements;
      $elements = array($elements);
    } else {
      $noMatch = 'Not identified';
    }

    foreach ($rules as $rule) {
      if (!isset($result)) {
        foreach ($elements as $element) {
          $element = trim($element);
          $pattern = trim($rule['pattern']);

          if (preg_match($pattern, $element, $tmp)) {
            $result = str_replace(
              array('$1', '$2', '$3'),
              array(
                isset($tmp[1]) ? $tmp[1] : '',
                isset($tmp[2]) ? $tmp[2] : '',
                isset($tmp[3]) ? $tmp[3] : ''
              ),
              trim($rule['string'])
            );

            break;
          }
        }
      } else {
        break;
      }
    }

    return isset($result) ? $result : $noMatch;
  }

  /**
  * Reads a set of regular expressions from a given file.
  *
  * @param  string $rulesFile
  * @return array
  * @access public
  * @static
  */
  function readRules($rulesFile) {
    $rules = array();

    if ($file = @file($rulesFile)) {
      $index    = 0;
      $numLines = sizeof($file);

      for ($i = 0; $i < $numLines; $i += 3) {
        $rules[$index]['pattern'] = $file[$i];
        $rules[$index]['string']  = $file[$i+1];
        $index++;
      }
    } else {
      return phpOpenTracker::handleError(
        sprintf(
          'Cannot open "%s".',
          $rulesFile
        ),
        E_USER_ERROR
      );
    }

    return $rules;
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

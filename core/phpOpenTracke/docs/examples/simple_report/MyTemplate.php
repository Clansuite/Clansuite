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
 * MyTemplate
 *
 * This is my template class. I like it. You don't have to :-)
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class MyTemplate {
  /**
  * @var  string
  */
  var $template = '';

  /**
  * @var  array
  */
  var $keys = array();

  /**
  * @var  array
  */
  var $values = array();

  /**
  * Constructor
  *
  * @param  string  file
  * @access public
  */
  function MyTemplate($file = '') {
    $this->setFile($file);
  }

  /**
  * Set template file
  *
  * @param  string  file
  * @access public
  */
  function setFile($file) {
    if ($file != '' && file_exists($file)) {
      $this->template = implode('', @file($file));
      return true;
    } else {
      return false;
    }
  }

  /**
  * Assign template variable(s)
  *
  * @param  mixed   key(s)
  * @param  mixed   value(s)
  * @access public
  */
  function setVar($keys, $values) {
    if (is_array($keys) && is_array($values) && sizeof($keys) == sizeof($values)) {
      foreach ($keys as $key) {
        $this->keys[] = '{' . $key . '}';
      }

      $this->values = array_merge($this->values, $values);
    } else {
      $this->keys[]   = '{' . $keys . '}';
      $this->values[] = $values;
    }
  }

  /**
  * Parse template
  *
  * @return string  parsed template
  * @access public
  */
  function parse() {
    if (!empty($this->template)) {
      return str_replace($this->keys, $this->values, $this->template);
    } else {
      trigger_error(
        'No template file loaded or template is empty.',
        E_USER_WARNING
      );
    }
  }

  /**
  * Parse and print template
  *
  * @access public
  */
  function pParse() {
    print $this->parse();
  }
}
?>

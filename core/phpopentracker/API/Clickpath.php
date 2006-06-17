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
 * Clickpath
 *
 * @author      Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright   Copyright &copy; 2000-2005 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license     http://www.apache.org/licenses/LICENSE-2.0 The Apache License, Version 2.0
 * @category    phpOpenTracker
 * @package     phpOpenTracker
 * @since       phpOpenTracker 1.0.0
 */
class phpOpenTracker_Clickpath {
  /**
  * Count
  *
  * @var integer $count
  */
  var $count;

  /**
  * Length
  *
  * @var integer $length
  */
  var $length;

  /**
  * documents
  *
  * @var array $documents
  */
  var $documents;

  /**
  * document_urls
  *
  * @var array $document_urls
  */
  var $document_urls;

  /**
  * Statistics
  *
  * @var array $statistics
  */
  var $statistics;

  /**
  * Constructor.
  *
  * @param  array   $documents
  * @param  array   $document_urls
  * @param  array   $statistics
  * @param  integer $count
  * @access public
  */
  function phpOpenTracker_Clickpath($documents, $document_urls = array(), $statistics = array(), $count = 1) {
    $this->documents     = $documents;
    $this->document_urls = $document_urls;
    $this->count         = $count;
    $this->length        = sizeof($documents);
    $this->statistics    = $statistics;
  }

  /**
  * Returns GraphViz/dot markup for the graph.
  *
  * @param  boolean $returnObject
  * @return mixed
  * @access public
  */
  function toGraph($returnObject = false) {
    if (!@include_once('Image/GraphViz.php')) {
      phpOpenTracker::handleError(
        'Could not find PEAR Image_GraphViz package, exiting.',
        E_USER_ERROR
      );
    }

    $graph = new Image_GraphViz();

    for ($i = 0; $i < $this->length - 1; $i++) {
      $graph->addNode(
        $i,
        array(
          'url'   => $this->document_urls[$i],
          'label' => $this->documents[$i],
          'shape' => 'box'
        )
      );

      $graph->addNode(
        $i+1,
        array(
          'url'   => $this->document_urls[$i+1],
          'label' => $this->documents[$i+1],
          'shape' => 'box'
        )
      );

      if (isset($this->statistics[$i]['count'])) {
        $label = sprintf(
          'count: %d\naverage time: %d seconds',

          $this->statistics[$i]['count'],
          $this->statistics[$i]['time_spent']
        );
      } else {
        $label = sprintf(
          'time spent: %d seconds',

          $this->statistics[$i]
        );
      }

      $graph->addEdge(
        array(
          $i => $i+1
        ),
        array(
          'label' => $label
        )
      );
    }

    if ($returnObject) {
      return $graph;
    } else {
      return $graph->parse();
    }
  }

  /**
  * Returns XML markup for the graph.
  *
  * @param  boolean $returnObject
  * @return mixed
  * @access public
  */
  function toXML($returnObject = false) {
    if (!@include_once('XML/Tree.php')) {
      phpOpenTracker::handleError(
        'Could not find PEAR XML_Tree package, exiting.',
        E_USER_ERROR
      );
    }

    $tree = new XML_Tree;
    $root = &$tree->addRoot('clickpath');

    for ($i = 0; $i < $this->length; $i++) {
      $root->addChild('length', $this->length);

      $node = &$root->addChild('node');

      $node->addChild('document', $this->documents[$i]);

      if (!isset($this->statistics[$i]['count'])) {
        $node->addChild('timespent', $this->statistics[$i]);
      }
    }

    if (!$returnObject) {
      return $root->get();
    } else {
      return $root;
    }
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>

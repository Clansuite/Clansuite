/**
 * @file       /themes/core/javascript/codemirror_config.js       
 * @desc       CodeMirror Configuration File for "Clansuite - just an eSports CMS"
 * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005 - onwards)
 * @link       http://www.clansuite.com
 * @version    SVN: $Id$
 */

$(document).ready(function() {

    var textarea = document.getElementById('codecontent');

    // For a CodeMirror display with editor toolbar and buttons
	var editor = new MirrorFrame(CodeMirror.replace(textarea), {

	// For a pure textarea replacement
	//var editor = CodeMirror.fromTextArea(textarea, {

      // The content parameter is a standard DOM fetch of the text box contents which we are replacing.
      content: textarea.value,

      // The path parameter specifies the location where the script will find the parser and tokenizers files.
	  path: "/libraries/codemirror/js/",

	  // The parserfile parameter specifies which parsers and tokenizer to load.
	  // The following will enable mixed syntax highlighting by combining several parsers.
	  parserfile: ["tokenizephp.js",
	               "parsephp.js",
	               "parsephphtmlmixed.js",
	               "parsexml.js",
	               "parsecss.js",
	               "tokenizejavascript.js",
                   "parsejavascript.js",
                   "parsehtmlmixed.js"],

	  // The stylesheet parameter defines the CSS to pull in for each corresponding parser.
	  stylesheet: ["/libraries/codemirror/css/xmlcolors.css",
                   "/libraries/codemirror/css/jscolors.css",
                   "/libraries/codemirror/css/csscolors.css"],

	  autoMatchParens: true,
      width: '100%',
      Height: '100%',
      textWrapping: false,
      lineNumbers: true,
      tabMode: 'spaces',
      iframeClass: 'ifc',
      indentUnit: 4

	});
});
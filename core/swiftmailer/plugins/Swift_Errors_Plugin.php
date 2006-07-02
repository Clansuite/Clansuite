<?php

/**
 * Error handling plugin for Swift Mailer, a PHP Mailer class.
 *
 * @package	Swift
 * @version	>= 0.0.4
 * @author	Chris Corbyn
 * @date	8th June 2006
 * @license	http://www.gnu.org/licenses/lgpl.txt Lesser GNU Public License
 *
 * @copyright Copyright &copy; 2006 Chris Corbyn - All Rights Reserved.
 * @filesource
 * 
 *   This library is free software; you can redistribute it and/or
 *   modify it under the terms of the GNU Lesser General Public
 *   License as published by the Free Software Foundation; either
 *   version 2.1 of the License, or (at your option) any later version.
 *
 *   This library is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *   Lesser General Public License for more details.
 *
 *   You should have received a copy of the GNU Lesser General Public
 *   License along with this library; if not, write to
 *
 *   The Free Software Foundation, Inc.,
 *   51 Franklin Street,
 *   Fifth Floor,
 *   Boston,
 *   MA  02110-1301  USA
 *
 *    "Chris Corbyn" <chris@w3style.co.uk>
 *
 */

class Swift_Errors_Plugin implements Swift_IPlugin
{
	/**
	 * Name of the plugin (identifier)
	 * @var string plugin id
	 */
	public $pluginName = 'Errors';
	/**
	 * Contains a reference to the main swift object.
	 * @var object swiftInstance
	 */
	private $swiftInstance;
	/**
	 * The norm is the echo and continue.
	 * Settting this to TRUE makes it echo the die()
	 * @var bool halt
	 */
	private $halt;
	
	/**
	 * Constructor.
	 * @param bool halt (if the script should die() on error)
	 */
	public function __construct($halt=false)
	{
		$this->halt = (bool) $halt;
	}
	/**
	 * Load in Swift
	 * @param object SwiftInstance
	 */
	public function loadBaseObject(&$object)
	{
		$this->swiftInstance =& $object;
	}
	/**
	 * Event handler for onError
	 */
	public function onError()
	{
		$this_error = $this->swiftInstance->lastError;
		
		$error_info = $this->getErrorStartPoint();
		
		if (!empty($error_info['class'])) $class = $error_info['class'].'::';
		else $class = '';
		
		$file_info = ' near '.$class.$error_info['function'].
			' in <strong>'.$error_info['file'].'</strong> on line <strong>'.
			$error_info['line'].'</strong><br />';
		
		$output = '<br />'.$this_error.$file_info;
		echo $output;
		if ($this->halt) exit();
	}
	/**
	 * Get the command that caused the error
	 */
	private function getErrorStartPoint()
	{
		$trace = debug_backtrace();
		$start = array_pop($trace);
		return array(
			'file' => $start['file'],
			'line' => $start['line'],
			'class' => $start['class'],
			'function' => $start['function']
		);
	}
}

?>
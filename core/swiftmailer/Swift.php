<?php

/**
 * Swift Mailer: A Flexible PHP Mailer Class.
 *
 * Current functionality:
 *  
 *  * Send uses one single connection to the SMTP server
 *  * Doesn't rely on mail()
 *  * Unlimited redundant connections (via plugin)
 *  * Custom Headers
 *  * Sends Multipart messages, handles encoding
 *  * Sends Plain-text single-part emails
 *  * Fast Cc and Bcc handling
 *  * Set Priority Level
 *  * Request Read Receipts
 *  * Batch emailing with multiple To's or without
 *  * Support for multiple attachments
 *  * Sendmail (or other binary) support
 *  * Pluggable SMTP Authentication (LOGIN, PLAIN, MD5-CRAM, POP Before SMTP)
 *  * Secure Socket Layer connections (SSL)
 *  * Transport Layer security (TLS) - Gmail account holders!
 *  * Send mail with inline embedded images easily!
 *  * Loadable plugin support with event handling features
 * 
 * @package	Swift
 * @version	1.3.1
 * @author	Chris Corbyn
 * @date	28th June 2006
 * @license http://www.gnu.org/licenses/lgpl.txt Lesser GNU Public License
 *
 * @copyright Copyright &copy; 2006 Chris Corbyn - All Rights Reserved.
 * @filesource
 * 
 * -----------------------------------------------------------------------
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

if (!defined('SWIFT_VERSION')) define('SWIFT_VERSION', '1.3.1');

if (substr(PHP_VERSION, 0, 1) != 5) exit("<br /><strong style=\"background: #ee6666;\">SWIFT ERROR:</strong> This version of Swift is for PHP5 only, make sure you have the correct version.<br />");

/**
 * Swift Plugin Interface. Describes the methods which plugins should implement
 * @package Swift
 */
interface Swift_IPlugin
{
	/**
	 * Required Properties
	 *
	 * private SwiftInstance;
	 * public pluginName;
	 */
	
	/**
	 * Loads an instance of Swift to the Plugin
	 * @param  object  SwiftInstance
	 * @return  void
	 */
	public function loadBaseObject(&$object);
	
	/**
	 * Optional Methods to implement
	 *
	 * public function onLoad();
	 * public function onClose();
	 * public function onFail();
	 * public function onError();
	 * public function onBeforeSend();
	 * public function onSend();
	 * public function onBeforeCommand();
	 * public function onCommand();
	 * public function onLog();
	 * public function onAuthenticate();
	 * public function onFlush();
	 * public function onResponse();
	 */
}

/**
 * Swift Authenticator Interface. Describes the methods which authenticators should implement
 * @package	Swift
 */
interface Swift_IAuthenticator
{
	/**
	 * Required Properties
	 * private SwiftInstance;
	 * public serverString;
	 */
	
	/**
	 * Loads an instance of Swift to the Plugin
	 * @param  object  SwiftInstance
	 * @return  void
	 */
	public function loadBaseObject(&$object);
	/**
	 * Executes the logic in the authentication mechanism
	 * @param  string  username
	 * @param  string  password
	 * @return  bool  successful
	 */
	public function run($username, $password);
	
}

/**
 * Swift Connection Handler Interface.
 * Describes the methods which connection handlers should implement
 * @package	Swift
 */
interface Swift_IConnection
{
	/**
	 * Required properties
	 *
	 * public readHook;
	 * public writeHook;
	 * public error
	 */
	
	/**
	 * Establishes a connection with the MTA
	 * @return  bool  connected
	 */
	public function start();
	/**
	 * Closes the connection with the MTA
	 * @return  void
	 */
	public function stop();
	/**
	 * Returns a boolean value TRUE if the connection is active.
	 * @return bool connected
	 */
	public function isConnected();
}

/**
 * Swift Mailer Class.
 * Accepts connections to an MTA and deals with the sending and processing of
 * commands and responses.
 * @package	Swift
 */
class Swift
{
	/**
	 * Plugins container
	 * @var  array  plugins
	 * @private
	 */
	private $plugins = array();
	private $esmtp = false;
	private $autoCompliance = true;
	/**
	 * Whether or not Swift should send unique emails to all "To"
	 * recipients or just bulk them together in the To header.
	 * @var bool use_exact
	 */
	private $useExactCopy = false;
	private $domain = 'SwiftUser';
	private $mimeBoundary;
	private $mimeWarning;
	/**
	 * MIME Parts container
	 * @var  array  parts
	 * @private
	 */
	private $parts = array();
	/**
	 * Attachment data container
	 * @var  array  attachments
	 * @private
	 */
	private $attachments = array();
	/**
	 * Inline image container
	 * @var  array  image parts
	 * @private
	 */
	private $images = array();
	/**
	 * Response codes expected for commands
	 * $command => $code
	 * @var  array  codes
	 * @private
	 */
	private $expectedCodes = array(
		'ehlo' => 250,
		'helo' => 250,
		'auth' => 334,
		'mail' => 250,
		'rcpt' => 250,
		'data' => 354
	);
	/**
	 * Blind-carbon-copy address container
	 * @var array addresses
	 */
	private $Bcc = array();
	/**
	 * Carbon-copy address container
	 * @var array addresses
	 */
	private $Cc = array();
	/**
	 * The address any replies will go to
	 * @var string address
	 */
	private $replyTo;
	/**
	 * The addresses we're sending to
	 * @var string address
	 */
	private $to = array();
	/**
	 * Priority value 1 (high) to 5 (low)
	 * @var int priority (1-5)
	 */
	private $priority = 3;
	/**
	 * Whether a read-receipt is required
	 * @var bool read receipt
	 */
	private $readReceipt = false;
	/**
	 * The max number of entires that can exist in the log
	 * (saves memory)
	 * @var int log size
	 */
	private $maxLogSize = 30;
	/**
	 * The address to which bounces are sent
	 * @var string Return-Path:
	 */
	private $returnPath;
	
	/**
	 * Connection object (container holding a socket)
	 * @var  object  connection
	 */
	public $connection;
	/**
	 * Authenticators container
	 * @var  array  authenticators
	 */
	public $authenticators = array();
	public $authTypes = array();
	/**
	 * Holds the username used in authentication (if any)
	 * @var string username
	 */
	public $username;
	/**
	 * Holds the password used in authentication (if any)
	 * @var string password
	 */
	public $password;
	
	public $charset = "ISO-8859-1";
	/**
	 * Boolean value representing if Swift has failed or not
	 * @var  bool  failed
	 */
	public $failed = false;
	/**
	 * If Swift should clear headers etc automatically
	 * @var bool autoFlush
	 */
	public $autoFlush = true;
	/**
	 * Numeric code from the last MTA response
	 * @var  int  code
	 */
	public $responseCode;
	/**
	 * Keyword of the command being sent
	 * @var string keyword
	 */
	public $commandKeyword;
	/**
	 * Last email sent or email about to be sent (dependant on location)
	 * @var  array  commands
	 */
	public $currentMail = array();
	/**
	 * Email headers
	 * @var  string  headers
	 */
	public $headers;
	public $currentCommand = '';
	/**
	 * Errors container
	 * @var  array  errors
	 */
	public $errors = array();
	/**
	 * Log container
	 * @var  array  transactions
	 */
	public $transactions = array();
	
	public $lastTransaction;
	public $lastError;
	/**
	 * The very most recent response received from the MTA
	 * @var  string  response
	 */
	public $lastResponse;
	
	/**
	 * Swift Constructor
	 * @param  object  Swift_IConnection
	 * @param  string  user_domain, optional
	 */
	public function __construct(Swift_IConnection &$object, $domain=false)
	{
		if (!$domain) $domain = !empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'SwiftUser';
		
		$this->domain = $domain;
		$this->connection =& $object;

		$this->connect();
		// * Hey this library is FREE so it's not much to ask ;)  But if you really do want to
		// remove this header then go ahead of course... what's GPL for? :P
		$this->headers = "X-Mailer: Swift ".SWIFT_VERSION." by Chris Corbyn\r\n";
		$this->mimeWarning = "This part of the E-mail should never be seen. If\r\n".
		"you are reading this, consider upgrading your e-mail\r\n".
		"client to a MIME-compatible client.";
	}
	/**
	 * Connect to the server
	 * @return bool connected
	 */
	public function connect()
	{
		if (!$this->connection->start())
		{
			$this->fail();
			$error = 'Connection to the given MTA failed.';
			if (!empty($this->connection->error)) $error .= ' The Connection Interface said: '.$this->connection->error;
			$this->logError($error, 0);
			return false;
		}
		else
		{
			$this->handshake();
			return true;
		}
	}
	/**
	 * Returns TRUE if the connection is active.
	 */
	public function isConnected()
	{
		return $this->connection->isConnected();
	}
	/**
	 * Sends the standard polite greetings to the MTA and then
	 * identifies the MTA's capabilities
	 */
	public function handshake()
	{
		$this->commandKeyword = "";
		//What did the server greet us with on connect?
		$this->logTransaction();
		if ($this->supportsESMTP($this->lastResponse))
		{
			//Just being polite
			$list = $this->command("EHLO {$this->domain}\r\n");
			
			$this->getAuthenticationMethods($list);
			
			$this->esmtp = true;
		}
		else $this->command("HELO {$this->domain}\r\n");
	}
	/**
	 * Checks for Extended SMTP support
	 * @param  string  MTA greeting
	 * @return  bool  ESMTP
	 * @private
	 */
	private function supportsESMTP($greeting)
	{
		//Not mentiioned in RFC 2821 but this how it's done
		if (strpos($greeting, 'ESMTP')) return true;
		else return false;
	}
	/**
	 * Set the maximum num ber of entries in the log
	 * @param int size
	 */
	public function setMaxLogSize($size)
	{
		$this->maxLogSize = (int) $size;
	}
	/**
	 * Sets the priority level of the email
	 * This must be 1 to 5 where 1 is highest
	 * @param int priority
	 */
	public function setPriority($level)
	{
		$level = (int) $level;
		if ($level < 1) $level = 1;
		if ($level > 5) $level = 5;
		switch ($level)
		{
			case 1: case 2:
			$this->addHeaders("X-Priority: $level\r\nX-MSMail-Priority: High");
			break;
			case 4: case 5:
			$this->addHeaders("X-Priority: $level\r\nX-MSMail-Priority: Low");
			break;
			case 3: default:
			$this->addHeaders("X-Priority: $level\r\nX-MSMail-Priority: Normal");
		}
	}
	/**
	 * Set the return path address (Bounce detection)
	 * @param string address
	 */
	public function setReturnPath($address)
	{
		$this->returnPath = $this->getAddress($address);
	}
	/**
	 * Request a read receipt from all recipients
	 * @param bool request receipt
	 */
	public function requestReadReceipt($request=true)
	{
		$this->readReceipt = (bool) $request;
	}
	/**
	 * Set the character encoding were using
	 * @param string charset
	 */
	public function setCharset($string="UTF-8")
	{
		$this->charset = $string;
	}
	/**
	 * Whether or not Swift should send unique emails to all To recipients
	 * @param bool unique
	 */
	public function useExactCopy($use=true)
	{
		$this->useExactCopy = (bool) $use;
	}
	/**
	 * Sets the Reply-To address used for sending mail
	 * @param string address
	 */
	public function setReplyTo($string)
	{
		$this->replyTo = $this->getAddress($string);
	}
	/**
	 * Add one or more Blind-carbon-copy recipients to the mail
	 * @param mixed addresses
	 */
	public function addBcc($addresses)
	{
		$this->Bcc = array_merge($this->Bcc, $this->parseAddressList((array) $addresses));
	}
	/**
	 * Add one or more Carbon-copy recipients to the mail
	 * @param mixed addresses
	 */
	public function addCc($addresses)
	{
		$this->Cc = array_merge($this->Cc, $this->parseAddressList((array) $addresses));
	}
	/**
	 * Force swift to break lines longer than 76 characters long
	 * @param  bool  resize
	 */
	public function useAutoLineResizing($use=true)
	{
		$this->autoCompliance = (bool) $use;
	}
	/**
	 * Associate a code with a command. Swift will fail quietly if the code
	 * returned does not match.
	 * @param  string  command
	 * @param  int  code
	 */
	public function addExpectedCode($command, $code)
	{
		$this->expectedCodes[$command] = (int) $code;
	}
	/**
	 * Reads the EHLO return string to see what AUTH methods are supported
	 * @param  string  EHLO response
	 * @return  void
	 * @private
	 */
	private function getAuthenticationMethods($list)
	{
		preg_match("/^250[\-\ ]AUTH\ (.*)\r\n/m", $list, $matches);
		if (!empty($matches[1]))
		{
			$types = explode(' ', $matches[1]);
			$this->authTypes = $types;
		}
	}
	/**
	 * Load a plugin object into Swift
	 * @param  object  Swift_IPlugin
	 * @param string plugin name
	 * @return  void
	 */
	public function loadPlugin(Swift_IPlugin &$object, $id=false)
	{
		if ($id) $object->pluginName = $id;
		$this->plugins[$object->pluginName] =& $object;
		$this->plugins[$object->pluginName]->loadBaseObject($this);

		if (method_exists($this->plugins[$object->pluginName], 'onLoad'))
		{
			$this->plugins[$object->pluginName]->onLoad();
		}
	}
	/**
	 * Fetch a reference to a plugin in Swift
	 * @param  string  plugin name
	 * @return  object  Swift_IPlugin
	 */
	public function &getPlugin($name)
	{
		if (isset($this->plugins[$name]))
		{
			return $this->plugins[$name];
		}
	}
	/**
	 * Un-plug a loaded plugin. Returns false on failure.
	 * @param string plugin_name
	 * @return bool success
	 */
	public function removePlugin($name)
	{
		if (!isset($this->plugins[$name])) return false;
		
		if (method_exists($this->plugins[$name], 'onUnload'))
		{
			$this->plugins[$name]->onUnload();
		}
		unset($this->plugins[$name]);
		return true;
	}
	/**
	 * Trigger event handlers
	 * @param  string  event handler
	 * @return  void
	 * @private
	 */
	private function triggerEventHandler($func)
	{
		foreach ($this->plugins as $name => $object)
		{
			if (method_exists($this->plugins[$name], $func))
			{
				$this->plugins[$name]->$func();
			}
		}
	}
	/**
	 * Attempt to load any authenticators from the Swift/ directory
	 * @see  RFC 2554
	 * @return  void
	 * @private
	 */
	private function loadDefaultAuthenticators()
	{
		$dir = dirname(__FILE__).'/Swift';
		if (file_exists($dir) && is_dir($dir))
		{
			$handle = opendir($dir);
			while ($file = readdir($handle))
			{
				if (preg_match('@^(Swift_\w*?_Authenticator)\.php$@', $file, $matches))
				{
					require_once($dir.'/'.$file);
					$class = $matches[1];
					$this->loadAuthenticator(new $class);
				}
			}
			closedir($handle);
		}
	}
	/**
	 * Use SMTP authentication
	 * @param  string  username
	 * @param  string  password
	 * @return  bool  successful
	 */
	public function authenticate($username, $password)
	{
		$this->username = $username;
		$this->password = $password;
	
		if (empty($this->authenticators)) $this->loadDefaultAuthenticators();
		
		if (!$this->esmtp || empty($this->authTypes))
		{
			$this->logError('The MTA doesn\'t support any of Swift\'s loaded authentication mechanisms', 0);
			return false;
		}
		foreach ($this->authenticators as $name => $object)
		{
			//An asterisk means that the auth type is not advertised by ESMTP
			if (in_array($name, $this->authTypes) || substr($name, 0, 1) == '*')
			{
				if ($this->authenticators[$name]->run($username, $password))
				{
					$this->triggerEventHandler('onAuthenticate');
					return true;
				}
				else return false;
			}
		}
		//If we get this far, no authenticators were used
		$this->logError('The MTA doesn\'t support any of Swift\'s loaded authentication mechanisms', 0);
		$this->fail();
		return false;
	}
	/**
	 * Load an authentication mechanism object into Swift
	 * @param  object  Swift_IAuthenticator
	 * @return  void
	 */
	public function loadAuthenticator(Swift_IAuthenticator &$object)
	{
		$this->authenticators[$object->serverString] =& $object;
		$this->authenticators[$object->serverString]->loadBaseObject($this);
	}
	/**
	 * Get a unique multipart MIME boundary
	 * @param  string  mail data, optional
	 * @return  string  boundary
	 * @private
	 */
	private function getMimeBoundary($string=false)
	{
		$force = true;
		if (!$string)
		{
			$force = false;
			$string = implode('', $this->parts);
			$string .= implode('', $this->attachments);
		}
		if ($this->mimeBoundary && !$force) return $this->mimeBoundary;
		else
		{ //Make sure we don't (as if it would ever happen!) -
		  // produce a hash that's actually in the email already
			do
			{
				$this->mimeBoundary = 'swift-'.strtoupper(md5($string.microtime()));
			} while(strpos($string, $this->mimeBoundary));
		}
		return $this->mimeBoundary;
	}
	/**
	 * Append a string to the message header
	 * @param  string  headers
	 * @return  void
	 */
	public function addHeaders($string)
	{
		$this->headers .= $string;
		if (substr($this->headers, -2) != "\r\n")
			$this->headers .= "\r\n";
	}
	/**
	 * Set the multipart MIME boundary (only works for first part)
	 * @param  string  boundary
	 * @return  void
	 */
	public function setMimeBoundary($string)
	{
		$this->mimeBoundary = $string;
	}
	/**
	 * Set the text that displays in non-MIME clients
	 * @param  string  warning
	 * @return  void
	 */
	public function setMimeWarning($warning)
	{
		$this->mimeWarning = $warning;
	}
	/**
	 * Tells Swift to clear out attachment, parts, headers etc
	 * automatically upon sending - this is the default.
	 * @param bool flush
	 */
	public function autoFlush($flush=true)
	{
		$this->autoFlush = (bool) $flush;
	}
	/**
	 * Empty out the MIME parts and attachments
	 * @param  bool  reset headers
	 * @return  void
	 */
	public function flush($clear_headers=false)
	{
		$this->parts = array();
		$this->attachments = array();
		$this->images = array();
		$this->mimeBoundary = null;
		$this->Bcc = array();
		$this->to = array();
		$this->Cc = array();
		$this->replyTo = null;
		//See comment above the headers property above the constructor before editing this line! *
		if ($clear_headers) $this->headers = "X-Mailer: Swift ".SWIFT_VERSION." by Chris Corbyn\r\n";
		$this->triggerEventHandler('onFlush');
	}
	/**
	 * Reset to
	 */
	public function flushTo()
	{
		$this->to = array();
	}
	/**
	 * Reset Cc
	 */
	public function flushCc()
	{
		$this->Cc = array();
	}
	/**
	 * Reset Bcc
	 */
	public function flushBcc()
	{
		$this->Bcc = array();
	}
	/**
	 * Reset parts
	 */
	public function flushParts()
	{
		$this->parts = array();
		$this->images = array();
	}
	/**
	 * Reset attachments
	 */
	public function flushAttachments()
	{
		$this->attchments = array();
	}
	/**
	 * Reset headers
	 */
	public function flushHeaders()
	{
		$this->headers = "X-Mailer: Swift ".SWIFT_VERSION." by Chris Corbyn\r\n";
	}
	/**
	 * Log an error in Swift::errors
	 * @param  string  error string
	 * @param  int  error number
	 * @return  void
	 */
	public function logError($errstr, $errno=0)
	{
		$this->errors[] = array(
			'num' => $errno,
			'time' => microtime(),
			'message' => $errstr
		);
		$this->lastError = $errstr;
		
		$this->triggerEventHandler('onError');
	}
	/**
	 * Log a transaction in Swift::transactions
	 * @param  string  command
	 * @return  void
	 */
	public function logTransaction($command='')
	{
		$this->lastTransaction = array(
			'command' => $command,
			'time' => microtime(),
			'response' => $this->getResponse()
		);
		$this->triggerEventHandler('onLog');
		if ($this->maxLogSize)
		{
			$this->transactions = array_slice(array_merge($this->transactions, array($this->lastTransaction)), -$this->maxLogSize);
		}
		else $this->transactions[] = $this->lastTransaction;
	}
	/**
	 * Read the data from the socket
	 * @return  string  response
	 * @private
	 */
	private function getResponse()
	{
		if (!$this->connection->readHook || !$this->isConnected()) return false;
		$ret = "";
		while (true)
		{
			$tmp = @fgets($this->connection->readHook);
			$ret .= $tmp;
			//The last line of SMTP replies have a space after the status number
			// They do NOT have an EOF so while(!feof($socket)) will hang!
			if (substr($tmp, 3, 1) == ' ' || $tmp == false) break;
		}
		$this->responseCode = $this->getResponseCode($ret);
		$this->lastResponse = $ret;
		$this->triggerEventHandler('onResponse');
		return $this->lastResponse;
	}
	/**
	 * Get the number of the last server response
	 * @param  string  response string
	 * @return  int  response code
	 * @private
	 */
	private function getResponseCode($string)
	{
		return (int) sprintf("%d", $string);
	}
	/**
	 * Get the first word of the command
	 * @param  string  command
	 * @return  string  keyword
	 * @private
	 */
	private function getCommandKeyword($comm)
	{
		if (false !== $pos = strpos($comm, ' '))
		{
			return $this->commandKeyword = strtolower(substr($comm, 0, $pos));
		}
		else return $this->commandKeyword = strtolower(trim($comm));
	}
	/**
	 * Issue a command to the socket
	 * @param  string  command
	 * @return  string  response
	 */
	public function command($comm)
	{
		$this->currentCommand = ltrim($comm);
		
		$this->triggerEventHandler('onBeforeCommand');
		
		if (!$this->connection->writeHook || !$this->isConnected() || $this->failed)
		{
			$this->logError('Error running command: '.trim($comm).'.  No connection available', 0);
			return false;
		}

		$command_keyword = $this->getCommandKeyword($this->currentCommand);
		
		//SMTP commands must end with CRLF
		if (substr($this->currentCommand, -2) != "\r\n") $this->currentCommand .= "\r\n";
		
		if (@fwrite($this->connection->writeHook, $this->currentCommand))
		{
			$this->logTransaction($this->currentCommand);
			if (array_key_exists($command_keyword, $this->expectedCodes))
			{
				if ($this->expectedCodes[$command_keyword] != $this->responseCode)
				{
					$this->fail();
					$this->logError('MTA Error: '.$this->lastResponse, $this->responseCode);
					return $this->hasFailed();
				}
			}
			$this->triggerEventHandler('onCommand');
			return $this->lastResponse;
		}
		else return false;
	}
	/**
	 * Splits lines longer than 76 characters to multiple lines
	 * @param  string  text
	 * @return  string chunked output
	 */
	public function chunkSplitLines($string)
	{
		return wordwrap($string, 74, "\r\n");
	}
	/**
	 * Add a part to a multipart message
	 * @param  string  body
	 * @param  string  content-type, optional
	 * @param  string  content-transfer-encoding, optional
	 * @return  void
	 */
	public function addPart($string, $type='text/plain', $encoding='8bit')
	{
		$body_string = $this->encode($string, $encoding);
		if ($this->autoCompliance && $encoding != 'binary') $body_string = $this->chunkSplitLines($body_string);
		$ret = "Content-Type: $type; charset=\"{$this->charset}\"\r\n".
				"Content-Transfer-Encoding: $encoding\r\n\r\n".
				$body_string;
		
		if (strtolower($type) == 'text/html') $this->parts[] = $this->makeSafe($ret);
		else $this->parts = array_merge((array) $this->makeSafe($ret), $this->parts);
	}
	/**
	 * Add an attachment to a multipart message.
	 * Attachments are added as base64 encoded data.
	 * @param  string  data
	 * @param  string  filename
	 * @param  string  content-type
	 * @return  void
	 */
	public function addAttachment($data, $filename, $type='application/octet-stream')
	{
		$this->attachments[] = "Content-Type: $type; ".
				"name=\"$filename\";\r\n".
				"Content-Transfer-Encoding: base64\r\n".
				"Content-Description: $filename\r\n".
				"Content-Disposition: attachment; ".
				"filename=\"$filename\"\r\n\r\n".
				chunk_split($this->encode($data, 'base64'));
	}
	/**
	 * Insert an inline image and return it's name
	 * These work like attachments but have a content-id
	 * and are inline/related.
	 * @param string path
	 * @return string name
	 */
	public function addImage($path)
	{
		if (!file_exists($path)) return false;
		
		$gpc = ini_get('magic_quotes_gpc');
		ini_set('magic_quotes_gpc', 0);
		$gpc_run = ini_get('magic_quotes_runtime');
		ini_set('magic_quotes_runtime', 0);
		
		$img_data = @getimagesize($path);
		if (!$img_data) return false;
		
		$type = image_type_to_mime_type($img_data[2]);
		$filename = basename($path);
		$data = file_get_contents($path);
		$cid = 'SWM'.md5(uniqid(rand(), true));
		
		$this->images[] = "Content-Type: $type\r\n".
				"Content-Transfer-Encoding: base64\r\n".
				"Content-Disposition: inline; ".
				"filename=\"$filename\"\r\n".
				"Content-ID: <$cid>\r\n\r\n".
				chunk_split($this->encode($data, 'base64'));
		
		ini_set('magic_quotes_gpc', $gpc);
		ini_set('magic_quotes_runtime', $gpc_run);
		
		return 'cid:'.$cid;
	}
	/**
	 * Close the connection in the connecion object
	 * @return  void
	 */
	public function close()
	{
		if ($this->connection->writeHook && $this->isConnected())
		{
			$this->command("QUIT\r\n");
			$this->connection->stop();
		}
		$this->triggerEventHandler('onClose');
	}
	/**
	 * Check if Swift has failed and stopped processing
	 * @return  bool  failed
	 */
	public function hasFailed()
	{
		return $this->failed;
	}
	/**
	 * Force Swift to fail and stop processing
	 * @return  void
	 */
	public function fail()
	{
		$this->failed = true;
		$this->triggerEventHandler('onFail');
	}
	/**
	 * Encode a string (mail) in a given format
	 * Currently supports:
	 *  - BASE64
	 *  - Quoted-Printable
	 *  - Ascii 7-bit
	 *  - Ascii 8bit
	 *  - Binary (not encoded)
	 *
	 * @param  string  input
	 * @param  string  encoding
	 * @return  string  encoded output
	 */
	public function encode($string, $type)
	{
		$type = strtolower($type);
		
		switch ($type)
		{
			case 'base64':
			return base64_encode($string);
			break;
			//
			case 'quoted-printable':
			return $this->quotedPrintableEncode($string);
			//
			case '7bit':
			case '8bit':
			if (strtoupper($this->charset) != 'UTF-8') return utf8_decode($string);
			break;
			case 'binary':
			default:
			break;
		}
		
		return $string;
	}
	/**
	 * Handles quoted-printable encoding
	 * From php.net by user bendi at interia dot pl
	 * @param  string  input
	 * @return  string  encoded output
	 * @private
	 */
	private function quotedPrintableEncode($string)
	{
		$string = preg_replace('/[^\x21-\x3C\x3E-\x7E\x09\x20]/e', 'sprintf( "=%02x", ord ( "$0" ) ) ;', $string);
		preg_match_all('/.{1,73}([^=]{0,3})?/', $string, $matches);
		return implode("=\r\n", $matches[0]);
	}
	/**
	 * Converts lone LF characters to CRLF
	 * @param  string  input
	 * @return  string  converted output
	 */
	public function LFtoCRLF($string)
	{
		return preg_replace("@(?:(?<!\r)\n)|(?:\r(?!\n))@", "\r\n", $string);
	}
	/**
	 * Prevents premature <CRLF>.<CRLF> strings
	 * Converts any lone LF characters to CRLF
	 * @param  string  input
	 * @return  string  escaped output
	 */
	public function makeSafe($string)
	{
		return str_replace("\r\n.", "\r\n..", $this->LFtoCRLF($string));
	}
	/**
	 * Pulls an email address from a "Name" <add@ress> string
	 * @param string input
	 * @return string address
	 */
	public function getAddress($string)
	{
		if (preg_match('/^.*?<([^>]+)>\s*$/', $string, $matches))
		{
			return '<'.$matches[1].'>';
		}
		elseif (!preg_match('/<|>/', $string)) return '<'.$string.'>';
		else return $string;
	}
	/**
	 * Builds the headers needed to reflect who the mail is sent to
	 * Presently this is just the "To: " header
	 * @param  string  address
	 * @return  string  headers
	 * @private
	 */
	private function makeRecipientHeaders($address=false)
	{
		if ($address) return "To: $address\r\n";
		else
		{
			$ret = "To: ".implode(",\r\n\t", $this->to)."\r\n";
			if (!empty($this->Cc)) $ret .= "Cc: ".implode(",\r\n\t", $this->Cc)."\r\n";
			return $ret;
		}
	}
	/**
	 * Structure a given array of addresses into the 1-dim we want
	 * @param array unstructured
	 * @return array structured
	 * @private
	 */
	private function parseAddressList($u_array)
	{
		$ret = array();
		foreach ($u_array as $val)
		{
			if (is_array($val)) $ret[] = '"'.$val[0].'" <'.$val[1].'>';
			else $ret[] = $val;
		}
		return $ret;
	}
	/**
	 * Send an email using Swift (send commands)
	 * @param  string  to_address
	 * @param  string  from_address
	 * @param  string  subject
	 * @param  string  body, optional
	 * @param  string  content-type,optional
	 * @param  string  content-transfer-encoding,optional
	 * @return  bool  successful
	 */
	public function send($to, $from, $subject, $body=false, $type='text/plain', $encoding='8bit')
	{
		$to = (array) $to;
		$this->to = $this->parseAddressList($to);
		//In these cases we just send the one email
		if ($this->useExactCopy || !empty($this->Cc) || !empty($this->Bcc))
		{
			$this->currentMail = $this->buildMail(false, $from, $subject, $body, $type, $encoding, 1);
			$this->triggerEventHandler('onBeforeSend');
			foreach ($this->currentMail as $command)
			{
				if (is_array($command))
				{ //Commands can be returned as 1-dimensional arrays
					foreach ($command as $c)
					{
						if (!$this->command($c))
						{
							$this->logError('Sending failed on command: '.$c, 0);
							return false;
						}
					}
				}
				else if (!$this->command($command))
				{
					$this->logError('Sending failed on command: '.$command, 0);
					return false;
				}
			}
			$this->triggerEventHandler('onSend');
		}
		else
		{
			$get_body = true;
			$cached_body = '';
			foreach ($this->to as $address)
			{
				$this->currentMail = $this->buildMail($address, $from, $subject, $body, $type, $encoding, $get_body);
				//If we have a cached version
				if (!$get_body) $this->currentMail[] = $this->makeRecipientHeaders($address).$cached_body;
				$this->triggerEventHandler('onBeforeSend');
				foreach ($this->currentMail as $command)
				{
					//This means we're about to send the DATA part
					if ($get_body && $this->responseCode == 354)
					{
						$cached_body = $command;
						$command = $this->makeRecipientHeaders($address).$command;
					}
					if (is_array($command))
					{
						foreach ($command as $c)
						{
							if (!$this->command($c))
							{
								$this->logError('Sending failed on command: '.$c, 0);
								return false;
							}
						}
					}
					else if (!$this->command($command))
					{
						$this->logError('Sending failed on command: '.$command, 0);
						return false;
					}
				}
				$this->triggerEventHandler('onSend');
				$get_body = false;
			}
		}
		if ($this->autoFlush) $this->flush(true); //Tidy up a bit
		return true;
	}
	/**
	 * Builds the list of commands to send the email
	 * The last command in the output is the email itself (DATA)
	 * The commands are as follows:
	 *  - MAIL FROM: <address> (0)
	 *  - RCPT TO: <address> (1)
	 *  - DATA (2)
	 *  - <email> (3)
	 *
	 * @param  string  to_address
	 * @param  string  from_address
	 * @param  string  subject
	 * @param  string  body, optional
	 * @param  string  content-type, optional
	 * @param  string  encoding, optional
	 * @return  array  commands
	 * @private
	 */
	private function buildMail($to, $from, $subject, $body, $type='text/plain', $encoding='8bit', $return_data_part=true)
	{
		$date = date('r'); //RFC 2822 date
		$return_path = $this->returnPath ? $this->returnPath : $this->getAddress($from);
		$ret = array("MAIL FROM: ".$return_path."\r\n"); //Always
		//If the user specifies a different reply-to
		$reply_to = !empty($this->replyTo) ? $this->getAddress($this->replyTo) : $this->getAddress($from);
		//Standard headers
		$data = "From: $from\r\n".
			"Reply-To: $reply_to\r\n".
			"Subject: $subject\r\n".
			"Date: $date\r\n";
		if ($this->readReceipt) $data .= "Disposition-Notification-To: $from\r\n";
		
		if (!$to) //Only need one mail if no address was given
		{ //We'll collate the addresses from the class properties
			$data .= $this->getMimeBody($body, $type, $encoding)."\r\n.\r\n";
			$headers = $this->makeRecipientHeaders();
			//Rcpt can be run several times
			$rcpt = array();
			foreach ($this->to as $address) $rcpt[] = "RCPT TO: ".$this->getAddress($address)."\r\n";
			foreach ($this->Cc as $address) $rcpt[] = "RCPT TO: ".$this->getAddress($address)."\r\n";
			$ret[] = $rcpt;
			$ret[] = "DATA\r\n";
			$ret[] = $headers.$this->headers.$data;
			//Bcc recipients get to see their own Bcc header but nobody else's
			foreach ($this->Bcc as $address)
			{
				$ret[] = "MAIL FROM: ".$this->getAddress($from)."\r\n";
				$ret[] = "RCPT TO: ".$this->getAddress($address)."\r\n";
				$ret[] = "DATA\r\n";
				$ret[] = $headers."Bcc: $address\r\n".$this->headers.$data;
			}
		}
		else //Just make this individual email
		{
			if ($return_data_part) $mail_body = $this->getMimeBody($body, $type, $encoding);
			$ret[] = "RCPT TO: ".$this->getAddress($to)."\r\n";
			$ret[] = "DATA\r\n";
			if ($return_data_part) $ret[] = $data.$this->headers.$mail_body."\r\n.\r\n";
		}
		return $ret;
	}
	/**
	 * Returns the MIME-specific headers followed by the email
	 * content as a string.
	 * @param string body
	 * @param string content-type
	 * @param string encoding
	 * @return string mime data
	 * @private
	 */
	private function getMimeBody($string, $type, $encoding)
	{
		if ($string) //Not using MIME parts
		{
			$body = $this->encode($string, $encoding);
			if ($this->autoCompliance) $body = $this->chunkSplitLines($body);
			$data = "Content-Type: $type; charset=\"{$this->charset}\"\r\n".
				"Content-Transfer-Encoding: $encoding\r\n\r\n".
				$this->makeSafe($body);
		}
		else
		{ //Build a full email from the parts we have
			$boundary = $this->getMimeBoundary();
			$alternative_boundary = $this->getMimeBoundary(implode($this->parts));

			if (!empty($this->images))
			{
				$related_boundary = $this->getMimeBoundary(implode($this->parts).implode($this->images));
				
				$message_body = "Content-Type: multipart/related; ".
					"boundary=\"{$related_boundary}\"\r\n\r\n".
					"--{$related_boundary}\r\n";
				
				$parts_body = "Content-Type: multipart/alternative; ".
					"boundary=\"{$alternative_boundary}\"\r\n\r\n".
					"--{$alternative_boundary}\r\n".
					implode("\r\n\r\n--$alternative_boundary\r\n", $this->parts).
					"\r\n--$alternative_boundary--\r\n";
				
				$message_body .= $parts_body.
					"--$related_boundary\r\n";
				
				$images_body = implode("\r\n\r\n--$related_boundary\r\n", $this->images);
				
				$message_body .= $images_body.
					"\r\n--$related_boundary--\r\n";
				
			}
			else $message_body = "Content-Type: multipart/alternative; ".
					"boundary=\"{$alternative_boundary}\"\r\n\r\n".
					"--{$alternative_boundary}\r\n".
					implode("\r\n\r\n--$alternative_boundary\r\n", $this->parts).
					"\r\n--$alternative_boundary--\r\n";
	
			if (!empty($this->attachments)) //Make a sub-message that contains attachment data
			{
				$message_body .= "\r\n\r\n--$boundary\r\n".
					implode("\r\n--$boundary\r\n", $this->attachments);
			}
			
			$data = "MIME-Version: 1.0\r\n".
				"Content-Type: multipart/mixed;\r\n".
				"	boundary=\"{$boundary}\"\r\n".
				"Content-Transfer-Encoding: 7bit\r\n\r\n".
				"--$boundary\r\n".
				"$message_body\r\n".
				"--$boundary--";
		}
		return $data;
	}
}

?>
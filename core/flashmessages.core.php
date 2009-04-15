<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005-2008)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: cache.class.php 1813 2008-03-21 22:46:21Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * Clansuite Flashmessages
 *
 * The sending of messages is very simple for GET-Requests.
 * You can use echo or the template to output the messages.
 * It's not that simple for POST-Requests followed by a redirect.
 * In general there are three solutions:
 * You can (1) append the message to the URL, which results in very long urls
 * like header('Location: http://www.example.com/index.php?message='.urlencode($message));
 * or you can (2) implement a two step redirect, first redirecting to a successview (html with message
 * and then redirecting to the target url but you can (3) store it in the usersession.
 * This class implements the third solution.
 *
 * Think of this class as an improvement and extension of the user session object.
 * This object containers messages for the user across different HTTP-Requests.
 * by storing them in the users-session.
 * Typical messages are: errors, notices, warnings and status notifications.
 * These will flash (hence the name) on the request and inform the user.
 * The message is removed from session after it’s been displayed.
 *
 * Inspired by Ruby on Rails Flash Messages.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Flashmessages
 */
class Clansuite_Flashmessages implements ArrayAccess
{
    # contains $session array of $flashmessages
    private $flashmessages = array();

    # no constructor
    function __construct()
    {
        self::getMessagesFromSession();
    }

    /**
     * Clansuite_Flashmessages is a Singleton
     *
     * @param object $injector DependencyInjector
     *
     * @return instance of flashmessage class
     */
    public static function getInstance($injector)
    {
        static $instance;
        if ( ! isset($instance))
        {
            $instance = new Clansuite_Flashmessages($injector);
        }
        return $instance;
    }

    /**
     * General Methods for FlashMessage Handling
     */

    /**
     * Load all flashmessages from the session to this object
     * and remove the flashmessages from the session.
     * @todo abstract session access
     */
    private static function getMessagesFromSession()
    {
        if (array_key_exists('flashmessages', $_SESSION))
        {
            foreach ($_SESSION['flashmessages'] as $message => $value)
            {
                $this->flashmessages[$message] = $value;

            }
            unset($_SESSION['flashmessages']);
        }
    }

    /**
     * save the flashmessages of this object to the session
     * @todo abstract session access
     */
    private function saveFlashMessagesToSession()
    {
       $_SESSION['flashmessages'] = $this->flashmessages;
    }

    /**
     * sets a message to the session
     *
     * @param   $messagetype    string type of the message, usable for formatting.
     * @param   $message        object the actual message
     */
    public function setMessage($messagetype, $message)
    {
        # set message the session in flashmessages array container
        $this->flashmessages[$messagetype][] = $message;
    }

    /**
     * checks if a certain messagetype is set as flashmessage
     *
     * @param   $messagetype    string type of the message, usable for formatting.
     * @return  boolean         true|false
     */
    public function hasMessageType($messagetype)
    {
        return array_key_exists($key, $this->flashmessages);
    }

    /**
     * removes a specific message from the session
     */
    /*public function delMessage($messagetype, $message_id)
    {
         # @todo message_id as identifier
         $this->flashmessages[$message_id] = array();
    }*/

    /**
     * resets the whole flashmessage array
     */
    public function delMessages()
    {
        $this->flashmessages = array();
    }

    /**
     * Direct Messages with specialized templates
     *
     * 1. error
     * 2. warning
     * 3. notice
     * 4. success
     * 5. debug
     */

    /**
     * 1) sets a error-message to the session
     *
     * @param   $message    object the actual message
     */
    public function setError($message)
    {
        $this->setMessage('error', $message);
    }

    /**
     * 2) sets a warning-message to the session
     *
     * @param   $message    object the actual message
     */
    public function setWarning($message)
    {
        $this->setMessage('warning', $message);
    }

    /**
     * 3) sets a notice-message to the session
     *
     * @param   $message    object the actual message
     */
    public function setNotice($message)
    {
        $this->setMessage('notice', $message);
    }

    /**
     * 4) sets a success-message to the session
     *
     * @param   $message    object the actual message
     */
    public function setSuccess($message)
    {
        $this->setMessage('success', $message);
    }

    /**
     * 5) sets a debug-message to the session
     *
     * @param   $message    object the actual message
     */
    public function setDebug($message)
    {
        $this->setMessage('debug', $message);
    }

    /**
     *  Destructor Method
     *  implements the Savehandler
     */
    public function __destruct()
    {
       $this->saveFlashMessagesToSession();
    }

    # Magic Methods
    # This is not helpful, because of key-usage.
    # @todo add type, key, id handling
    # @todo wakeup(), sleep()

    /**
    * magic set
    */
    function __set($key, $value)
    {
        $this->flashmessages[$key] = $value;
    }

    /**
    * magic get
    */
    function __get($key)
    {
        if (isset($this->flashmessages[$key])) {
            return $this->flashmessages[$key];
        }
    }

    /**
    * magic conditional check isset
    */
    function __isset($key)
    {
        return array_key_exists($key, $this->flashmessages);
    }

    /**
     * magic unset
     */
    function __unset($key)
    {
        unset($this->flashmessage[$key]);
    }
}
?>
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
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

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
class Clansuite_Flashmessages /* extends Clansuite_Session */
{
    /**
     * @var contains $session array of $flashmessages
     */
    private static $flashmessages = array();
    /**
     * @var array types of flashmessages (whitelist)
     */
    private static $flashmessagetypes = array('error', 'warning', 'notice', 'success', 'debug');

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
        if(isset($instance) == false)
        {
            $instance = new Clansuite_Flashmessages();
        }
        return $instance;
    }

    /**
     * Sets a message to the session
     *
     * Supported Messagetypes are error, warning, notice, success, debug
     * @see self::$flashmessagetypes
     *
     * @param $type string type of the message, usable for formatting.
     * @param $message object the actual message
     */
    public static function setMessage($type, $message)
    {
        if(in_array($type, self::$flashmessagetypes) == true)
        {
            self::$flashmessages[][$type] = $message;
        }

        $_SESSION['user']['flashmessages'] = self::$flashmessages;
    }

    /**
     * Returns the whole array of flashmessages, if $type is not specified/null.
     * Or the subarray of flashmessages with key $type.
     *
     * @param $type string type of the message, usable for formatting.
     * @return array If $type is null, returns whole flashmessages array. Otherwise, subarray with key $type.
     */
    public static function getMessages($type = null)
    {
        if($type === null)
        {
            return self::$flashmessages;
        }
        elseif(isset($type) and $this->hasMessageType($type))
        {
            return self::$flashmessages[$type];
        }
    }

    /**
     * Load all flashmessages from the session to this object and remove the flashmessages from the session.
     */
    private static function getMessagesFromSessionAndUnset()
    {
        if(array_key_exists('flashmessages', $_SESSION['user']))
        {
            self::$flashmessages = $_SESSION['user']['flashmessages'];
            unset($_SESSION['user']['flashmessages']);
            return self::$flashmessages;
        }
    }

    /**
     * Checks if a certain messagetype is set as flashmessage
     *
     * @param   $type    string type of the message, usable for formatting.
     * @return  boolean  true|false
     */
    public static function hasMessageType($type)
    {
        return array_key_exists($type, self::$flashmessages);
    }

    /**
     * Removes a specific message from the session
     */
    public static function delMessage($type, $id)
    {
        # @todo message_id as identifier
        #self::$flashmessages[$type][$id] = array();
    }

    /**
     * resets the whole flashmessage array
     */
    public static function delMessages()
    {
        self::$flashmessages = array();
    }

    /**
     * Render the flashmessages
     *
     * @param $type string Type of flashmessage, will render only messages of this type.
     */
    public static function render($type = null)
    {
        if(isset($_SESSION['user']['flashmessages']))
        {
            Clansuite_Xdebug::firebug($_SESSION['user']['flashmessages']);
        }

        #$flashmessages = self::getMessages($type);
        $flashmessages_index = self::getMessagesFromSessionAndUnset();
        if(isset($flashmessages_index))
        {
            $html = '';
            foreach($flashmessages_index as $flashmessage)
            {
                foreach($flashmessage as $type => $flashmessage)
                {
                    $html .= '<div id="flashmessage" class="flashmessage ' . $type . '">' . $flashmessage . '</div>';
                }
            }
            return $html;
        }
    }
}
?>
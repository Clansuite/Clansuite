<?php
/**
 * TeamSpeak 2 Object
 *
 *
 * LICENSE:
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA. 
 *
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    $Id: Object.php 30 2007-08-27 19:42:15Z steven $
 * @since      1.0
 */

/**
 * TeamSpeak 2 Object
 *
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    $Id: Object.php 30 2007-08-27 19:42:15Z steven $
 * @since      1.0
 */

abstract class Absurd_TeamSpeak2_Object implements RecursiveIterator, ArrayAccess, Countable
{
  /**
   * Serveradmin privilege
   *
   * @see Absurd_TeamSpeak2_Object::setPrivilege()
   */
  const PRIV_SA = 'privilege_serveradmin';

  /**
   * Sticky privilege
   * 
   * @see Absurd_TeamSpeak2_Object::setPrivilege()
   */
  const PRIV_ST = 'privilege_channelsticky';

  /**
   * Allow Registration privilege
   * 
   * @see Absurd_TeamSpeak2_Object::setPrivilege()
   */
  const PRIV_AR = 'privilege_canregister';

  /**
   * Grant
   * 
   * @see Absurd_TeamSpeak2_Object::setPrivilege()
   */
  const GRANT = 1;

  /**
   * Revoke
   * 
   * @see Absurd_TeamSpeak2_Object::setPrivilege()
   */
  const REVOKE = 0;
  
  /**
   * Linux client sorting algorithm
   * 
   * @see Absurd_TeamSpeak2_Object::setSortingType
   */
  const SORT_LINUX = 1;
  
  /**
   * Windows client sorting algorithm
   * 
   * @see Absurd_TeamSpeak2_Object::setSortingType
   */
  const SORT_WINDOWS = 1;

  /**
   * List of childnodes
   *
   * @ignore 
   * @var array
   */
  protected $nodeList = null;

  /**
   * ArrayObject that keeps information about the node
   *
   * @ignore
   * @var array
   */
  protected $nodeInfo;

  /**
   * Parent Object
   *
   * @ignore
   * @var Absurd_TeamSpeak2_Object
   */
  protected $parent;

  /**
   * Returns a unique ID
   * 
   * @return string
   */
  public abstract function getUniqueID();

  /**
   * Returns the parent of current node
   * 
   * @return Absurd_TeamSpeak2_Object
   */
  public function getParent()
  {
    return $this->parent;
  }

  /**
   * Returns information about current node
   *
   * @return array
   */
  public function getNodeInfo()
  {
    $this->verifyNodeInfo();
    return $this->nodeInfo;
  }

  /**
   * Fetches the nodelist
   *
   * @ignore
   * @return void
   */
  protected function fetchNodeList()
  {
    $this->nodeList = array();
  }

  /**
   * Fetches the node info
   * 
   * @ignore
   * @return void
   */
  protected function fetchNodeInfo()
  {
    $this->nodeInfo = array();
  }

  /**
   * Verifies the node info
   *
   * @ignore 
   * @return void
   */
  protected function verifyNodeInfo()
  {
    if ($this->nodeInfo === null) {
      $this->fetchNodeInfo();
    }
  }

  /**
   * Verifies the node list
   *
   * @ignore 
   * @return void
   */
  protected function verifyNodeList()
  {
    if ($this->nodeList === null) {
      $this->fetchNodeList();
      usort($this->nodeList, array(__CLASS__, 'sortNodeList'));
    }
  }

  /**
   * Calls a function on all leaves
   *
   * @ignore 
   */
  protected function callOnClients()
  {
    $args = func_get_args();

    if (count($args) < 1) {
      throw new Absurd_TeamSpeak2_Exception("undefined recursive call", 0x06);
    }

    $method = array_shift($args);

    $iterator = new RecursiveIteratorIterator($this);

    foreach ($iterator as $child) {
      if ($child instanceof Absurd_TeamSpeak2_Client) {
        $reflect = new ReflectionObject($child);
        $reflect->getMethod($method)->invokeArgs($child, $args);
      }
    }
  }

  /**
   * Kicks one or more clients
   *
   * @param string $reason
   * @throws Absurd_TeamSpeak2_Exception
   * @return void
   */
  public function kick($reason='')
  {
    $this->callOnClients(__FUNCTION__, $reason);
  }

  /**
   * Moves one or more clients to specific channel
   * 
   * @param Absurd_TeamSpeak2_Channel | integer $channel
   * @throws Absurd_TeamSpeak2_Exception
   * @return void
   */
  public function moveTo($channel)
  {
    $this->callOnClients(__FUNCTION__, $channel);
  }

  /**
   * Removes one or more clients
   *
   * @throws Absurd_TeamSpeak2_Exception
   * @return void
   */
  public function remove()
  {
    $this->callOnClients(__FUNCTION__);
  }

  /**
   * Bans one or more clients (but does not automatically kick them)
   *
   * @throws Absurd_TeamSpeak2_Exception
   * @param integer $minutes
   * @return void
   */
  public function ban($minutes = 0)
  {
    $this->callOnClients(__FUNCTION__, $minutes);
  }

  /**
   * Messages one or more clients
   *
   * @throws Absurd_TeamSpeak2_Exception
   * @param string $msg
   * @param boolean $hidesender
   * @return void
   */
  public function message($msg, $hidesender = true)
  {
    $this->callOnClients(__FUNCTION__, $msg, $hidesender);
  }

  /**
   * Sets the privileges of one or more clients
   * 
   * Values for $priv are:
   * - {@link Absurd_TeamSpeak2_Object::PRIV_SA}
   * - {@link Absurd_TeamSpeak2_Object::PRIV_ST}
   * - {@link Absurd_TeamSpeak2_Object::PRIV_AR}
   * 
   * 
   * Values for $value are:
   * - {@link Absurd_TeamSpeak2_Object::GRANT}
   * - {@link Absurd_TeamSpeak2_Object::REVOKE}
   * 
   * @param string $priv
   * @param integer $value
   * 
   * @return void
   */
  public function setPrivilege($priv, $value)
  {
    $this->callOnClients(__FUNCTION__, $priv, $value);
  }

  /**
   * Parses the current object with a viewer
   * 
   * @param Absurd_TeamSpeak2_Viewer $viewer
   * 
   * @return void
   */
  public function parseViewer(Absurd_TeamSpeak2_Viewer $viewer)
  {
    $viewer->displayObject($this, array());

    $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

    foreach ($iterator as $key => $object) {
      $level  = array();
      $depth  = $iterator->getDepth();
      $parent = $object->parent;

      array_unshift($level, (($key + 1) < count($parent)));

      while ($depth > 0) {
        $depth--;
        $parent = $parent->parent;
        array_unshift($level, (($iterator->getSubIterator($depth)->key() + 1) < count($parent)));
      }

      $viewer->displayObject($object, $level);
    }
  }

  private static function sortNodeList($a, $b)
  {
    if (get_class($a) != get_class($b)) {
      return strnatcmp(get_class($a), get_class($b));
    } else if ($a instanceof Absurd_TeamSpeak2_Client) {
      if (($a["pprivs"] & 1) != ($b["pprivs"] & 1)) {
        return (($a["pprivs"] & 1) == 1) ? -1 : 1;
      } else {
        if ($a["cprivs"] != $b["cprivs"]) {
          if (($a["cprivs"] & 1) != ($b["cprivs"] & 1)) {
            return (($a["pprivs"] & 1) == 1) ? -1 : 1;
          }
          if (($a["cprivs"] & 2) != ($b["cprivs"] & 2)) {
            return (($a["pprivs"] & 2) == 2) ? -1 : 1;
          }
          if (($a["cprivs"] & 4) != ($b["cprivs"] & 4)) {
            return (($a["pprivs"] & 4) == 4) ? -1 : 1;
          }
          if ((($a["cprivs"] & 8) != ($b["cprivs"] & 8)) || (($a["cprivs"] & 16) != ($b["cprivs"] & 16))) {
            if (($a["cprivs"] & 8) == 0 && ($a["cprivs"] & 16) == 0) {
        	  return 1;
            }   
            if (($b["cprivs"] & 8) == 0 && ($b["cprivs"] & 16) == 0) {
              return -1;
            }  
          }
          if (defined('LIBACTS2_SORTING_TYPE') && LIBACTS2_SORTING_TYPE == self::SORT_WINDOWS) {
            return strcmp(strtolower($a->toAlnumString()), strtolower($b->toAlnumString()));
          } else {
            return strnatcmp(strtolower($a["nick"]), strtolower($b["nick"]));
          }
        } else {
          if (defined('LIBACTS2_SORTING_TYPE') && LIBACTS2_SORTING_TYPE == self::SORT_WINDOWS) {
            return strcmp(strtolower($a->toAlnumString()), strtolower($b->toAlnumString()));
          } else {
            return strnatcmp(strtolower($a["nick"]), strtolower($b["nick"]));
          }        
        }
      }
    } else if ($a instanceof Absurd_TeamSpeak2_Channel) {
      if ($a["order"] != $b["order"]) {
        return strnatcmp($a["order"], $b["order"]);
      } else {
        if (defined('LIBACTS2_SORTING_TYPE') && LIBACTS2_SORTING_TYPE == self::SORT_WINDOWS) {
          return strcmp(strtolower($a->toAlnumString()), strtolower($b->toAlnumString()));
        } else {
          return strnatcmp(strtolower($a["name"]), strtolower($b["name"]));
        }
      }
    }
  }

  /**
   * Clears the cache for Nodelist and Nodeinfo
   *
   */
  public function clearCache()
  {
    $this->nodeInfo = null;
    $this->nodeList = null;
    $this->nodeListIterator = null;
  }

  /**
   * Returns the string equivalent of Object
   * 
   * @return string
   */
  public function toString()
  {
    return $this->__toString();
  }
  
  /**
   * @ignore
   */
  public function toAlnumString() {
    return preg_replace('/[^0-9a-zA-Z]/', '', $this->toString());
  }

  /**
   * @ignore
   */
  public function __toString()
  {
    return get_class($this);
  }

  /*
  * Implementing the Countable Interface
  */

  /**
   * @ignore
   */
  public function count()
  {
    $this->verifyNodeList();
    return count($this->nodeList);
  }

  /*
   * Implementing the Recursive Iterator interfaces
   */

  /**
   * @ignore 
   */
  public function current()
  {
    $this->verifyNodeList();
    return current($this->nodeList);
  }

  /**
   * @ignore 
   */
  public function getChildren()
  {
    $this->verifyNodeList();
    return current($this->nodeList);
  }

  /**
   * @ignore 
   */    
  public function hasChildren()
  {
    $this->verifyNodeList();
    return count(current($this->nodeList)) > 0;
  }

  /**
   * @ignore 
   */    
  public function valid()
  {
    $this->verifyNodeList();
    return (key($this->nodeList) !== null);
  }

  /**
   * @ignore 
   */    
  public function key()
  {
    $this->verifyNodeList();
    return key($this->nodeList);
  }

  /**
   * @ignore 
   */    
  public function next()
  {
    $this->verifyNodeList();
    return next($this->nodeList);
  }

  /**
   * @ignore 
   */   
  public function rewind()
  {
    $this->verifyNodeList();
    return reset($this->nodeList);
  }

  /*
  * Implementing the ArrayAccess Interface
  */

  /**
   * @ignore 
   */    
  public function offsetExists($offset)
  {
    $this->verifyNodeInfo();
    return isset($this->nodeInfo[$offset]);
  }

  /**
   * @ignore 
   */    
  public function offsetGet($offset)
  {
    $this->verifyNodeInfo();
    return $this->nodeInfo[$offset];
  }

  /**
   * @ignore 
   */    
  public function offsetSet($offset, $value)
  {
    $this->verifyNodeInfo();
    $this->nodeInfo[$offset] = $value;
  }

  /**
   * @ignore 
   */    
  public function offsetUnset($offset)
  {
    $this->verifyNodeInfo();
    unset($this->nodeInfo[$offset]);
  }
}
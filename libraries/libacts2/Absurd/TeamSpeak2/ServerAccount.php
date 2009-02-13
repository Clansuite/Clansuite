<?php
/**
 * TeamSpeak 2 Server Account
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
 * @version    $Id: ServerAccount.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

/**
 * TeamSpeak 2 Server Account
 *
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    Release: $Id: ServerAccount.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

class Absurd_TeamSpeak2_ServerAccount implements ArrayAccess
{
  /**
   * Parent object
   *
   * @var Absurd_TeamSpeak2_Object
   */
  private $parent;

  /**
   * ID
   *
   * @var integer
   */
  private $id;

  /**
   * Data
   *
   * @var array
   */
  private $nodeInfo;

  /**
   * Creates a new Account Object
   *
   * You should not create this object manually unless you know what you are doing!
   * 
   * @see Absurd_TeamSpeak2_Host::getAccountByName()
   * @see Absurd_TeamSpeak2_Server::getAccountByName()
   * @param Absurd_TeamSpeak2_Object $parent
   * @param array $data
   * 
   * @return void
   */
  public function __construct(Absurd_TeamSpeak2_Object $parent, array $data)
  {
    if (!($parent instanceof Absurd_TeamSpeak2_Server) && !($parent instanceof Absurd_TeamSpeak2_Host)) {
      throw new Absurd_TeamSpeak2_Exception("Invalid parent object", 0x08);
    }

    $this->parent   = $parent;
    $this->id       = $data['id'];
    $this->nodeInfo = $data;
  }

  /**
   * Deletes the account
   *
   * @return void
   */
  public function delete()
  {
    if ($this->parent instanceof Absurd_TeamSpeak2_Host) {
      $this->parent->request("dbsuserdel {$this->id}");
    } else if ($this->parent instanceof Absurd_TeamSpeak2_Server) {
      $this->parent->request("dbuserdel {$this->id}");
    }
  }

  /**
   * Changes the account password
   *
   * @param string $password
   * 
   * @return void
   */
  public function changePassword($password)
  {
    if ($this->parent instanceof Absurd_TeamSpeak2_Host) {
      $this->parent->request("dbsuserchangepw {$this->id} {$password} {$password}");
    } else if ($this->parent instanceof Absurd_TeamSpeak2_Server) {
      $this->parent->request("dbuserchangepw {$this->id} {$password} {$password}");
    }
  }

  /**
   * Sets ServerAdmin rights (not applicable for SuperAdmin Accounts)
   *
   * @param boolean $serverAdmin
   * 
   * @return void
   */
  public function setServerAdmin($serverAdmin)
  {
    if ($this->parent instanceof Absurd_TeamSpeak2_Host) {
      throw new Absurd_TeamSpeak2_Exception("Not applicable on this object", 0x09);
    } else if ($this->parent instanceof Absurd_TeamSpeak2_Server) {
      $serverAdmin = (integer)$serverAdmin;
      $this->parent->request("dbuserchangeattribs {$this->id} {$serverAdmin}");
    }
  }

  /**
   * Returns information about the current account
   * 
   * @return array
   */
  public function getNodeInfo()
  {
    return $this->nodeInfo;
  }

  /*
  * Implementing the ArrayAccess Interface
  */

  /**
   * @ignore 
   */    
  public function offsetExists($offset)
  {
    return isset($this->nodeInfo[$offset]);
  }

  /**
   * @ignore 
   */    
  public function offsetGet($offset)
  {
    return $this->nodeInfo[$offset];
  }

  /**
   * @ignore 
   */    
  public function offsetSet($offset, $value)
  {
    $this->nodeInfo[$offset] = $value;
  }

  /**
   * @ignore 
   */    
  public function offsetUnset($offset)
  {
    unset($this->nodeInfo[$offset]);
  }
}

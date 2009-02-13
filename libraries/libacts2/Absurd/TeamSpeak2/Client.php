<?php
/**
 * TeamSpeak 2 Client
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
 * @version    $Id: Client.php 30 2007-08-27 19:42:15Z steven $
 * @since      1.0
 */

/**
 * TeamSpeak 2 Client
 *
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    $Id: Client.php 30 2007-08-27 19:42:15Z steven $
 * @since      1.0
 */

class Absurd_TeamSpeak2_Client extends Absurd_TeamSpeak2_Object
{
  /**
   * The corresponding server
   *
   * @var Absurd_TeamSpeak2_Server
   */
  private $server;

  /**
   * Client ID
   *
   * @var integer
   */
  private $id;

  public function __construct(Absurd_TeamSpeak2_Server $server, Absurd_TeamSpeak2_Object $parent, array $nodeinfo, $id)
  {
    $this->server    = $server;
    $this->parent    = $parent;
    $this->nodeInfo  = $nodeinfo;
    $this->id        = $id;
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
    $msg = ($hidesender) ? "@$msg" : $msg;
    $this->server->request("msgu {$this->id} {$msg}");
  }

  /**
   * Kicks one or more clients
   *
   * @param string $reason
   * @throws Absurd_TeamSpeak2_Exception
   * @return void
   */
  public function kick($reason = '')
  {
    $this->server->request("kick {$this->id} {$reason}");
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
    $this->server->request("banplayer {$this->id} {$minutes}");
  }

  /**
   * Removes one or more clients
   *
   * @throws Absurd_TeamSpeak2_Exception
   * @return void
   */
  public function remove()
  {
    $this->server->request("removeclient {$this->id}");
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
    if ($channel instanceof Absurd_TeamSpeak2_Channel) {
      $channel = $channel['id'];
    }
    $this->server->request("mptc {$channel} {$this->id}");
  }

  /**
   * Sets the privileges of one or more clients
   * 
   * Values for $priv are:
   * - {@link Absurd_TeamSpeak2_Object::PRIV_SA}
   * - {@link Absurd_TeamSpeak2_Object::PRIV_ST}
   * - {@link Absurd_TeamSpeak2_Object::PRIV_AR}
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
    $this->server->request("sppriv {$this->id} {$priv} {$value}");
  }

  /**
	 * Returns the flags of the current client as array of chars
	 *
	 * @return array
	 */
  public function getFlags() {
    $flags = array();
    $flags[] = (((integer)$this['pprivs'] & 4) == 4) ? 'R' : 'U';
    if (((integer)$this['pprivs'] & 1) == 1) {
      $flags[] = 'SA';
    }
    if (((integer)$this['cprivs'] & 1) == 1) {
      $flags[] = 'CA';
    }
    if (((integer)$this['cprivs'] & 8) == 8) {
      $flags[] = 'AO';
    }
    if (((integer)$this['cprivs'] & 16) == 16) {
      $flags[] = 'AV';
    }
    if (((integer)$this['cprivs'] & 2) == 2) {
      $flags[] = 'O';
    }
    if (((integer)$this['cprivs'] & 4) == 4) {
      $flags[] = 'V';
    }
    if (((integer)$this['pprivs'] & 16) == 16) {
      $flags[] = 'St';
    }
    return $flags;
  }

  public function getUniqueID()
  {
    return 'ts2_s_' . $this->server['server_id'] . '_u_' . $this->id;
  }

  /**
   * @ignore 
   */
  public function __toString()
  {
    return $this['nick'];
  }
}

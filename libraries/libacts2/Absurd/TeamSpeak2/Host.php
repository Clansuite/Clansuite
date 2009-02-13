<?php
/**
 * TeamSpeak 2 Host
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
 * @version    $Id: Host.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

/**
 * TeamSpeak 2 Host
 *
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    $Id: Host.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

class Absurd_TeamSpeak2_Host extends Absurd_TeamSpeak2_Object
{
  /**
   * TCP-Client
   *
   * @var Absurd_TeamSpeak2_TCPClient
   */
  private $tcpclient;

  /**
   * Stores the UDP-Port of the selected subserver
   *
   * @var integer
   */
  private $selected = null;

  /**
   * Server List
   *
   * @var ArrayObject
   */
  private $serverList = null;

  /**
   * Username
   *
   * @var string
   */
  private $username = null;

  /**
   * Password
   *
   * @var string
   */
  private $password = null;

  /**
   * Creates a new connection to a TeamSpeak 2 Host
   *
   * @param Absurd_TeamSpeak2_TCPClient $client
   * @return void
   */
  public function __construct(Absurd_TeamSpeak2_TCPClient $client)
  {
    $this->tcpclient = $client;
    $this->parent    = null;
  }

  /**
   * Returns a server object with the given id
   * 
   * @param integer $id
   * @return Absurd_TeamSpeak2_Server
   */
  public function getServerById($id)
  {
    $servers = $this->request('dbserverlist')->toTable();
    if(isset($servers[$id])) {
      return $this->getServerByUdp($servers[$id]['udpport']);
    } else {
      throw new Absurd_TeamSpeak2_Exception('no such object', 0x08);
    }
  }

  /**
   * Returns a server object with the given udp
   * 
   * @param integer $udp
   * @return Absurd_TeamSpeak2_Server
   */
  public function getServerByUdp($udp)
  {
    $this->verifyNodeList();
    if(isset($this->serverList[$udp])) {
      return $this->serverList[$udp];
    } else {
      throw new Absurd_TeamSpeak2_Exception('no such object', 0x08);
    }
  }

  /**
   * Authenticates with the hostsystem as a superadmin
   *
   * @param string $user
   * @param string $pass
   * @return void
   */
  public function login($user, $pass)
  {
    $this->request("slogin $user $pass");
    $this->username = $user;
    $this->password = $pass;
  }

  /**
   * Returns the login status
   *
   * @return boolean
   */
  public function loggedIn()
  {
    return !empty($this->username);
  }

  /**
   * Performs a request on the server and returns the reply
   *
   * @param command $cmd
   * @return Absurd_TeamSpeak2_Reply
   */
  public function request($cmd)
  {
    return $this->tcpclient->request($cmd);
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
    $this->request("msgall $msg");
  }

  /**
   * Returns the UDP-Port of the server which is currently selected
   *
   * @return integer
   */
  public function getSelected()
  {
    return $this->selected;
  }

  /**
   * Unregisters the selected subserver
   * 
   * @return void
   */
  public function deselect()
  {
    $this->selected = null;
  }

  /**
   * Selects the specified server
   *
   * @param integer $udp
   */
  public function select($udp)
  {
    if ($udp != $this->selected) {
      $this->request("sel $udp");
      $this->selected = $udp;
    }
  }

  /**
   * Returns a list of all subserver with ID and status
   * 
   * @return array
   */
  public function listServers()
  {
    return $this->request('dbserverlist')->toTable();
  }

  /**
   * Adds a server to the hostsystem
   * 
   * @param integer $udp
   * @return void
   */
  public function createServer($udp)
  {
    $this->request("serveradd $udp");
  }

  /**
   * Starts a server with the given id
   * 
   * @param integer $id
   */
  public function startServerById($id)
  {
    $this->request("serverstart $id");
  }

  /**
   * @ignore 
   */
  public function moveTo($channel)
  {
    throw new Absurd_TeamSpeak2_Exception('this cannot be done', 0x0b);
  }

  /**
   * Starts a server with the given udp port
   * 
   * @param integer $udp
   */
  public function startServerByUdp($udp)
  {
    $servers = $this->request('dbserverlist')->toTable();
    $sid = 0;
    foreach ($servers as $id => $srv) {
      if ($srv['udpport'] == $udp) {
        $sid = $id;
      }
    }
    $this->startServerById($sid);
  }

  /**
   * Deletes a server with the given id
   * 
   * @param integer $id
   */
  public function deleteServerById($id)
  {
    $this->request("serverdel $id");
  }

  /**
   * Deletes a server with the given udp port
   * 
   * @param integer $udp
   */
  public function deleteServerByUdp($udp)
  {
    $servers = $this->request('dbserverlist')->toTable();
    $sid = 0;
    foreach ($servers as $id => $srv) {
      if ($srv['udpport'] == $udp) {
        $sid = $id;
      }
    }
    if ($sid != 0) {
      $this->deleteServerById($sid);
    } else {
      throw new Absurd_TeamSpeak2_Exception('no such object', 0x08);
    }
  }

  /**
   * Returns am Account object with the given ID
   * 
   * @param integer $id
   * 
   * @return Absurd_TeamSpeak2_ServerAccount
   */
  public function getAccountById($id)
  {
    $dbsulist = $this->request('dbsuserlist')->toTable();
    return new Absurd_TeamSpeak2_ServerAccount($this, $dbsulist[$id]);
  }

  /**
   * Returns am Account object with the given name
   * 
   * @param string $name
   * 
   * @return Absurd_TeamSpeak2_ServerAccount
   */
  public function getAccountByName($name)
  {
    foreach ($this->request('dbsuserlist')->toTable() as $acc) {
      if ($acc['name'] == $name) {
        return new Absurd_TeamSpeak2_ServerAccount($this, $acc);
      }
    }
    throw new Absurd_TeamSpeak2_Exception('no such object', 0x08);
  }

  /**
   * Returns a list of all given accounts
   * 
   * @return array
   */
  public function getAccountList()
  {
    $list = $this->request('dbsuserlist')->toTable();
    foreach ($list as $id => $acc) {
      $list[$id] = new Absurd_TeamSpeak2_ServerAccount($this, $acc);
    }
    return $list;
  }

  /**
   * Registers an account on the host
   * 
   * @param string $name
   * @param string $password
   * 
   * @return void
   */
  public function registerAccount($name, $password)
  {
    $this->request("dbsuseradd $name $password $password");
  }

  /**
   * Reads a certain amount of lines from the end of the logfile and returns them
   *
   * @param integer $lines
   * @return string
   */
  public function readLog($lines=30)
  {
    return $this->request("log $lines")->toString();
  }

  /**
   * Returns all lines of the logfile which contain the $query
   *
   * @param string $query
   * @return string
   */
  public function searchLog($query=',')
  {
    return $this->request("logfind $query")->toString();
  }

  /**
   * Writes a line to the logfile
   *
   * @param string $text
   * @return void
   */
  public function writeLog($text)
  {
    $this->request("logmark $text");
  }

  /**
   * Configures the host by setting several values
   * 
   * $key should be one of:
   * - hoster_gfx_url
   * - allowedclientnamechars
   * - disallowedclientnamechars
   * 
   * @param string $key
   * @param string $value
   * 
   * @return void
   */
  public function setValue($key, $value)
  {
    $this->request("globalset $key $value");
  }

  public function getUniqueID()
  {
    return 'ts2_h';
  }

  /**
   * @ignore
   *
   */
  protected function fetchNodeInfo()
  {
    $this->nodeInfo = $this->request('gi')->toArray();
  }

  /**
   * @ignore
   *
   */    
  protected function fetchNodeList()
  {
    $sl = $this->tcpclient->request('sl')->toList();

    $this->serverList = array();
    $this->nodeList   = array();
    $this->selected   = null;

    foreach ($sl as $udp) {
      $server = new Absurd_TeamSpeak2_Server($this, $udp);
      $this->nodeList[]       = $server;
      $this->serverList[$udp] = $server;
    }
  }

  /**
   * @ignore
   */
  public function __toString()
  {
    return "TeamSpeak 2 Host";
  }

  /**
   * @ignore 
   */
  public function __wakeup()
  {
    if (!empty($this->username)) {
      $this->login($this->username, $this->password);
    }
    $this->selected = null;
  }
}

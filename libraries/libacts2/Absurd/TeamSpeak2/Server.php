<?php
/**
 * TeamSpeak 2 Server
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
 * @version    $Id: Server.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

/**
 * TeamSpeak 2 Server
 *
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    $Id: Server.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

class Absurd_TeamSpeak2_Server extends Absurd_TeamSpeak2_Object
{
  /**
   * UDP-Port
   *
   * @var integer
   */
  private $udp;

  /**
   * Channel list
   *
   * @var ArrayObject
   */
  private $channelList;

  /**
   * Client list
   *
   * @var ArrayObject
   */
  private $clientList;

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
   * Creates a new connection to a TeamSpeak 2 Server
   * 
   * You should not create this object manually unless you know what you are doing!
   *
   * @see Absurd_TeamSpeak2_Host::getServer()
   * 
   * @param Absurd_TeamSpeak2_Host $host
   * @param Absurd_TeamSpeak2_TCPClient $client
   * @param integer $udp
   * 
   * @return void
   */
  public function __construct(Absurd_TeamSpeak2_Host $host, $udp)
  {
    $this->parent = $host;
    $this->udp    = $udp;
  }

  /**
   * Sends a request to the server and checks whether the current server is selected
   *
   * @param string $cmd
   * @return Absurd_TeamSpeak2_Reply
   */
  public function request($cmd)
  {
    if ($this->parent->getSelected() != $this->udp) {
      $this->parent->select($this->udp);
      if (!$this->parent->loggedIn() && !empty($this->username)) {
        $this->parent->request("login {$this->username} {$this->password}");
      }
    }
    return $this->parent->request($cmd);
  }

  /**
   * Authenticates with the server as a server admin
   *
   * @param string $user
   * @param string $pass
   * @return void
   */
  public function login($user, $pass)
  {
    $this->request("login $user $pass");
    $this->username = $user;
    $this->password = $pass;
  }

  /**
   * Bans given IP-Adress for $minutes minutes from server
   * 0 minutes = forever
   * 
   * @param string $ip
   * @param integer $minutes
   * 
   * @return void
   */
  public function banIp($ip, $minutes = 0)
  {
    $this->request("banadd $ip $minutes");
  }

  /**
   * Returns a list of bans with several information
   * 
   * @return ArrayObject
   */
  public function banList()
  {
    return $this->request('banlist')->toTable();
  }

  /**
   * Removes a ban with the given id
   * 
   * @param integer|array $id
   * @return void
   */
  public function removeBan($id)
  {
    if ($id instanceof ArrayObject || is_array($id)) {
      $id = $id['b_id'];
    }
    $this->request("bandel $id");
  }

  /**
   * Clears all existing bans
   * 
   * @return void
   */
  public function clearBanlist()
  {
    $this->request('banclear');
  }

  /**
   * @ignore
   */
  protected function fetchNodeInfo()
  {
    $this->nodeInfo = $this->request('si')->toArray();
  }

  /**
   * @ignore
   */
  protected function fetchNodeList()
  {
    if(version_compare($this->parent['total_server_version'], '2.0.22.1') == -1) {
      $cl = $this->request('cl')->toTableByRegexp('/^(\d+)\t(\d+)\t([-1\d]+)\t(\d+)\t(\d+)\t"([^\r\n]+)"\t(\d+)\t([01]+)\t"([^\r\n]*)"\s*$/');
      $pl = $this->request('pl')->toTableByRegexp('/^(\d+)\t([-1\d]+)\t(\d+)\t(\d+)\t(\d+)\t(\d+)\t(\d+)\t(\d+)\t(\d+)\t(\d+)\t(\d+)\t(\d+)\t(\d+)\t"(\d+\.\d+\.\d+\.\d+)"\t"([^\r\n]+)"\t"([^\r\n]*)"\s*$/');
    } else {
      $cl = $this->request('cl')->toTable();
      $pl = $this->request('pl')->toTable();
    }

    $this->channelList = array();
    $this->clientList  = array();
    $this->nodeList    = array();

    foreach ($cl as $id => $val) {
      if ($val['parent'] == '-1') {
        $channel = new Absurd_TeamSpeak2_Channel($this, $this, $val, $id);
        $this->nodeList[] = $channel;
      } else {
        $channel = new Absurd_TeamSpeak2_Channel($this, $this->getChannel($val['parent']), $val, $id);
      }

      $this->channelList[$id] = $channel;
    }

    foreach ($pl as $id => $val) {
      $player = new Absurd_TeamSpeak2_Client($this, $this->getChannel($val['c_id']), $val, $id);
      $this->clientList[$id] = $player;
    }
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
    $this->request("msg $msg");
  }

  /**
   * Internal function to get a channel by id
   * 
   * @param integer $id
   * @return Absurd_TeamSpeak2_Channel
   */
  private function getChannel($id)
  {
    if($id == -1) {
      $channel = $this->getDefaultChannel();
      $id      = $channel['id'];
    }
    return $this->channelList[$id];
  }

  /**
   * Returns the channel with the given id
   *
   * @param integer $id
   * @return Absurd_TeamSpeak2_Channel
   */
  public function getChannelById($id)
  {
    $this->verifyNodeList();
    return $this->getChannel($id);
  }

  /**
   * Returns the client with the given id
   *
   * @param integer $id
   * @return Absurd_TeamSpeak2_Client
   */
  public function getClientById($id)
  {
    $this->verifyNodeList();
    return $this->clientList[$id];
  }

  /**
   * Returns the client with the given id
   *
   * @param string $name
   * @return Absurd_TeamSpeak2_Client
   */
  public function getClientByName($name)
  {
    $this->verifyNodeList();
    foreach ($this->clientList as $client) {
      if ($client['nick'] == $name) {
        return $client;
      }
    }

    throw new Absurd_TeamSpeak2_Exception("no such object $name", 0x08);
  }

  /**
   * Returns the channel list
   *
   * @return array
   */
  public function getChannelList()
  {
    $this->verifyNodeList();
    return $this->channelList;
  }

  /**
   * Returns the client list
   *
   * @return array
   */
  public function getClientList()
  {
    $this->verifyNodeList();
    return $this->clientList;
  }

  /**
   * Returns a list of channels matching the regexp search pattern in key $key
   * 
   * @param string $pattern
   * @param string $key
   * @return array
   */
  public function searchChannels($pattern, $key = 'name')
  {
    $this->verifyNodeList();
    $matches = array();
    foreach ($this->channelList as $channel) {
      if (@preg_match($pattern, $channel[$key])) {
        $matches[] = $channel;
      }
    }
    return $matches;
  }

  /**
   * Returns the servers default channel object
   *
   * @return Absurd_TeamSpeak2_Channel
   */
  public function getDefaultChannel() {
    $this->verifyNodeList();
    foreach ($this->channelList as $channel) {
      if(($channel['flags'] & 16) == 16) {
        return $channel;
      }
    }

    throw new Absurd_TeamSpeak2_Exception('no such object', 0x08);
  }

  /**
   * Returns a list of clients matching the regexp search pattern in key $key
   * 
   * @param string $pattern
   * @param string $key
   * @return array
   */
  public function searchClients($pattern, $key = 'nick')
  {
    $this->verifyNodeList();
    $matches = array();
    foreach ($this->clientList as $client) {
      if (@preg_match($pattern, $client[$key])) {
        $matches[] = $client;
      }
    }
    return $matches;
  }

  /**
   * Reads all logentries which belong to the server and returns them
   * 
   * @return string
   */
  public function readLog()
  {
    return $this->parent->request("logfind SID: " . $this['server_id'])->toString();
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
    $dbulist = $this->request('dbuserlist')->toTable();
    if (!isset($dbulist[$id])) {
      throw new Absurd_TeamSpeak2_Exception('no such object', 0x08);
    }
    return new Absurd_TeamSpeak2_ServerAccount($this, $dbulist[$id]);
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
    foreach ($this->request('dbuserlist')->toTable() as $acc) {
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
    $list = $this->request('dbuserlist')->toTable();
    foreach ($list as $id => $acc) {
      $list[$id] = new Absurd_TeamSpeak2_ServerAccount($this, $acc);
    }
    return $list;
  }

  /**
   * Registers an account on the server
   * 
   * @param string $name
   * @param string $password
   * @param boolean $serverAdmin
   * 
   * @return void
   */
  public function registerAccount($name, $password, $serverAdmin=false)
  {
    $serverAdmin = (integer)$serverAdmin;
    $this->request("dbuseradd $name $password $password $serverAdmin");
  }

  /**
   * Configures the server by setting several values
   * 
   * $key should be one of:
   * - server_clan_server
   * - server_allow_codec_windowscelp52
   * - server_allow_codec_gsm164
   * - server_allow_codec_gsm148
   * - server_allow_codec_celp63
   * - server_allow_codec_celp51
   * - server_allow_codec_speex2150
   * - server_allow_codec_speex3950
   * - server_allow_codec_speex5950
   * - server_allow_codec_speex8000
   * - server_allow_codec_speex11000
   * - server_allow_codec_speex15000
   * - server_allow_codec_speex18200
   * - server_allow_codec_speex24600
   * - server_maxusers
   * - server_password
   * - server_name
   * - server_welcomemessage
   * - server_webpost_posturl
   * - server_webpost_linkurl     
   * 
   * @param string $key
   * @param string $value
   * 
   * @return void
   */
  public function setValue($key, $value)
  {
    $this->request("serverset $key $value");
  }

  /**
   * Stops the server
   *
   * @return void
   */
  public function stop()
  {
    $this->request('serverstop');
  }

  public function getUniqueID()
  {
    return 'ts2_s_' . $this['server_id'];
  }

  /**
   * @ignore 
   */
  public function __toString()
  {
    return $this['server_name'];
  }

  /**
   * @ignore 
   */
  public function __wakeup()
  {
    if (!empty($this->username)) {
      $this->login($this->username, $this->password);
    }
  }
}

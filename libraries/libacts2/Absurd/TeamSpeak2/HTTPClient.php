<?php
/**
 * TeamSpeak 2 Webinterface Wrapper
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
 * @version    $Id: HTTPClient.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

/**
 * TeamSpeak 2 Webinterface Wrapper
 *
 * @deprecated The usage of this HTTP-Emulation is very dirty but sometimes necessary. Use it only where it is really needed.
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    Release: $Id: HTTPClient.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0/**
 */

class Absurd_TeamSpeak2_HTTPClient
{
  const SERVER_ADMIN  = 'sa';
  const CHANNEL_ADMIN = 'ca';
  const OPERATOR      = 'op';
  const VOICED        = 'v';
  const REGISTERED    = 'r';
  const UNREGISTERED  = 'u';

  /**
   * HTTP Text Wrapper
   *
   * @var Absurd_Network_TextClient
   */
  private $client;

  /**
   * Session Cookie
   *
   * @var string
   */
  private $cookie;


  /**
   * Creates a new HTTP-Client
   * 
   * @deprecated 
   * @param Absurd_Network_TextClient $client
   * @return void
   */
  public function __construct(Absurd_Network_TextClient $client)
  {
    $this->client = $client;
  }

  /**
   * Login procedure
   * 
   * If no $port is specified you will be logged in as superadmin otherwise as serveradmin
   * 
   * @deprecated 
   * @param string $username
   * @param string $password
   * @param integer $port
   * @return void
   */
  public function login($username, $password, $port = null)
  {
    if ($port !== null) {
      $response = $this->post('/login.tscmd', array('serverport' => $port,
      'username'   => $username,
      'password'   => $password));
    } else {
      $response = $this->post('/login.tscmd', array('superadmin' => 1,
      'username'   => $username,
      'password'   => $password));
    }

    if (empty($response['Location']) || empty($response['Set-Cookie']) || $response['Location'] != 'index.html') {
      throw new Absurd_TeamSpeak2_Exception('HTTP-Server login failed', 0x0a);
    }

    $this->cookie = $response['Set-Cookie'];
  }

  /**
   * Selects a server if you are logged in as a superadmin
   * 
   * Server is a Absurd_TeamSpeak2_Server or the server id (not the UDP-Port)
   * 
   * @deprecated 
   * @param Absurd_TeamSpeak2_Server|integer $server
   * @return void
   */
  public function select($server)
  {
    if ($server instanceof Absurd_TeamSpeak2_Server) {
      $server = $server['server_id'];
    }
    $this->get("/select_server.tscmd?serverid=$server");
  }

  /**
   * Reads a list of server settings and return them
   * 
   * @deprecated 
   * @return array
   */
  public function readServerSettings()
  {
    $response = $this->get('/server_manager_settings.html');
    $settings = $response['content'];
    $pattern = '/<input.*?name="([a-zA-Z0-9]+)".*?value="(.*?)".*?>/im';
    $pattern2 = '/<input.*?name="serverudpport".*?value="(.*?)">*/im';
    preg_match_all($pattern, $settings, $matches);
    $data = array();
    foreach ($matches[1] as $key => $val) {
      if (strstr($matches[0][$key], 'type="checkbox"')) {
        $data[$val] = (strstr($matches[0][$key], 'checked')) ? 1 : 0;
      } else {
        $data[$val] = $matches[2][$key];
      }
    }
    if (preg_match($pattern2, $settings, $matches)) {
      $data['serverudpport'] = $matches[1];
    }
    $data['servertype'] = (strstr($settings, '<option selected value="1" >')) ? 1 : 2;
    return $data;
  }

  /**
   * Reads a list of host settings and return them
   * 
   * @deprecated 
   * @return array
   */
  public function readHostSettings()
  {
    $response = $this->get('/server_basic_settings.html');
    $settings = $response['content'];
    $pattern  = '/<input.*?name="([a-zA-Z0-9_]+)".*?value="(.*?)".*?>/im';
    $pattern2 = '/<option value="(.*?)" selected>/im';
    preg_match_all($pattern, $settings, $matches);
    $data = array();
    foreach ($matches[1] as $key => $val) {
      if (strstr($matches[0][$key], 'type="checkbox"')) {
        $data[$val] = (strstr($matches[0][$key], 'checked')) ? 1 : 0;
      } else {
        $data[$val] = $matches[2][$key];
      }
    }
    if (preg_match($pattern2, $settings, $matches)) {
      $data['basic_country'] = $matches[1];
    }
    return $data;
  }

  /**
   * Writes host settings to server
   * 
   * The $userdata should be an array as you get from readHostSettings
   * 
   * @deprecated 
   * @param array $userdata
   * @return array
   */
  public function writeHostSettings(array $userdata) {
    $current = $this->readHostSettings();
    $newdata = array_merge($current, $userdata);
    $this->post('/settings_basic.tscmd', $newdata);
  }

  /**
   * Writes server settings to server
   * 
   * The $userdata should be an array as you get from readHostSettings
   * 
   * @deprecated 
   * @param array $userdata
   * @return array
   */
  public function writeServerSettings(array $userdata) {
    $current = $this->readServerSettings();
    $newdata = array_merge($current, $userdata);
    $this->post('/settings_server.tscmd', $newdata);
  }

  private function post($target, array $data)
  {
    $postdata = $this->parsePostData($data);

    $command  = "POST $target HTTP/1.1\r\n";
    $command .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $command .= "Content-Length: " . strlen($postdata) . "\r\n";
    if (!empty($this->cookie)) {
      $command .= "Cookie: {$this->cookie}\r\n";
    }
    $command .= "\r\n";
    $command .= $postdata;

    $this->client->write($command);

    return $this->read();
  }

  private function get($target)
  {
    $command  = "GET $target HTTP/1.1\r\n";
    if (!empty($this->cookie)) {
      $command .= "Cookie: {$this->cookie}\r\n";
    }
    $command .= "\r\n";

    $this->client->write($command);

    return $this->read();
  }

  private function read()
  {
    $status  = explode(" ", trim($this->client->readLine()), 3);
    $code    = $status[1];
    $headers = array();
    while (($line = $this->client->readLine()) != "\r\n") {
      $header = explode(": ", trim($line));
      $headers[$header[0]] = $header[1];
    }

    if ($code == 200 && !empty($headers['Content-Length'])) {
      $headers['content'] = $this->client->read($headers['Content-Length']);
    }

    if (!empty($headers['Connection']) && $headers['Connection'] == 'close') {
      $this->client->reconnect();
    }

    return $headers;
  }

  /**
   * Reads the group permission for a specified group and returns the permissions
   * 
   * Group should be one of:
   * - {@link Absurd_TeamSpeak2_HTTPClient::SERVER_ADMIN}
   * - {@link Absurd_TeamSpeak2_HTTPClient::CHANNEL_ADMIN}
   * - {@link Absurd_TeamSpeak2_HTTPClient::OPERATOR}
   * - {@link Absurd_TeamSpeak2_HTTPClient::VOICED}
   * - {@link Absurd_TeamSpeak2_HTTPClient::REGISTERED}
   * - {@link Absurd_TeamSpeak2_HTTPClient::UNREGISTERED}
   * 
   * @deprecated 
   * @param string $group
   * @return array
   */
  public function readGroupPermissions($group)
  {
    $classes = array('sa', 'ca', 'op', 'v', 'r', 'u');
    if (!in_array($group, $classes, true)) {
      throw new Absurd_TeamSpeak2_Exception('Unknwon group', 0x08);
    }
    $response = $this->get("/server_manager_permission_$group.html");
    $pattern = '/<input.*?name="([a-zA-Z_]+)".*?value="1"(checked)*>/im';
    preg_match_all($pattern, $response['content'], $matches);
    $data = array();
    foreach ($matches[1] as $key => $val) {
      if ($matches[2][$key] == 'checked')
      $matches[2][$key] = 1;
      else
      $matches[2][$key] = 0;
      $data[$val] = $matches[2][$key];
    }
    return $data;
  }

  /**
   * Writes group data for a specified group
   * 
   * $data should be an array like the return value of readGroupPermissions
   * 
   * Group should be one of:
   * - {@link Absurd_TeamSpeak2_HTTPClient::SERVER_ADMIN}
   * - {@link Absurd_TeamSpeak2_HTTPClient::CHANNEL_ADMIN}
   * - {@link Absurd_TeamSpeak2_HTTPClient::OPERATOR}
   * - {@link Absurd_TeamSpeak2_HTTPClient::VOICED}
   * - {@link Absurd_TeamSpeak2_HTTPClient::REGISTERED}
   * - {@link Absurd_TeamSpeak2_HTTPClient::UNREGISTERED}
   *
   * @deprecated 
   * @param string $group
   * @param array $data
   * @return void
   */
  public function writeGroupPermissions($group, array $data)
  {
    $classes = array('sa', 'ca', 'op', 'v', 'r', 'u');
    if (!in_array($group, $classes, true)) {
      throw new Absurd_TeamSpeak2_Exception('Unknwon group', 0x08);
    }
    $current = $this->readGroupPermissions($group);
    foreach ($current as $key => $val)
    if (isset($data[$key]) && ($data[$key] == 1 || $data[$key] == 0))
    $current[$key] = $data[$key];
    $this->post('/permissions_server.tscmd', $current);
  }

  private function parsePostData(array $data)
  {
    $postdata = '';
    foreach ($data as $key => $val) {
      $postdata .= "$key=" . urlencode($val) . '&';
    }
    return $postdata;
  }
}

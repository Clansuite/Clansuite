<?php
/**
 * Network Client for text based data
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
 * @version    $Id: TextClient.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

/**
 * Network Client for text based data
 *
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    $Id: TextClient.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

class Absurd_Network_TextClient implements Absurd_IO_TextWrapper
{
  /**
   * Network Address
   *
   * @var string
   */
  private $address;

  /**
   * Network timeout
   *
   * @var integer
   */
  private $timeout;

  /**
   * Network stream
   *
   * @var resource
   */
  private $stream;

  /**
   * Creates a new TextClient object
   *
   * @see http://de.php.net/stream_socket_client 
   * @param string $address
   * @param integer $timeout
   * @throws Absurd_Network_Exception
   * @return void
   */
  public function __construct($address, $timeout = 5)
  {
    $this->connect($address, $timeout);
  }

  /**
   * Creates a Network Stream
   *
   * @param string $address
   * @param integer $timeout
   * @throws Absurd_Network_Exception
   * @return void
   */ 
  public function connect($address, $timeout = 5)
  {
    $this->address = $address;
    $this->timeout = $timeout;
    $this->stream  = @stream_socket_client($address, $errno, $errstr, $timeout);
    if ($this->stream === false) {
      throw new Absurd_Network_Exception($errstr, $errno);
    }
    stream_set_timeout($this->stream, $timeout);
  }

  public function __wakeup()
  {
    $this->connect($this->address, $this->timeout);
  }

  /**
   * Closes the stream
   *
   * @return void
   */
  public function disconnect()
  {
    @fclose($this->stream);
  }

  /**
   * Closes and reopens the stream
   *
   * @return void
   */
  public function reconnect()
  {
    $this->disconnect();
    $this->connect($this->address, $this->timeout);
  }

  public function read($length = 0)
  {
    $data = @stream_get_contents($this->stream, $length);
    if ($data === false) {
      throw new Absurd_IO_Exception("unable to read data from TCP string", 0x01);
    }
    return $data;
  }

  public function readLine($token = "\r\n")
  {
    $data = @fgets($this->stream);
    if ($data === false) {
      throw new Absurd_IO_Exception("unable to read data from TCP string", 0x01);
    }
    return $data;
  }

  public function write($data)
  {
    @stream_socket_sendto($this->stream, $data);
  }

  public function writeLine($data, $separator="\n")
  {
    @stream_socket_sendto($this->stream, $data.$separator);
  }

  public function __clone() {
    $this->connect($this->address, $this->timeout);
  }
}

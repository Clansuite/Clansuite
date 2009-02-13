<?php
/**
 * TeamSpeak 2 TCP-Query Client
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
 * @version    $Id: TCPClient.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

/**
 * TeamSpeak 2 TCP-Query Client
 *
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    $Id: TCPClient.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

class Absurd_TeamSpeak2_TCPClient
{
  /**
	 * welcome message
	 */
  const PROTOCOL_SYN = "[TS]\r\n";

  /**
	 * ok message
	 */
  const PROTOCOL_OK  = "OK\r\n";

  /**
	 * error message pattern
	 */
  const PROTOCOL_ERROR_PATTERN = 'error,';

  /**
	 * error message exact
	 */
  const PROTOCOL_ERROR = "error\r\n";

  const PROTOCOL_ERROR_F = 'Your password failed 3 consecutive times, please wait 10 minutes before trying again!';
  const PROTOCOL_ERROR_B = 'You are still banned or a failed password try caused a 30 second delay!';

  /**
   * TextWrapper
   *
   * @var Absurd_IO_TextWrapper
   */
  private $wrapper;

  /**
   * Creates a new TeamSpeak 2 TCP-Client
   *
   * @param Absurd_IO_TextWrapper $wrapper
   */
  public function __construct(Absurd_IO_TextWrapper $wrapper)
  {
    $this->wrapper = $wrapper;
    if ($this->wrapper->readLine() != self::PROTOCOL_SYN) {
      throw new Absurd_TeamSpeak2_Exception('failed to handshake with the server', 0x01);
    }
  }

  /**
   * Performs a request on the server and returns a reply object
   *
   * @param string $cmd
   * @throws Absurd_TeamSpeak2_Exception
   * @return Absurd_TeamSpeak2_Reply
   */
  public function request($cmd)
  {
    if (strstr($cmd, "\n") || strstr($cmd, "\r")) {
      throw new Absurd_TeamSpeak2_Exception('invalid character in command', 0x03);
    }

    $this->wrapper->writeLine($cmd);

    $reply = $line = '';
    do {
      $line   = $this->wrapper->readLine();
      $reply .= $line;
    } while($line != self::PROTOCOL_OK && $line != self::PROTOCOL_ERROR
    && strtolower(substr($line, 0, 6)) != self::PROTOCOL_ERROR_PATTERN
    && $line != self::PROTOCOL_ERROR_F && $line != self::PROTOCOL_ERROR_B 
    && !empty($line));

    if ($line != self::PROTOCOL_OK) {
      throw new Absurd_TeamSpeak2_Exception(trim($line), 0x05);
    }

    return new Absurd_TeamSpeak2_Reply($reply);
  }
}

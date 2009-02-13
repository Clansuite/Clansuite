<?php
/**
 * TeamSpeak 2 Meta Class
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
 * @version    $Id: TeamSpeak2.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

/**
 * TeamSpeak 2 Meta Class
 *
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    Release: $Id: TeamSpeak2.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

class Absurd_TeamSpeak2
{
  /**
   * Returns a TeamSpeak 2 Host
   *
   * $address is in format tcp://$ipAdress:$tcpQueryPort
   * 
   * @param string $address
   * @param integer $timeout
   * @return Absurd_TeamSpeak2_Host
   */
  public static function connect($address, $timeout = 5)
  {
    return new Absurd_TeamSpeak2_Host(new Absurd_TeamSpeak2_TCPClient(new Absurd_Network_TextClient($address, $timeout)));
  }

  /**
   * Returns a TeamSpeak 2 Webinterface Connection
   *
   * $address is in format tcp://$ipAdress:$webInterfacePort
   * 
   * @deprecated
   * @param string $address
   * @param integer $timeout
   * @return Absurd_TeamSpeak2_HTTPClient
   */
  public static function httpConnect($address, $timeout = 5)
  {
    return new Absurd_TeamSpeak2_HTTPClient(new Absurd_Network_TextClient($address, $timeout));
  }
}
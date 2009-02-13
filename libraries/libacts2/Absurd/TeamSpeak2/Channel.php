<?php
/**
 * TeamSpeak 2 Channel
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
 * @version    $Id: Channel.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

/**
 * TeamSpeak 2 Channel
 *
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    $Id: Channel.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

class Absurd_TeamSpeak2_Channel extends Absurd_TeamSpeak2_Object
{
  /**
   * The corresponding server
   *
   * @var Absurd_TeamSpeak2_Server
   */
  private $server;

  /**
   * Channel ID
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
   * Returns the name of the codec of the current channel
   *
   * @return string
   */
  public function getCodecName() {
    $codec = array(
      'CELP 5.1 Kbit',
      'CELP 6.3 Kbit',
      'GSM 14.8 Kbit',
      'GSM 16.4 Kbit',
      'CELP Windows 5.2 Kbit',
      'Speex 3.4 Kbit',
      'Speex 5.2 Kbit',
      'Speex 7.2 Kbit',
      'Speex 9.3 Kbit',
      'Speex 12.3 Kbit',
      'Speex 16.3 Kbit',
      'Speex 19.5 Kbit',
      'Speex 25.9 Kbit',
    );
    return $codec[$this['codec']];
  }

  /**
	 * Returns the flags of the current channel as array of chars
	 *
	 * @return array
	 */
  public function getFlags() {
    if ($this['parent'] == -1) {
      $flags = array();
      $flags[] = (($this['flags'] & 1) == 1) ? 'U' : 'R';
      if (($this['flags'] & 2) == 2) {
        $flags[] = 'M';
      }
      if (($this['flags'] & 4) == 4) {
        $flags[] = 'P';
      }
      if (($this['flags'] & 8) == 8) {
        $flags[] = 'S';
      }
      if (($this['flags'] & 16) == 16) {
        $flags[] = 'D';
      }
      return $flags;
    } else {
      return array();
    }
  }

  /**
   * @ignore 
   */
  protected function fetchNodeList()
  {
    $this->nodeList = new ArrayObject();

    $cl = $this->server->getChannelList();
    foreach ($cl as $val) {
      if ($val['parent'] == $this->id) {
        $this->nodeList[] = $val;
      }
    }

    $pl = $this->server->getClientList();
    foreach ($pl as $val) {
      if ($val['c_id'] == $this->id) {
        $this->nodeList[] = $val;
      }
    }
  }

  public function getUniqueID()
  {
    return 'ts2_s_' . $this->server['server_id'] . '_c_' . $this->id;
  }

  /**
   * @ignore 
   */
  public function __toString()
  {
    return $this['name'];
  }
}

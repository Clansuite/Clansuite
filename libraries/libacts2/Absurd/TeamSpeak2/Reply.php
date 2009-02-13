<?php
/**
 * TeamSpeak 2 Reply Object
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
 * @version    $Id: Reply.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

/**
 * TeamSpeak 2 Reply Object
 *
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    $Id: Reply.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

class Absurd_TeamSpeak2_Reply
{
  const LINE_SEPARATOR = "\r\n";
  const CELL_SEPARATOR = "\t";
  const LIST_SEPARATOR = '=';

  /**
   * Reply
   *
   * @var string
   */
  private $reply;

  /**
   * Creates a new TeamSpeak 2 Reply Parser
   *
   * @param string $rpl
   */
  public function __construct($rpl) {
    $this->reply = $rpl;
  }

  /**
   * Returns the reply as a string as it is returned by the TeamSpeak Server
   *
   * @return string
   */
  public function toString()
  {
    return substr($this->reply, 0, strlen($this->reply) - 6);
  }

  /**
   * Returns the reply parsed into a table (multidimensional array)
   * 
   * @return array
   */
  public function toTable()
  {
    $values = array();
    $rpla = explode(self::LINE_SEPARATOR, $this->reply);
    array_pop($rpla);
    array_pop($rpla);
    $keys = explode(self::CELL_SEPARATOR, array_shift($rpla));
    foreach ($rpla as $line) {
      $dline = explode(self::CELL_SEPARATOR, $line);
      if (substr($line, -1) == self::CELL_SEPARATOR) {
        array_pop($dline);
      }
      $vline = array();
      foreach ($dline as $key => $val) {
        $l = strlen($val) - 1;
        if ($val{0} == '"' && $val{$l} == '"') {
          $val = substr($val, 1, $l - 1);
        }
        $vline[$keys[$key]] = $val;
      }
      $values[$vline[$keys[0]]] = $vline;
      if ($line == "") {
        break;
      }
    }

    return $values;
  }

  /**
   * Returns the reply parsed into a table (multidimensional array)
   * 
   * @param string $pattern
   * @return array
   */
  public function toTableByRegexp($pattern) {
    $values = array();
    $rpla = explode(self::LINE_SEPARATOR, $this->reply);
    array_pop($rpla);
    array_pop($rpla);
    $keys = explode(self::CELL_SEPARATOR, array_shift($rpla));
    foreach($rpla as $line) {
      if(preg_match($pattern, $line, $dline)) {
        array_shift($dline);
        $vline = array();
        foreach($dline as $key => $val) {
          $vline[$keys[$key]] = $val;
        }
        $values[$vline[$keys[0]]] = $vline;
      }
    }

    return $values;
  }

  /**
   * Returns the reply parsed into a array
   * 
   * @return array
   */
  public function toArray()
  {
    $values = array();
    $rpla = explode(self::LINE_SEPARATOR, $this->reply);
    array_pop($rpla);
    array_pop($rpla);
    foreach ($rpla as $val) {
      $line = explode(self::LIST_SEPARATOR, $val, 2);
      list($key, $value) = $line;
      $values[$key] = $value;
    }
    return $values;
  }

  /**
   * Returns the reply parsed as a list
   * 
   * @return array
   */
  public function toList()
  {
    $rpla = explode(self::LINE_SEPARATOR, $this->reply);
    array_pop($rpla);
    array_pop($rpla);
    return $rpla;
  }

  public function __toString()
  {
    return $this->toString();
  }
}



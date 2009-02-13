<?php
/**
 * Absurdcoding IO Wrapper for text based data
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
 * @version    $Id: TextWrapper.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

/**
 * Absurdcoding IO Wrapper for text based data
 *
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    $Id: TextWrapper.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

interface Absurd_IO_TextWrapper
{
  /**
   * Reads a specific amount of data and returns the value
   *
   * @param integer $length
   * @throws Absurd_IO_Exception
   * @return string
   */
  public function read($length = 0);

  /**
   * Reads one line of data and returns the value
   *
   * @throws Absurd_IO_Exception
   * @return string
   */
  public function readLine($token = "\n");

  /**
	 * Writes given data
	 * 
	 * @param string $data
	 * @throws Absurd_IO_Exception
	 * @return void
	 */
  public function write($data);

  /**
	 * Writes one line of data
	 * 
	 * @param string $data
	 * @param string $separator
	 * @throws Absurd_IO_Exception
	 * @return void
	 */
  public function writeLine($data, $separator="\n");
}

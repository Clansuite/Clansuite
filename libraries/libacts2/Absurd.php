<?php
/**
 * Absurdcoding.org Framework Loader
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
 * @version    $Id: Absurd.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

Absurd::init();

/**
 * Absurdcoding.org Framework Loader
 *
 * @copyright  2006 Steven Barth
 * @license    http://www.gnu.org/licenses/gpl.html   GNU General Public License
 * @version    $Id: Absurd.php 25 2007-07-08 11:11:17Z steven $
 * @since      1.0
 */

class Absurd
{
  /**
   * Library path
   *
   * @var string
   */
  private static $libpath;

  /**
	 * File extension
	 *
	 * @var string
	 */
  private static $extension;

  /**
	 * Initialises Absurdcoding.org Framework
	 *
	 * @return void
	 */
  public static function init()
  {
    self::$libpath   = dirname(__FILE__) . '/';
    self::$extension = '.php';
    spl_autoload_register(array(__CLASS__, 'autoload'));
  }

  /**
	 * Autoloads a class with given name
	 *
	 * @param  string $class
	 * @throws Absurd_Exception
	 * @return void
	 */
  public static function autoload($class)
  {
    if (!class_exists($class) && !interface_exists($class))
    {
      if (!preg_match('/^[A-Za-z0-9_]+$/', $class))
      {
        throw new Absurd_Exception("Class $class contains invalid characters", 0x01);
      }
      else
      {
        $file = self::$libpath . str_replace('_', '/', $class) . self::$extension;
        /**
         * Modification Reason:
         * The following section of original code is just ahem.. stu.. suboptimal!
         * If you work with several autoload methods (chaining), throwing exceptions is fatal (breaking the chain).
         * Therefore it's replaced by a bypass!
         */
        /*
        if (!$fp = @fopen($file, 'r', true)) {
          throw new Absurd_Exception("Class file $file was not found", 0x02);
        } else {
          @fclose($fp);
          include_once($file);
          if (!class_exists($class) && !interface_exists($class)) {
            throw new Absurd_Exception("Class $class was not found in the expected place", 0x03);
          }
        }
        */        
        if (is_file($file))
        {
            require_once $file;
            return true;
        }
        return false;
      }
    }
  }
}
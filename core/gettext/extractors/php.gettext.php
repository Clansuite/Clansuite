<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

   # Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Gettext_Extractor_PHP
 *
 * Extracts translation strings by scanning for certain functions: translate(), t(), _().
 *
 * @author Karel Klíma
 * @author Jens-Andre Koch
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Gettext
 */
class Clansuite_Gettext_Extractor_PHP implements Clansuite_Gettext_Extractor_Interface
{
    /**
     * The functions to extract translation strings from
     *
     * @var array
     */
    protected $functions = array('translate', 't', '_');

    /**
     * Includes a function to parse gettext phrases from
     *
     * @param $functionName
     * @param $argumentPosition
     *
     * @return object Clansuite_Gettext_Extractor_PHP
     */
    public function addFunction($functionName, $argumentPosition = 1)
    {
        $this->functions[$functionName] = ceil((int) $argumentPosition);

        return $this;
    }

    /**
     * Excludes a function from the function list
     *
     * @param $functionName
     *
     * @return object Clansuite_Gettext_Extractor_PHP
     */
    public function removeFunction($functionName)
    {
        unset($this->functions[$functionName]);

        return $this;
    }

    /**
     * Excludes all functions from the function list
     *
     * @return object Clansuite_Gettext_Extractor_PHP
     */
    public function removeAllFunctions()
    {
        $this->functions = array();

        return $this;
    }

    /**
     * Parses given file and returns found gettext phrases
     *
     * @param string $file
     *
     * @return array
     */
    public function extract($file)
    {
        $pInfo = pathinfo($file);
        $data = array();
        $tokens = token_get_all(file_get_contents($file));
        $next = false;
        foreach($tokens as $c)
        {
            if(is_array($c))
            {
                if($c[0] != T_STRING && $c[0] != T_CONSTANT_ENCAPSED_STRING)
                    continue;
                if($c[0] == T_STRING && in_array($c[1], $this->keywords))
                {
                    $next = true;
                    continue;
                }
                if($c[0] == T_CONSTANT_ENCAPSED_STRING && $next == true)
                {
                    $data[substr($c[1], 1, -1)][] = $pInfo['basename'] . ':' . $c[2];
                    $next = false;
                }
            }
            else
            {
                if($c == ')')
                    $next = false;
            }
        }
        return $data;
    }
}
?>
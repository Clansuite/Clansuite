<?php
   /**
    * Clansuite - just an eSports CMS
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
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite_Upload - Clansuite Core Class for Upload Handling
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Upload
 */
class Clansuite_Upload implements ArrayAccess, IteratorAggregate, Countable
{
    protected $files = array();

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parseFiles();
    }

    /**
     * Parses $files variable to Clansuite_Upload_File objects.
     */
    protected function parseFiles()
    {
        foreach ($files as $formId => $fileInfo)
        {
            if (is_array($fileInfo['name']))
            {
                $this->files[$formId] = array();

                #$filecounter = count($fileInfo['name']);
                for ($i = 0; $i < $filecounter; $i++)
                {
                    $this->files[$formId][$i] = new Clansuite_File(
                        $fileInfo['name'][$i],
                        $fileInfo['type'][$i],
                        $fileInfo['size'][$i],
                        $fileInfo['tmp_name'][$i],
                        $fileInfo['error'][$i]
                    );
                }
            }
            else
            {
                $this->files[$formId] = new Clansuite_File(
                    $fileInfo['name'],
                    $fileInfo['type'],
                    $fileInfo['size'],
                    $fileInfo['tmp_name'],
                    $fileInfo['error']
                );
            }
        }
    }

    /**
     * Checks whether there is files uploaded with specified name.
     *
     * @param $offset string  form name of file upload
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->files[$offset]);
    }

    /**
     * Returns the uploaded files that have the specified form name.
     *
     * @param $offset string  form name of file upload
     * @return Clansuite_Upload_File|array  an uploaded file object or an array of them
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->files[$offset];
        }
    }

    /**
     * Array access is read only.
     *
     * @throws Clansuite_Upload_Exception always
     */
    public function offsetSet($offset, $value)
    {
        throw new Clansuite_Upload_Exception('Array access is read only.');
    }

    /**
     * Array access is read only.
     *
     * @throws Clansuite_Upload_Exception always
     */
    public function offsetUnset($offset)
    {
         throw new Clansuite_Upload_Exception('Array access is read only.');
   }

    /**
     * Returns an iterator for uploaded files.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->files);
    }

    /**
     * Returns the count of uploaded files with different form names.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->files);
    }
}
?>
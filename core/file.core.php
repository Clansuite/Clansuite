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
´   *
    * @version    SVN: $Id: $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * Clansuite_File - Clansuite Core Class for the File Object
 *
 * @author      Jens-André Koch <vain@clansuite.com>
 * @copyright   Jens-André Koch (2005 - onwards)
 * @package     clansuite
 * @category    filehandling
 * @subpackage  core
 */
class Clansuite_File
{
    protected $name;
    protected $type;
    protected $size;
    protected $temporaryName;
    protected $error;
    protected $allowedExtensions = array();

    /**
     * Constructor.
     *
     * @param $name string
     * @param $type string
     * @param $size integer
     * @param $temporaryName string
     * @param $error integer
     */
    public function __construct($name, $type, $size, $temporaryName, $error)
    {
        $this->name = basename($name);
        $this->type = $type;
        $this->size = $size;
        $this->temporaryName = $temporaryName;
        $this->error = $error;
    }

    /**
     * Returns the original filename of the uploaded file.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the MIME type of the uploaded file.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the size of the uploaded file in bytes.
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Returns the temporary filename of the uploade file in server.
     *
     * @return string
     */
    public function getTempName()
    {
        return $this->temporaryName;
    }

    /**
     * Returns the error code associated with this file upload.
     *
     * @see http://www.php.net/manual/en/features.file-upload.errors.php
     * @return integer
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Returns file extension. If file has no extension, returns null.
     *
     * @return string|null
     */
    public function getExtension()
    {
        return substr($this->name, strrpos($this->name, '.'));
    }

    /**
     * Checks if this file was uploaded successfully.
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->getError() === UPLOAD_ERR_OK;
    }

    /**
     * Checks if this uploaded file has a valid file extension.
     *
     * @return boolean
     */
    public function hasValidExtension()
    {
        if (count($this->allowedExtensions) === 0)
        {
            return true;
        }
        else
        {
            return in_array($this->getExtension(), $this->allowedExtensions);
        }
    }

    /**
     * Sets the allowed extensions that the uploaded file can have.
     *
     * @param $extensions array  an array of allowed file extensions. If empty,
     * every file extension is allowed.
     * @return Clansuite_File  this object
     */
    public function setAllowedExtensions(array $extensions = array())
    {
        $this->allowedExtensions = $extensions;

        return $this;
    }

    /**
     * Returns the allowed extensions that the uploaded file can have.
     *
     * @return array
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    /**
     * Moves uploaded file to the specified destination directory.
     *
     * @param $destination string  destination directory
     * @param $overwrite boolean overwrite 
     * @throws Clansuite_Exception  on failure
     */
    public function moveTo($destination, $overwrite = false)
    {
        # ensure upload was valid
        if ( false == $this->isValid())
        {
            throw new Clansuite_Exception('File upload was not successful.', $this->getError());
        }

        # ensure a valid file extension was used
        if ( false == $this->hasValidExtension())
        {
            throw new Clansuite_Exception('File does not have an allowed extension.');
        }

        # ensure destination directory exists
        if ( false == is_dir($destination))
        {
            throw new Clansuite_Exception($destination . ' is not a directory.');
        }

        # ensure destination directory is writeable
        if ( false == is_writeable($destination))
        {
            throw new Clansuite_Exception('Cannot write to destination directory ' . $destination);
        }

        # check if the destination as a file exists
        if (is_file($destination))
        {  
            # exit here, if overwrite is not requested
            if ( false == $overwrite)
            {
                throw new Clansuite_Exception('File ' . $destination . ' already exists.');
            }
            
            
            if ( false == is_writeable($destination))
            {
                throw new Clansuite_Exception('Cannot overwrite ' . $destination);
            }
        }

        if ( false == move_uploaded_file($this->tmpName, $destination))
        {
            throw new Clansuite_Exception('Moving uploaded file failed.');
        }
    }
}
?>
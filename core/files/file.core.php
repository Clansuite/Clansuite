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

# Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_File - Clansuite Core Class for the File Object
 *
 * @author      Jens-André Koch <vain@clansuite.com>
 * @copyright   Jens-André Koch (2005 - onwards)
 * @category    Clansuite
 * @package     Core
 * @subpackage  File
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
        return mb_substr($this->name, strrpos($this->name, '.'));
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

/**
 * ImagesOnly FileType Filter for the SPL FilterIterator.
 * If the directory iterator is wrapped into this filter, it will fetch only files with a certain type.
 */
class Clansuite_ImagesOnlyFilterIterator extends FilterIterator
{
    # whitelist of allowed image filetypes, lowercase
    private $allowed_image_filetypes = array('png', 'gif', 'jpeg', 'jpg');

    /*
    public function setup( Clansuite_Config $config )
    {
        # the config value is an comma separated list, so we explode it by comma into an array
        $allowed_image_filetypes_from_config = explode(',', $config['allowed_image_filetypes']);

        # then merge the hardcoded whitelist with the whitelist comming from the config
        array_merge($this->allowed_image_filetypes, $allowed_image_filetypes_from_config);
    }
    */

    /**
     * Implements method from FilterIterator (SPL.php)
     */
    public function accept()
    {
        #$this->setup();

        # get the current element from the iterator to examine the fileinfos
        $current = $this->current();

        # we want files, so we skip all directories
        if ($current->getType() !== 'file')
        {
            return false;
        }

        # set filename and pathinfo
        $filename = $current->getFilename();
        $fileInfo = pathinfo($filename);

        # based on the pathinfo variable fileInfo, we check if an extension exists
        if (empty($fileInfo['extension']))
        {
            return false;
        }
        # ensure that the extension is in the $allowed_image_filetypes whitelist
        elseif (in_array(mb_strtolower($fileInfo['extension']), $this->allowed_image_filetypes))
        {
            return true;
        }
        else
        {
            # it's not a whitelisted extension
            return false;
        }
    }
}

class Clansuite_Directory
{
    private $filtername = 'ImagesOnly';
    private $directory;

    /**
     * Available Filter types: image
     */
    public function setFilter($filtername)
    {
        $this->filtername = $filtername;

        return $this;
    }

    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }

    public function getDirectory()
    {
        if(empty($this->directory) == false)
        {
            return $this->directory;
        }
        else
        {
            return 'uploads/images/gallery';
        }
    }

    public function getFiles($directory = null, $return_as_array = null)
    {
        # set dir
        if(empty($directory) == false)
        {
            $this->setDirectory($directory);
        }

        # compose the full name of class in variable and then use it
        $classname = 'Clansuite_' . $this->filtername . 'FilterIterator';

        # cascade of a filter for a specific file type and the directory iterator
        $iterator = new $classname(new DirectoryIterator(ROOT . $this->getDirectory()));

        # return objects
        if($return_as_array == null or $return_as_array == false)
        {
            # create new array to take the SPL FileInfo Objects
            $data = new ArrayObject();

            # while iterating
            foreach($iterator as $file)
            {
                /**
                 * push the SPL FileInfo Objects into the array
                 * @see http://www.php.net/~helly/php/ext/spl/classSplFileInfo.html
                 */
                $data[$file->getFilename()] = $file->getFileInfo();
            }

            $data->ksort();
        }
        else # return array
        {
            # create array
            $data = array();

            # while iterating
            foreach($iterator as $file)
            {
                $wwwpath = WWW_ROOT . DS . $this->getDirectory() . DS . $file->getFilename();
                $wwwpath = str_replace('//', '/', $wwwpath);
                $data[$wwwpath] = $file->getFilename();
            }
        }

        # return the array with SPL FileInfo Objects
        return $data;
    }

    /**
     *
     * @author: Lostindream at atlas dot cz
     * @link http://de.php.net/manual/de/function.pathinfo.php#85196
     */
    function filePath($filePath)
    {
        $fileParts = pathinfo($filePath);

        if(!isset($fileParts['filename']))
        {
            $fileParts['filename'] = mb_substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));
        }

        return $fileParts;
    }
}
?>
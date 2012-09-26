<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Files;

class Directory
{
    private $filtername = 'ImagesOnly';
    private $directory = '';

    public function __construct($directory = null)
    {
        if ($directory !== null) {
            $this->setDirectory($directory);
        }

        return $this;
    }

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
        // slash fix
        $directory = str_replace('/', DS, $directory);
        $directory = str_replace('\\', DS, $directory);

        // prefix directory with ROOT for security purposes
        if (stristr($directory, ROOT) == false) {
            $directory = ROOT . $directory;
        }

        $this->directory = $directory;

        return $this;
    }

    public function getDirectory()
    {
        if (empty($this->directory) === false) {
            return $this->directory;
        } else { // default path

            return ROOT . 'uploads/images/gallery';
        }
    }

    public function getFiles($return_as_array = false)
    {
        // compose the full name of the filter class
        $classname = 'Koch_' . $this->filtername . 'FilterIterator';

        // wrap the iterator in a filter class, when looking for a specific file type, like imagesOnly'
        $iterator = new $classname(new \DirectoryIterator($this->getDirectory()));

        // return objects
        if ($return_as_array === false) {
            // create new array to take the SPL FileInfo Objects
            $data = new \ArrayObject();

            // while iterating
            foreach ($iterator as $file) {
                /**
                 * push the SPL FileInfo Objects into the array
                 * @see http://www.php.net/~helly/php/ext/spl/classSplFileInfo.html
                 */
                $data[$file->getFilename()] = $file->getFileInfo();
            }

            $data->ksort();
        } else { // return array
            // create array
            $data = array();

            // while iterating
            foreach ($iterator as $file) {
                $wwwpath = WWW_ROOT . DIRECTORY_SEPARATOR . $this->getDirectory() . DIRECTORY_SEPARATOR . $file->getFilename();
                $wwwpath = str_replace('//', '/', $wwwpath);
                $data[$wwwpath] = $file->getFilename();
            }
        }

        // return the array with SPL FileInfo Objects
        return $data;
    }

    /**
     *
     * @author: Lostindream at atlas dot cz
     * @link http://de.php.net/manual/de/function.pathinfo.php#85196
     */
    public function filePath($filePath)
    {
        $fileParts = pathinfo($filePath);

        if (!isset($fileParts['filename'])) {
            $fileParts['filename'] = mb_substr($fileParts['basename'], 0, mb_strrpos($fileParts['basename'], '.'));
        }

        return $fileParts;
    }
}

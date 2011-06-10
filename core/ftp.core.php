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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Core FTP Class
 *
 * Allows connections to FTP servers and basic directory and file operations.
 */
class Clansuite_FTP
{
    /**
     * @param array $errors An array of any errors
     */
    public $errors = array();
    
    private $connection;
    private $server;
    private $username;
    private $password;
    private $port;
    private $passive;

    /**
     * Default Constructor
     *     
     * @param string $server The server hostname to connect to
     * @param string $username The username required to access the FTP server
     * @param string $password The password required to access the FTP server
     * @param int $port The port number to connect to the FTP server on
     * @param bool $passive Whether or not to use a passive or active connection     * 
     */
    public function __construct($server, $username, $password, $port = 21, $passive = FALSE)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
        $this->passive = $passive;
    }
    
    /**
     * Tries to 
     * (1) open a connection to the remote server 
     * (2) authenticate the user
     * (3) sets the connection mode
     *
     * @return bool
     */
    private function open_connection()
    {
        # open connection
        if(!$connection = @ftp_connect($this->server, $this->port))
        {
            $this->errors[] = 'Cannot connect to FTP Server, please check settings.';
        }

        # authenticate user / login
        if(false === @ftp_login($connection, $this->username, $this->password))
        {
            $this->errors[] = 'Connected to server but unable to authenticate the user, please check credentials.';
        }

        # set connection mode
        if(false === @ftp_pasv($connection, $this->passive))
        {
            $this->errors[] = 'Unable to set connection mode to passive.';
        }

        if(empty($this->errors) === true)
        {
            $this->connection = $connection;
            return true;
        }

        return false;
    }

    /**
     * Upload a local file to the remote server
     *
     * @param string $source_file The local file to upload
     * @param string $destination_file The remote location and name of the file
     * @param string $transfer_mode optional Defaults to Binary connections but can use FTP_ASCII
     */
    public function upload($source_file, $destination_file, $transfer_mode = FTP_BINARY)
    {
        if($this->open_connection() === false)
        {
            return false;
        }

        # check local file
        if(is_file($source_file) === false)
        {
            $this->errors[] = 'Unable to find local file to send.';
            return false;
        }

        # attempt to send file
        if(false === @ftp_put($this->connection, $destination_file, $source_file, $transfer_mode))
        {
            $this->errors[] = 'Unable to send file to remote server, does destination folder exist?';
            $this->close_connection();
            return false;
        }

        $this->close_connection();
        return true;
    }

    /**
     * Download a a file from remote server to local file
     *
     * @param string $source_file The remote file
     * @param string $destination_file The local file to create
     * @param string $transfer_mode optional Defaults to Binary connections but can use FTP_ASCII
     * @return bool
     */
    public function download($source_file, $destination_file, $transfer_mode = FTP_BINARY)
    {
        if($this->open_connection() === false)
        {
            return false;
        }

        # download file
        if(false === @ftp_get($this->connection, $destination_file, $source_file, $transfer_mode))
        {
            $this->errors[] = 'Unable to download file, does local folder exist.';
            $this->close_connection();
            return false;
        }

        $this->close_connection();
        return true;
    }

    /**
     * Deletes a remote file
     *
     * @param string $file The remote file to delete
     * @return bool 
     */
    public function delete_file($file = '')
    {
        if($this->open_connection() === false)
        {
            return false;
        }
        
        # delete file
        if(false === @ftp_delete($this->connection, $file))
        {
            $this->errors[] = 'Unable to delete remote file, have you checked permissions.';
            $this->close_connection();
            return false;
        }

        $this->close_connection();
        return true;
    }

    /**
     * Rename or move a file or a directory
     * 
     *
     * @param string $source_file The file or folder to be renamed/moved
     * @param string $renamed_file The destination or new name of the file/folder
     * @return bool
     */
    public function rename_or_move($source_file, $renamed_file)
    {
        if($this->open_connection() === false)
        {
            return false;
        }

        if(false === @ftp_rename($this->connection, $source_file, $renamed_file))
        {
            $this->errors[] = 'Unable to rename/move file';
            $this->close_connection();
            return false;
        }

        $this->close_connection();        
        return true;
    }

    /**
     * Create a remote directory
     *
     * @param string $dir The path of the remote directory to create
     * @return bool
     */
    public function createDirectory($dir)
    {
        if($this->open_connection() === false)
        {
            return false;
        }

        if(ftp_mkdir($this->connection, $dir) === FALSE)
        {
            $this->errors[] = 'Unable to create remote directory.';
            $this->close_connection();
            return false;
        }

        $this->close_connection();        
        return true;
    }

    /**
     * Delete a remote directory
     *     
     * @param string $dir The path of the remote directory to delete
     * @return bool
     */
    public function deleteDirectory($dir)
    {
        if($this->open_connection() === false)
        {
            return false;
        }

        if(false === @ftp_rmdir($this->connection, $dir))
        {
            $this->errors[] = 'Unable to delete remote directory.';
            $this->close_connection();
            return false;
        }

        $this->close_connection();        
        return true;
    }

    /**
     * Set permissions on a file or directory
     *
     * @param string $file The file or directory to modify
     * @param int $chmod optional The permissions to apply Default 0644
     * @return bool
     */
    public function setPermissions($file, $chmod = 0644)
    {
        if($this->open_connection() === false)
        {
            return false;
        }

        if(function_exists('ftp_chmod') === false)
        {
            if(false === @ftp_site($this->connection, sprintf('CHMOD %o %s', $chmod, $file)))
            {
                $this->errors[] = 'Unable to modify permissions.';
                $this->close_connection();
                return false;
            }
        }
        else
        {
            if(false === @ftp_chmod($this->connection, $chmod, $file))
            {
                $this->errors[] = 'Unable to modify permissions.';
                $this->close_connection();
                return false;
            }
        }

        $this->close_connection();        
        return true;
    }
    
    /**
     * Check if a file exists
     *    
     * @param string $filename The remote file to check
     * @return bool|int FALSE if file doesn't exist or the number of bytes
     */
    public function isFile($filename)
    {
        $this->file_size($filename);
    }

    /**
     * Get the size in bytes of a remote file
     * Can be used to check if a file exists
     *    
     * @param string $filename The remote file to check
     * @return bool|int FALSE if file doesn't exist or the number of bytes
     */
    public function file_size($filename)
    {
        if($this->open_connection() === false)
        {
            return false;
        }

        $file_size = @ftp_size($this->connection, $filename);

        if($file_size === false or $file_size == -1)
        {
            $this->errors[] = 'Unable to find remote file.';
            $this->close_connection();
            return false;
        }

        $this->close_connection();        
        return $file_size;
    }

    /**
     * Checks whether a directory exists by trying to navigate to it
     *
     * @param string $dir The directory to check
     * @return bool
     */
    public function isDir($dir)
    {
        if($this->open_connection() === false)
        {
            return false;
        }

        if(false === @ftp_chdir($this->connection, $dir))
        {
            $this->close_connection();
            return false;
        }

        $this->close_connection();        
        return true;
    }

    /**
     * Returns the contents of a directory
     *
     * @param string $dir The directory to read
     * @return array|bool An array of files or a FALSE on error
     */
    public function getDirectoryContent($dir)
    {
        $this->open_connection();

        $f = @ftp_nlist($this->connection, $dir);

        if(empty($f) === true)
        {
            $this->errors[] = 'Unable to read remote directory.';
            $this->close_connection();
            return false;
        }

        $this->close_connection();        
        return $f;
    }

    

    /**
     * Attempts to close the connection
     *
     * @return bool
     */
    private function close_connection()
    {
        if(@ftp_close($this->connection) === false)
        {
            return false;
        }
        
        $this->connection = '';        
        return true;
    }

}

?>
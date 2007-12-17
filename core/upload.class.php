<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         upload.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for uploading files
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-$LastChangedDate$)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * This Clansuite Core Class for uploading files
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-$LastChangedDate$)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  upload
 */
class upload
{
    private $file = array();
    private $upload_folder = '';
    private $max_filesize = 0; // in bytes!
    private $valid_extensions = array();
    private $extension = '';
    private $new_filename = '';

    public $filename = '';
    public $done = false;


    /**
    * Create an upload stream, check filesize and extension as well as correct upload form
    *
    * @global $cfg
    */
    function __construct( $file = array(), $upload_folder = '', $new_filename = '', $valid_extensions = array(), $max_filesize = 0 )
    {
        global $cfg;

        // Set class vars
        $this->file = $file;
        $this->filename = $this->file['name'];
        $this->valid_extensions = $valid_extensions;
        $this->new_filename = $new_filename;
        $this->upload_folder = $upload_folder;

        // check if the array is filled
        if( !isset( $this->file['name'] ) OR !isset( $this->file['tmp_name'] ) OR !isset( $this->file['size'] ) )
            return false;

        // max filesize in bytes
        $this->max_filesize = ( $max_filesize != 0 ) ? $max_filesize : $cfg->max_upload_filesize;

        if( $this->_check_extension() and $this->_check_filesize() )
        {
            $this->filename = $this->new_filename . '.' . $this->extension;
            $this->done = move_uploaded_file($this->file['tmp_name'], $this->upload_folder . $this->filename );
        }
        else
        {
            return false;
        }
    }

    /**
    * Check if the exitension is valid
    */
    private function _check_extension()
    {
        $info = explode('.',$this->filename);;
        $ext = $info[(count($info))-1];

        if( in_array( $ext, $this->valid_extensions ) )
        {
            $this->extension = strtolower($ext);
            return true;
        }
        else
            return false;
    }

    /**
    * Check if the filesize is valid
    */
    private function _check_filesize()
    {
        if( $this->file['size'] < $this->max_filesize )
            return true;
        else
            return false;
    }
}
?>
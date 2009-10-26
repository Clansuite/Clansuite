<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andr� Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: layout.core.php 2870 2009-03-25 23:21:42Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Formelement_Input')) { require 'input.form.php'; }

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Input
 *      |
 *      \- Clansuite_Formelement_File
 */
class Clansuite_Formelement_File extends Clansuite_Formelement_Input implements Clansuite_Formelement_Interface
{
    /**
     * Flag variable for the uploadType.
     *
     * There are several different formelements available to upload files:
     *
     * 1) Ajaxupload    -> uploadajax.form.php
     * 2) APC           -> uploadapc.form.php
     * 3) Uploadify     -> uploadify.form.php
     * 4) Default HTML  -> this class
     *
     * @string
     */
    protected $uploadType;

    /**
     * constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->type = 'file';

        # watch out, that the opening form tag needs the enctype="multipart/form-data"
        # else you'll get the filename only and not the content of the file.
    }

    public function setUploadType($uploadType)
    {
        $this->uploadType = $uploadType;

        return $this;
    }

    public function render()
    {
        /**
         * Switch for uploadType
         */
        switch ($this->uploadType)
        {
            default:
            case 'ajaxupload':
                    if (!class_exists('Clansuite_Formelement_Uploadajax')) { include 'uploadajax.form.php'; }
                    return new Clansuite_Formelement_Uploadajax();
                break;
            case 'apc':
                    if (!class_exists('Clansuite_Formelement_Uploadapc')) { include 'uploadapc.form.php'; }
                    return new Clansuite_Formelement_Uploadapc();

                break;
            case 'uploadify':
                    if (!class_exists('Clansuite_Formelement_Uploadify')) { include'uploadify.form.php'; }
                    return new Clansuite_Formelement_Uploadify();
                break;
            case 'html':
                    /**
                     * Fallback to normal <input type="file"> upload
                     * Currently not using the render method of the parent class
                     * return parent::render();
                     */
                    return '<input name="uploadfile" type="file">';
                break;
        }
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>
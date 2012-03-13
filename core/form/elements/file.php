<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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
    * @copyright  Jens-André Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\Formelement;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

class File extends Input implements FormelementInterface
{
    /**
     * Flag variable for the uploadType.
     *
     * There are several different formelements available to upload files:
     *
     * 1) Ajaxupload    -> uploadajax.php
     * 2) APC           -> uploadapc.php
     * 3) Uploadify     -> uploadify.php
     * 4) Default HTML  -> this class
     *
     * @string
     */
    protected $uploadType;

    public function __construct()
    {
        $this->type = 'file';

        # Watch out, that the opening form tag needs the enctype="multipart/form-data"
        # else you'll get the filename only and not the content of the file.
        # Correct encoding is automatically set, when using $form->addElement() method.
    }

    /**
     * Flag variable for the uploadType.
     *
     * There are several different formelements available to upload files:
     *
     * @param $uploadType ajaxupload, apc, uploadify, html
     * @return Koch_Formelement_File
     */
    public function setUploadType($uploadType)
    {
        $this->uploadType = $uploadType;

        return $this;
    }

    public function render()
    {
        switch($this->uploadType)
        {
            default:
            case 'ajaxupload':
                if(false === class_exists('Koch_Formelement_Uploadajax', false))
                {
                    include __DIR__ . '/uploadajax.php';
                }
                return new Koch_Formelement_Uploadajax();
                break;
            case 'apc':
                if(false === class_exists('Koch_Formelement_Uploadapc', false))
                {
                    include __DIR__ . '/uploadapc.php';
                }
                return new Koch_Formelement_Uploadapc();
                break;
            case 'uploadify':
                if(false === class_exists('Koch_Formelement_Uploadify', false))
                {
                    include __DIR__ . '/uploadify.php';
                }
                return new Koch_Formelement_Uploadify();
                break;
            case 'html':
                /**
                 * Fallback to normal <input type="file"> upload
                 * Currently not using the render method of the parent class
                 * return parent::render();
                 */
                return '<input type="file" name="file[]" multiple="true">';
                break;
        }
    }

    /**
     * Magic-Method for rendering the subclass formelements.
     *
     * The render method needs a bit magic to render formelement objects directly.
     * See the short returns calls like the following above:
     *
     *      return new Koch_Formelement_Uploadajax();
     *
     * The long form is:
     *
     *      $formelement = new Koch_Formelement_Uploadajax();
     *      $formelement->render();
     */
    public function __toString()
    {
        return $this->render();
    }
}
?>
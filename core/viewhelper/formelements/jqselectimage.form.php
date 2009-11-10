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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

/**
 *  Clansuite_Form
 *  |
 *  \- Clansuite_Formelement_Select
 *     |
 *     \- Clansuite_Formelement_JQSelectImage
 */
class Clansuite_Formelement_JQSelectImage extends Clansuite_Formelement_Select implements Clansuite_Formelement_Interface
{
    private $html = null;

    private $directory;

    /**
     * JQSelectImage uses a simple jquery selection to insert the img src into a preview div
     */
    function __construct()
    {
        $this->type = "image";
        parent::setID('images');
    }

    public function getFiles()
    {
        if (!class_exists('Clansuite_Directory')) { require ROOT_CORE.'file.core.php'; }

        $dir = new Clansuite_Directory();

        $files = $dir->getFiles( $this->getDirectory(), true );

        return $files;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Setter for directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Shortcut to $this->setDirectory
     */
    public function fromDirectory($directory)
    {
        $this->setDirectory($directory);

        return $this;
    }

    public function render()
    {
        $files = $this->getFiles();
        
        if(empty($files))
        {
            $this->html = 'There are no images in "/uploads/images/categories" to select. Please upload some.';
        }
        else
        {    
            $this->setOptions($files);
            
            # @todo first image is not displayed... display it.
            
            # Watch out, that the div images/preview is present in the dom, before you assign js function to it via $('#image')
            $javascript = '<script type="text/javascript">
                           $(document).ready(function() {                           
                              $("#images").change(function() {
                                    var src = $("option:selected", this).val();
                                    $("#imagePreview_'.$this->getNameWithoutBrackets().'").html(src ? "<img src=\'" + src + "\'>" : "");
                                });
                            });
                            </script>';
    
            $html =  parent::render().CR.'<div id="imagePreview_'.$this->getNameWithoutBrackets().'"></div>';
    
            $this->html = $html.$javascript;        
        }

        return $this->html;
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>
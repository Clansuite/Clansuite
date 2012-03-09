<?php
   /**
    * Koch Framework
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005-onwards)
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

/**
 *  Koch_Form
 *  |
 *  \- Koch_Formelement_Select
 *     |
 *     \- Koch_Formelement_JQSelectImage
 */
class JQSelectImage extends Select implements Formelement
{
    private $html = null;

    private $directory;

    /**
     * JQSelectImage uses a simple jquery selection to insert the img src into a preview div
     */
    function __construct()
    {
        $this->type = 'image';
    }

    public function getFiles()
    {
        if (false === class_exists('Koch_Directory',false))
        {
            include ROOT_CORE . 'files/file.core.php';
        }

        $dir = new Koch_Directory();

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

        # set "images" hardcoded to identify the select options and append the Name
        parent::setID('images_'.$this->getNameWithoutBrackets());

        if(empty($files))
        {
            $this->html = 'There are no images in "'.$this->getDirectory().'" to select. Please upload some.';
        }
        else
        {
            $this->setOptions($files);

            # @todo first image is not displayed... display it.

            # Watch out, that the div images/preview is present in the dom, before you assign js function to it via $('#image')
            $javascript = '<script type="text/javascript">
                           $(document).ready(function() {
                              $("#images_'.$this->getNameWithoutBrackets().'").change(function() {
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

}
?>
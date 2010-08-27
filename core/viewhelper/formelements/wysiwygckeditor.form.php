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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id: wysiwygckeditor.form.php 3631 2009-11-10 21:31:15Z vain $
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

# conditional include of the parent class
if (false == class_exists('Clansuite_Formelement_Textarea',false))
{ 
    include dirname(__FILE__) . '/textarea.form.php';
}

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Textarea
 *      |
 *      \- Clansuite_Formelement_Wysiwygckeditor
 *
 * @see http://ckeditor.com/ Official Website of CKeditor
 * @see http://docs.cksource.com/ CKEditor Documentations
 * @see http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Integration
 */
class Clansuite_Formelement_Wysiwygckeditor extends Clansuite_Formelement_Textarea implements Clansuite_Formelement_Interface
{
    /**
     * This renders a textarea with the WYSWIWYG editor ckeditor attached.
     */
    public function render()
    {  
        # a) loads the ckeditor javascript files
        $javascript = '<script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE . '/javascript/ckeditor/ckeditor.js"></script>';

        # b) plug it to an specific textarea by ID
        # This script block must be included at any point "after" the <textarea> tag in the page.
        $javascript .= '<script type="text/javascript">
                                CKEDITOR.replace("'.$this->getName().'");
                        </script>';

        # Watch out! Serve html elements first, before javascript dom selections are applied on them!
        return $javascript;
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>
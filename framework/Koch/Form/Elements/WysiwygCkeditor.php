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

namespace Koch\Form\Elements;

use Koch\Form\FormElementInterface;

/**
 * Formelement_WysiwygCkeditor
 *
 * @see http://ckeditor.com/ Official Website of CKeditor
 * @see http://docs.cksource.com/ CKEditor Documentations
 * @see http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Integration
 */
class WysiwygCkeditor extends Textarea implements FormElementInterface
{
    public function __construct()
    {
        self::checkDependencies();
    }

    /**
     * Ensure, that the library is available, before the client requests a non-existant file.
     */
    public static function checkDependencies()
    {
        if (!is_file(ROOT_THEMES_CORE . 'javascript/ckeditor/ckeditor.js')) {
            exit('Ckeditor Javascript Library missing!');
        }
    }

    /**
     * This renders a textarea with the WYSWIWYG editor ckeditor attached.
     */
    public function render()
    {
        // a) loads the ckeditor javascript files
        $javascript = '<script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE . 'javascript/ckeditor/ckeditor.js"></script>';

        // b) plug it to an specific textarea by ID
        // This script block must be included at any point "after" the <textarea> tag in the page.
        $javascript .= '<script type="text/javascript">
                                CKEDITOR.replace("'.$this->getName().'");
                        </script>';

        // Watch out! Serve html elements first, before javascript dom selections are applied on them!
        return $javascript;
    }
}

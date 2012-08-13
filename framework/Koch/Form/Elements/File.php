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

use Koch\Form\Elements\Input;
use Koch\Form\FormElementInterface;

class File extends Input implements FormElementInterface
{
    /**
     * Flag variable for the uploadType.
     *
     * There are several different formelements available to upload files:
     *
     * 1) Ajaxupload    -> UploadAjax.php
     * 2) APC           -> UploadAPC.php
     * 3) Uploadify     -> Uploadify.php
     * 4) Default HTML  -> this class
     *
     * @string
     */
    protected $uploadType;

    public function __construct()
    {
        $this->type = 'file';

        // Watch out, that the opening form tag needs the enctype="multipart/form-data"
        // else you'll get the filename only and not the content of the file.
        // Correct encoding is automatically set, when using $form->addElement() method.
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
        switch ($this->uploadType) {
            default:
            case 'ajaxupload':
                if (false === class_exists('UploadAjax', false)) {
                    include __DIR__ . '/UploadAjax.php';
                }

                return new \Koch\Form\Elements\UploadAjax();
                break;
            case 'apc':
                if (false === class_exists('UploadAPC', false)) {
                    include __DIR__ . '/UploadAPC.php';
                }

                return new \Koch\Form\Elements\UploadAPC();
                break;
            case 'uploadify':
                if (false === class_exists('Uploadify', false)) {
                    include __DIR__ . '/Uploadify.php';
                }

                return new \Koch\Form\Elements\Uploadify();
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
     *      return new Koch\Form\Element\UploadAjax();
     *
     * The long form is:
     *
     *      $formelement = new Koch\Form\Element\UploadAjax();
     *      $formelement->render();
     */
    public function __toString()
    {
        return $this->render();
    }
}

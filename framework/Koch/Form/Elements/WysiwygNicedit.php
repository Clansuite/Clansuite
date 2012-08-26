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

namespace Koch\Formelement;

/**
 * Formelement_Wysiwygnicedit
 *
 * @see Http://www.nicedit.com/ Official Website of NicEdit
 * @see http://wiki.nicedit.com/ Wiki of NicEdit
 */
class Wysiwygnicedit extends Textarea implements FormElementInterface
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
        if (!is_file(ROOT_THEMES_CORE . 'javascript/nicedit/nicedit.js')) {
            exit('NicEdit Javascript Library missing!');
        }
    }

    /**
     * This renders a textarea with the WYSWIWYG editor NicEdit attached.
     */
    public function render()
    {
        // a) loads the nicedit javascript file
        $javascript = '<script src="'.WWW_ROOT_THEMES_CORE . 'javascript/nicedit/nicedit.js'. '" type="text/javascript"></script>';

        // b) handler to attach nicedit to all textareas
        $javascript .= "<script type=\"text/javascript\">// <![CDATA[
                        var wysiwyg;
                            bkLib.onDomLoaded(function() {
                              wysiwyg = new nicEditor({
                                    fullPanel : true,
                                    iconsPath : '" . WWW_ROOT_THEMES_CORE . "/javascript/nicedit/nicEditorIcons.gif',
                                    maxHeight : 320,
                                    bbCode    : true,
                                    xhtml     : true
                                  }).panelInstance('".$this->name."');
                            });
                            // ]]></script>";

        // wysiwyg.instanceById('page_body').saveContent();

        /**
         * c) css style
         *
         * Developer Notice
         *
         * nicEdit has the following CSS classes:
         *
         * .nicEdit-panelContain
         * .nicEdit-panel
         * .nicEdit-main
         * .nicEdit-button
         * .nicEdit-select
         */
        $html = '<style type="text/css">'.CR.'
                 .nicEdit-main {
                    background-color: #eee !important;
                    font-size: 16px;
                    padding: 3px;
                    }'.CR.'
                </style>';

        // if we are in inheritance mode, skip this, the parent class handles this already
        return $javascript.$html;
    }
}

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

if (!class_exists('Clansuite_Formelement')) { require ROOT_CORE . 'viewhelper/formelement.core.php'; }

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Textarea
 *
 * This class renders the formelement textarea.
 * It gives you the option to add a JavaScript WYSIWYG editor as textarea replacement.
 */
class Clansuite_Formelement_Textarea extends Clansuite_Formelement implements Clansuite_Formelement_Interface
{
    /**
     * Flag variable for the editorType.
     *
     * There are several different wysiwyg editor formelements available:
     *
     * 1) Nicedit       -> wysiwygnicedit.form.php
     * 2) TinyMCE       -> wysiwygtinymce.form.php
     * 3) CKEditor      -> wysiwygckeditor.form.php
     * 4) markItUp      -> wysiwygmarkItUp.form.phg  => DEFAULT
     * 5) Default HTML  -> this class
     *
     * @string
     */
    protected $editorType;

    /**
     * width of textarea in letters
     *
     * @var int
     */
    protected $cols = 0;

    /**
     * height of textarea in rows
     *
     * @var int
     */
    protected $rows = 0;

    protected $name, $wysiwyg;

    public function __construct()
    {
        $this->type  = 'textarea';

        #do not return this, but return the class defined by the factory
        return $this;
    }

    public function setEditorType($editorType)
    {
        $this->editorType = $editorType;

        return $this;
    }

    /**
     * defines width of textarea
     *
     * @param int $cols
     */
    public function setCols($cols)
    {
        $this->cols = $cols;

        return $this;
    }

    /**
     * get defined width of textarea
     *
     * @param int $cols
     */
    public function getCols($cols)
    {
        return $this->cols;
    }

    /**
     * define height of textarea in rows
     *
     * @param int $rows
     */
    public function setRows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * get defined height of textarea in rows
     *
     * @param int $rows
     */
    public function getRows($rows)
    {
        return $this->rows;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setWysiwygContent($wysiwyg)
    {
        $this->wysiwyg = $wysiwyg;

        return $this;
    }

    public function getWysiwygContent()
    {
        return $this->wysiwyg;
    }

    /**
     * Factory Pattern
     */
    public function render()
    {
        /**
         * Switch for editorType
         */
        switch ($this->editorType)
        {
            default:
             case 'markitup':
                    if (!class_exists('Clansuite_Formelement_Wysiwygmarkitup')) { include 'wysiwygmarkitup.form.php'; }
                    return new Clansuite_Formelement_Wysiwygmarkitup($this);
                break;
            case 'nicedit':
                    if (!class_exists('Clansuite_Formelement_Wysiwygnicedit')) { include 'wysiwygnicedit.form.php'; }
                    # passing 'this' into the child. so that child has a reference back to the parent
                    $wysiwyg = new Clansuite_Formelement_Wysiwygnicedit($this);
                    $this->setWysiwygContent($wysiwyg->render());
                    return $this->render_textarea();
                break;
            case 'ckeditor':
                    if (!class_exists('Clansuite_Formelement_Wysiwygckeditor')) { include 'wysiwygckeditor.form.php'; }
                    return new Clansuite_Formelement_Wysiwygckeditor($this);
                break;
            case 'tinymce':
                    if (!class_exists('Clansuite_Formelement_Wysiwygtinymce')) { include 'wysiwygtinymce.form.php'; }
                    return new Clansuite_Formelement_Wysiwygtinymce();
                break;
            case 'html':
                    # Fallback to normal <textarea>
                    return $this->render_textarea();
                break;
        }
    }

    /**
     * Renders a normal html textarea representation.
     * Without wysiwyg editor attached!
     *
     * @return $html HTML Representation of an textarea
     */
    public function render_textarea()
    {
        $html = ' ';

        /**
         * Attach content of WYSIWYG
         */
        $html .= $this->getWysiwygContent();

        /**
         * Opening of tag
         */
        $html .= '<textarea';
        $html .= (bool)$this->id ? ' id="'.$this->id.'"' : null;
        $html .= (bool)$this->name ? ' name="'.$this->name.'"' : null;
        $html .= (bool)$this->size ? ' size="'.$this->size.'"' : null;
        $html .= (bool)$this->cols ? ' cols="'.$this->cols.'"' : null;
        $html .= (bool)$this->rows ? ' rows="'.$this->rows.'"' : null;
        $html .= (bool)$this->class ? ' class="'.$this->class.'"' : null;
        $html .= (bool)$this->disabled ? ' disabled="disabled"' : null;
        $html .= (bool)$this->maxlength ? ' maxlength="'.$this->maxlength.'"' : null;
        $html .= (bool)$this->style ? ' style="'.$this->style.'"' : null;
        $html .= '>';

        /**
         * Content of tag (value)
         */
        $html .= $this->getValue();

        /**
         * Closing of tag
         */
        $html .= "</textarea>\n";

        #clansuite_xdebug::printR($html);

        return $html;
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>
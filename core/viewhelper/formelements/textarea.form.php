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
    * @link       http://gna.org/projects/
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Formelement',false)) { require ROOT_CORE . 'viewhelper/formelement.core.php'; }

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
     * Flag variable for the What-You-See-Is-What-You-Get Editor.
     *
     * There are several different wysiwyg editor formelements available:
     *
     * 1) Nicedit   -> wysiwygnicedit.form.php
     * 2) TinyMCE   -> wysiwygtinymce.form.php
     * 3) CKEditor  -> wysiwygckeditor.form.php
     * 4) markItUp  -> wysiwygmarkItUp.form.phg
     *
     * @var string
     */
    private $editor;

    /**
     * @var int width of textarea in letters
     */
    public $cols = 0;

    /**
     * @var int height of textarea in rows
     */
    public $rows = 0;

    /**
     * @var object of the wysiwyg editor
     */
    private $editorObject;

    public function __construct()
    {
        $this->type  = 'textarea';

        return $this;
    }

    public function setEditor($editor = null)
    {
        # if no editor is given, take the one definied in configuration
        if($editor == null)
        {
            $config = Clansuite_CMS::getInjector()->instantiate('Clansuite_Config');
            $editor = $config['editor']['type'];
            unset($config);
        }

        $this->editor = strtolower($editor);

        return $this;
    }

    public function getEditor()
    {
        return $this->editor;
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
    public function getCols()
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
    public function getRows()
    {
        return $this->rows;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setEditorFormelement(Clansuite_Formelement_Interface $editorObject)
    {
        $this->editorObject = $editorObject;

        return $this;
    }

    public function getEditorFormelement()
    {
        if(empty($this->editorObject))
        {
            return $this->setEditorFormelement($this->editorFactory());
        }
        else
        {
            return $this->editorObject;
        }
    }

    /**
     * editorFactory
     * loads and instantiates an wysiwyg editor object
     */
    private function editorFactory()
    {
        $name = $this->getEditor();
        Clansuite_Xdebug::firebug($name);

        # construct classname
        $classname = 'Clansuite_Formelement_Wysiwyg'. $name;

        # load file
        if (class_exists($classname, false) == false)
        {
            require ROOT_CORE.'viewhelper/formelements/wysiwyg'.$name.'.form.php';
        }

        # instantiate
        $editor_formelement = new $classname();

        return $editor_formelement;
    }

    /**
     * At some point in the lifetime of this object you decided that this textarea should be a wysiwyg editor.
     * The editorFactory will load the file and instantiate the editor object. But you already defined some properties
     * like Cols or Rows for this textarea. Therefore it's now time to transfer these properties to the editor object.
     * Because we don't render this textarea, but the requested wysiwyg editor object.
     */
    private function transferPropertiesToEditor()
    {
        # get editor formelement
        $formelement = $this->getEditorFormelement();

        # transfer props from $this to editor formelement
        $formelement->setRequired($this->required);
        $formelement->setRows($this->rows);
        $formelement->setCols($this->cols);
        $formelement->setLabel($this->label);
        $formelement->setName($this->name);
        $formelement->setValue($this->value);

        # return the editor formelement, to call e.g. render() on it
        return $formelement;
    }

    /**
     * Renders a normal html textarea representation when
     * no wysiwyg editor object is attached to this textarea object.
     * Otherwise the html content of the wysiwyg editor is prepended
     * to the textarea html.!
     *
     * @return $html HTML Representation of an textarea
     */
    public function render()
    {
        $html = '';

        /**
         * Attach HTML content of WYSIWYG Editor
         */
        if(empty($this->editor) == false)
        {
            $html .= $this->getEditorFormelement()->transferPropertiesToEditor()->render();
        }

        /**
         * Opening of textarea tag
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
         * Content between tags (value)
         */
        $html .= htmlspecialchars($this->getValue());

        /**
         * Closing of textarea tag
         */
        $html .= "</textarea>\n";

        return $html;
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>
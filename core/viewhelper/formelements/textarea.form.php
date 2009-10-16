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
    * @version    SVN: $Id: layout.core.php 2870 2009-03-25 23:21:42Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Formelement')) { require ROOT_CORE . 'viewhelper/formelement.core.php'; }

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Textarea
 */
class Clansuite_Formelement_Textarea extends Clansuite_Formelement implements Clansuite_Formelement_Interface
{
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

    /**
     * defines width of textarea in letters
     *
     * @param int $cols
     */
    public function setCols($cols)
    {
        $this->cols = $cols;
        
        return $this;
    }

    /**
     * defines height of textarea in rows
     *
     * @param int $rows
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
        
        return $this;
    }


    public function __construct()
    {
        $this->type  = 'textarea';

        return $this;
    }

    public function render()
    {
        /**
         * Opening of tag
         */
        $html  = '<textarea ';
        $html .= (bool)$this->id ? ' id="'.$this->id.'"' : null;
        $html .= (bool)$this->name ? ' name="'.$this->name.'"' : null;
        $html .= (bool)$this->size ? ' size="'.$this->size.'"' : null;
        $html .= (bool)$this->cols ? ' cols="'.$this->cols.'"' : null;
        $html .= (bool)$this->rows ? ' rows="'.$this->rows.'"' : null;
        $html .= (bool)$this->class ? ' class="'.$this->class.'"' : null;
        $html .= (bool)$this->disabled ? ' disabled="disabled"' : null;
        $html .= (bool)$this->maxlength ? ' maxlength="'.$this->maxlength.'"' : null;
        $html .= '>';

        /**
         * Content of tag (value)
         */
        $html .= $this->getValue();

        /**
         * Closing of tag
         */
        $html .= "</textarea>\n";

        return $html;
    }
}
?>
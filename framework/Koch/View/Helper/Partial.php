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

namespace Koch\View\Helper;

/**
 * Koch FrameworkBlockcontainer
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Layout
 */
class partial extends Layout
{
    // var $_blocks contains all block elements as Separate Objects
    private $_blockObjects = array();

    // no constructor
    public function __construct()
    {

    }

    // add block object
    public function addBlock($name, Block $block)
    {
        $this->_blockObjects[$name] = $block;
    }

    // execute each block in the container
    public function execute()
    {
        foreach ($this->_blockObjects as $block) {
            $block->execute(); // $_blocks[] = $smarty->fetch("blockTemplate.tpl");
        }
    }

    /**
     * Render Blocks
     */
    public function render($params, $smarty)
    {
        // Set Smarty as View to each Block
        foreach ($this->_blockObjects as $block) {
            $block->setView($smarty);
        }

        // Assign BlockObjects to Smarty
        $smarty->assign('block', $this->_blockObjects);

        // Display it via /core/tools/sidebar.tpl
        // which loops over each Array Element (one block) and displays it
        $smarty->display('core/tools/sidebar.tpl');
    }

    /**
     * Getter for $this->_blockObjects
     */
    public function getBlocks()
    {
        return $this->_blockObjects;
    }
}

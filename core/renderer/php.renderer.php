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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Renderer Class - Renderer for native PHP Templates
 *
 * This is a wrapper/adapter for using native PHP as Template Engine.
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005-onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Renderer
 */

class Clansuite_Renderer_Php extends Clansuite_Renderer_Base
{
    private $file;
    #private $template;
    private $data = array();

    /**
     * @param string $filename
     * @param string $directory
     * @return Content of php template
     *
     * @todo
     */
    public function fetch($filename = null, $directory = null)
    {
        $file = '';

        if($filename === null)
        {
            $file = $directory . DS . $filename . '.tpl';
        }
        else
        {
            $file = $this->file;
        }

        if(is_file($file) === true)
        {
            /**
             * extract all templatevariables
             * but do not overwrite an existing variable
             * if there is a collision just prefix them with invalid_
             */
            extract($this->data, EXTR_REFS | EXTR_PREFIX_INVALID, 'invalid_');

            ob_start();
            include $file;
            $content = ob_get_contents();
            ob_end_clean();

            return $content;
        }
        else
        {
            throw new Clansuite_Excpetion('PHP Renderer Error: Template ' . $file . ' not found!', 99);
        }
    }

    /**
     * Assign specific variable to the template
     *
     * @param mixed $key Object with template vars (extraction method fetch), or array or key/value pair
     * @param mixed $value Variable value
     * @return Clansuite_Renderer_PHP
     */
    public function assign($key, $value=null)
    {
        if(is_object($key))
        {
            $this->data[$key] = $value->fetch();
        }
        elseif(is_array($key))
        {
            array_merge($this->data, $key);
        }
        else
        {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Display the rendered template
     *
     * @return string HTML Representation of Template with Vars
     */
    public function render()
    {
        return $this->fetch();
    }

    /**
     * Render the content and return it
     *
     * @example
     * echo new Clansuite_Renderer_PHP($file, array('title' => 'My title'));
     *
     * @return string  HTML Representation
     */
    public function __toString()
    {
        return $this->render();
    }
}
?>
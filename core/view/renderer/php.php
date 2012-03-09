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
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\View\Renderer;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch FrameworkRenderer Class - Renderer for native PHP Templates
 *
 * This is a wrapper/adapter for using native PHP as Template Engine.
 *
 * @author     Jens-Andr� Koch <vain@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005-onwards)
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Renderer
 */

class Php extends Renderer_Base
{
    private $file;

    private $data = array();

    /**
     * Executes the template rendering and returns the result.
     *
     * @param string $template Template Filename
     * @param array $data Data to extract to the local scope.
     * @return string
     */
    public function fetch($filename = null, array $data = array())
    {
        $file = '';

        if($filename === null)
        {
            # @todo where does dir come from???
            $file = $directory . DS . $filename . '.tpl';
        }
        else
        {
            $file = $this->file;
        }

        if(is_file($file) === true)
        {
            /**
             * extract all template variables to local scope,
             * but do not overwrite an existing variable.
             * on collision, prefix variable with "invalid_".
             */
            extract($this->data, EXTR_REFS | EXTR_PREFIX_INVALID, 'invalid_');

            ob_start();
            include $file; # conditional include; not require !
            return ob_get_clean();
        }
        else
        {
            throw new Koch_Excpetion('PHP Renderer Error: Template ' . $file . ' not found!', 99);
        }
    }

    /**
     * Assign specific variable to the template
     *
     * @param mixed $key Object with template vars (extraction method fetch), or array or key/value pair
     * @param mixed $value Variable value
     * @return Koch_Renderer_PHP
     */
    public function assign($key, $value=null)
    {
        if(is_object($key))
        {
            # @todo pull object props to array
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
    public function render($template, $viewdata)
    {
        $this->assign($viewdata);

        return $this->fetch($template);
    }

    /**
     * Render the content and return it
     *
     * @example
     * echo new Koch_Renderer_PHP($file, array('title' => 'My title'));
     *
     * @return string  HTML Representation
     */
    public function __toString()
    {
        return $this->render();
    }
}
?>
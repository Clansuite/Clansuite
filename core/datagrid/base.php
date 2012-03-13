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
    * @copyright  Jens-André Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\Datagrid;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Datagrid Base
 *
 * It is the parent class for the datagrid
 * and provides common methods.
 *
 * @author Florian Wolf <xsign.dll@clansuite.com>
 */
class Base
{
    /**
     * The data to render in the grid.
     *
     * @var array
     */
    public $data;

    // scoped vars
    private $alias;
    private $id;
    private $name;
    private $class;
    private $style;

    /**
     * Label for the datagrid
     *
     * @var string
     */
    private $label = 'Label';

    /**
     * The caption (<caption>...</caption>) for the datagrid
     *
     * @var string
     */
    private $caption = 'Caption';

    /**
     * The description for the datagrid
     *
     * @var string
     */
    private $description = 'This is a Clansuite Datagrid.';

    /**
     * Base URL for the Datatable
     *
     * @var string
     */
    private static $baseURL = null;


    /**
     *  Setter Methods for a Datagrid
     */
    public function setAlias($alias)
    {
        $alias = str_replace('\\', '_', $alias);
        $this->alias = $alias;
        return $this;
    }

    /**
     * Sets the BaseURL
     *
     * @param string $baseURL The baseURL for the datatable
     */
    public function setBaseURL($baseURL = null)
    {
        if(self::$baseURL === null)
        {
            self::$baseURL = Koch\MVC\HttpRequest::getRequestURI();
        }
        else
        {
            self::$baseURL = $baseURL;
        }
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setStyle($style)
    {
        $this->style = $style;
        return $this;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function setCaption($caption)
    {
        $this->caption = $caption;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set datagrid state from options array
     *
     * @param array $options
     * @return Datagrid
     */
    public function setOptions(array $options)
    {
        foreach($options as $key => $value)
        {
            $method = 'set' . ucfirst($key);

            if(method_exists($this, $method))
            {
                # setter method exists
                $this->$method($value);
            }
            else
            {
                throw new Clansuite_Exception('Unknown property ' . $key . ' for Datagrid');
            }
        }
        return $this;
    }

    /**
     * Getter Methods for Datagrid
     */

    public function getAlias()
    {
        return $this->alias;
    }

    public static function getBaseURL()
    {
        return self::$baseURL;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStyle()
    {
        return $this->style;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add an url-string to the baseurl
     *
     * @example
     *   $sUrl = $this->appendUrl('dg_Sort=0:ASC');
     *
     * @param string $appendString String to append to the URL.
     */
    public static function appendUrl($appendString)
    {
        $separator = '?';

        if( preg_match('#\?#', self::getBaseURL()) )
        {
            $separator = '&amp;';
        }

        $cleanAppendString = preg_replace('#^&amp;#', '', $appendString);
        $cleanAppendString = preg_replace('#^&#', '', $cleanAppendString);
        $cleanAppendString = preg_replace('#^\?#', '', $cleanAppendString);
        $cleanAppendString = preg_replace('#&(?!amp;)#i', '&amp;', $cleanAppendString);

        return self::getBaseURL() . $separator . $cleanAppendString;
    }
}
?>
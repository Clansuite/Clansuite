<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\Formelement;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

class Radiolist extends Radio implements FormelementInterface
{
    protected $options;

    public function __construct()
    {
        $this->type = 'radio';
    }

    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    protected $separator = '<br/>';

    public function render()
    {
        $options = array( 'option1' => 'berlin',
                          'option2' => 'new york');

        $this->setOptions($options);

        $i=0;
        $html = '';
        while ( list($key, $value) = each($this->options))
        {
            # setup a new radio formelement
            $radio = new Koch_Formelement_Radio();
            $radio->setValue($key)
                  ->setName($value)
                  ->setDescription($value)
                  ->setLabel($value);

            # check the element, if value is "active"
            if( $this->value == $key)
            {
                $radio->setChecked();
            }

            # assign it as output
            $html .= $radio;

            #Koch_Debug::printR($html);

            # if we have more options comming up, add a seperator
            if (++$i!=count($this->options))
            {
                $html .= $this->separator;
            }
        }
        return $html;
    }
}
?>
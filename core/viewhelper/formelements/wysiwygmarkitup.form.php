<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andr� Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Formelement_Textarea')) { require 'textarea.form.php'; }

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Textarea
 *      |
 *      \- Clansuite_Formelement_Wysiwygmarkitup
 *
 * @see http://markitup.jaysalvat.com/home/ Official Website of markItUp!
 */
class Clansuite_Formelement_Wysiwygmarkitup extends Clansuite_Formelement_Textarea implements Clansuite_Formelement_Interface
{
    protected $factory = null;
    
    public function __construct($factory = null)
    {
        # I know! uhm... this is... a ball of mud. @todo
        if(isset($factory) and is_object($factory))
        {
            $this->factory = $factory;
        }
        
        return $this;   
    }
    
    /**
     * This renders a textarea with the WYSWIWYG editor markItUp! attached.
     */
    public function render()
    {
        if(isset($this->factory) and $this->factory !== null)
        {
            $name = $this->factory->getName(); 
        }
        else
        {
            $name = $this->getName();   
        }
        
        # a) loads the markitup javascript files
        #$javascript = '<script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE . '/javascript/jquery/jquery.js"></script>';
        $javascript .= '<script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE . '/javascript/markitup/jquery.markitup.js"></script>';
                       
        # b) load JSON default settings
        $javascript .= '<script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE . '/javascript/markitup/sets/default/set.js"></script>';
        
        # c) include CSS
        $css = '<link rel="stylesheet" type="text/css" href="'.WWW_ROOT_THEMES_CORE . '/javascript/markitup/skins/markitup/style.css" />
                 <link rel="stylesheet" type="text/css" href="'.WWW_ROOT_THEMES_CORE . '/javascript/markitup/sets/default/style.css" />';
                 
        # d) plug it to an specific textarea by ID
        $javascript .= "<script type=\"text/javascript\">// <![CDATA[                           
                           $(document).ready(function() {
                              $(\"textarea:visible\").markItUp(mySettings);
                           });
                        // ]]></script>";

        # if we are in inheritance mode, skip this, the parent class handles this already
        if(is_object($this->factory))
        {
             # e) set id markItUp
            parent::setID($name);
            parent::setCols('80');
            parent::setRows('20');
            $html = parent::render_textarea();
            #clansuite_xdebug::printR($html);
        }

        #clansuite_xdebug::printR($javascript.$css.$html);

        return $javascript.$css.$html;
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>
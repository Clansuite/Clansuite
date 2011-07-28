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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

# conditional include of the parent class
if (false == class_exists('Clansuite_Formelement_Select',false))
{
    include __DIR__ . '/select.form.php';
}

/**
 *
 *  Clansuite_Form
 *  |
 *  \- Clansuite_Formelement_Select
 *     |
 *     \- Clansuite_Formelement_Selectlanguage
 */
class Clansuite_Formelement_Selectlanguage extends Clansuite_Formelement_Select implements Clansuite_Formelement_Interface
{
    public function __construct()
    {
        # include locale arrays
        include ROOT_CORE . 'gettext/locales.gettext.php';
               
        /**
         * prepare array structure for dropdown ( key => value )
         */
        $options = array();

        foreach($l10n_sys_locales as $locale)
        {
            # key is the shortname
            $key = $locale['lang-www'];
            
            # show language native name and shortname in the drop-down
            $value = $locale['lang-native'] . ' (' . $locale['lang-www'] . ')';
                        
            $options[$key] = $value;
        }

        $this->setOptions($options);
        
        $this->setLabel( _('Select Language') );
    }
}
?>
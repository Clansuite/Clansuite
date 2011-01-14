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
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id: index.module.php 2625 2008-12-09 00:04:43Z vain $
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Mibbitirc
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Mibbitirc
 */
class Clansuite_Module_Mibbitirc extends Clansuite_Module_Controller
{
    public function initializeModule()
    {
        $this->getModuleConfig();
    }

    public function action_show()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/mibbitirc/show');

        # Try to get Mibbit Options from config or set default ones
        $mibbit_options['nick']        = preg_replace('/ /', '_', $_SESSION['user']['nick']);

        $mibbit_options['title']       = self::getConfigValue('mibbit_irc_page_title',      'Clansuite Live Chat');
        $mibbit_options['nick_prefix'] = self::getConfigValue('mibbit_irc_nickname_prefix', 'Guest');
        $mibbit_options['server']      = self::getConfigValue('mibbit_irc_server',          'irc.quakenet.org');
        $mibbit_options['channel']     = self::getConfigValue('mibbit_irc_channel',         '#clansuite');
        $mibbit_options['width']       = self::getConfigValue('mibbit_irc_width',           '500');
        $mibbit_options['height']      = self::getConfigValue('mibbit_irc_height',          '280');

        # Set options to the view
        $this->getView()->assign('mibbit_options', $mibbit_options);

        # Output
        $this->display();
    }
}
?>
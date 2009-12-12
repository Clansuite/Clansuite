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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: index.module.php 2625 2008-12-09 00:04:43Z vain $
    */

// Security Handler
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Module - Shockvoiceviewer
 *
 * @author     Jens-Andr� Koch <vain@clansuite.com>
 * @copyright  Jens-Andr� Koch (2005 - onwards)
 * @version    0.2, 29.06.2009
 */
class Module_Shockvoiceviewer extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Shockvoiceviewer->execute()
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        #$this->moduleconfig = $this->getModuleConfig();
    }

    /**
     * Widget Shockvoiceviewer
     *
     * @todo set serverdata to configfile
     */
    public function widget_shockvoiceviewer()
    {
        # ensure the php extension cURL is loaded
        if (!function_exists('curl_init'))
        {
            return _('The module shockvoiceviewer requires cURL.');
        }

        # set modulename, because outside this widget a different module could be active
        $modulename = 'shockvoiceviewer';
        # insert the modulename to construct a configfilename and fetch it
        //$this->getModuleConfig(ROOT_MOD.$modulename.DS.$modulename.'.config.php');
        $this->getModuleConfig('shockvoiceviewer');

        $host       = $this->getConfigValue('hostname', 'druckwelle-hq.de');
        $port       = $this->getConfigValue('port', '8010');
        $server     = $this->getConfigValue('serverid', '1');

        # load Shockvoice Viewer Class
        require_once(ROOT_MOD.$modulename.DS.'library/shockvoice.class.php');

        # instantiate with connection data from config
        $SVQ = new Clansuite_Shockvoice_Query($host , $port , '8010', $server, 'UTF-8');

        #clansuite_xdebug::printr($SVQ);

        # get the view and assign data
        $this->getView()->assign('serverinfos', $SVQ->getShockvoice());
    }
}
?>
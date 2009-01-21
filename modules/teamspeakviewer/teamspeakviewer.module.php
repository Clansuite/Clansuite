<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: news.module.php 2345 2008-08-02 04:35:23Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Module - Teamspeakviewer
 *
 */
class Module_Teamspeakviewer extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Teamspeakviewer -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {

    }

    public function widget_tsministatus($params)
    {
        $smarty = $this->getView();

        /*
        # get data
        $tsviewer = Doctrine_Query::create()
                    ->select('s.*')
                    ->from('CStsviewer s')
                    ->execute(array(), Doctrine::HYDRATE_ARRAY);
        */

        # hardcoded for testing
        $server_address  = 'clansuite.com';
        $server_tcpport  = '51234';
        $server_udpport  = '8000';

        $server_password = '';
        $server_location = 'Somewhere';
        $guest_nickname  = 'Guest';
        $msg_ok_boolean  = true;

        # load query object
        $ts_query = new tcpquery($server_address, $server_tcpport, $server_udpport);
        $serverinfo = $ts_query->get_serverinfo();

        if(is_array($serverinfo))
        {

            $serverinfo['request_ok'] = true;

            # assign
            $serverinfo['server_address']  = $server_address;
            $serverinfo['server_tcpport']  = $server_tcpport;
            $serverinfo['guest_nickname']  = $guest_nickname;
            $serverinfo['server_location'] = $server_location;
        }
        else
        {
            $serverinfo['request_ok'] = false;
            $serverinfo['server_address']  = $server_address;
            $serverinfo['server_tcpport']  = $server_tcpport;
        }

        $smarty->assign('serverinfo', $serverinfo);
    }

    public function widget_tsviewer($params)
    {
        $smarty = $this->getView();

        /*
        # get data
        $serverinfo = Doctrine_Query::create()
                    ->select('s.*')
                    ->from('CStsviewer s')
                    ->execute(array(), Doctrine::HYDRATE_ARRAY);
        */

        # hardcoded for testing
        $serverinfo['server_id'] = '77135';

        # assign
        $smarty->assign('serverinfo', $serverinfo);

    }
}

class tcpquery
{
    private $server_address = 'clansuite.com';
    private $server_tcpport = '51234';
    private $server_udpport = '8787';
    public  $serverinfos    = array();
    private $socket;

    function __construct($server_address, $server_tcpport, $server_udpport)
    {
        $this->server_address = $server_address;
        $this->server_tcpport = $server_tcpport;
        $this->server_udpport = $server_udpport;

        if($this->connect())
        {
            return;
        }
        else
        {
            return false;
        }
    }

    function connect()
    {
        $this->socket = @fsockopen($this->server_address, $this->server_tcpport, $errno, $errstr, 2);

        if(isset($this->socket))
        {
            if(!$this->socket or !preg_match('/^\[TS\]\s*$/', @fgets($this->socket)))
            {
                return false;
            }

            stream_set_blocking($this->socket, 1);
            return true;
        }
    }

    function get_serverinfo()
    {
        @fputs($this->socket,"si $this->server_udpport\n");

        while($answer = @fgets($this->socket))
        {
            if(preg_match('/^([^\r\n\f=]*)=([^\r\n\f]*)\s*$/', $answer, $info))
            {
                $this->serverinfos[$info[1]] = $info[2];
            }
            else if(preg_match('/^OK\s*$/', $answer))
            {
                $this->disconnect();
                return $this->serverinfos;
            }
            else
            {
                return false;
            }
        }
    }

    function disconnect()
    {
        @fputs($this->socket,"quit\n");
        return true;
    }
}
?>
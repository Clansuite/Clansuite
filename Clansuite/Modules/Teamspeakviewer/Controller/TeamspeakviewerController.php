<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
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

namespace Clansuite\Modules\Teamspeakviewer\Controller;

use Clansuite\Core\Mvc\ModuleController;

/**
 * Clansuite_Module_Teamspeakviewer
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  TeamspeakViewer
 */
class TeamspeakviewerController extends ModuleController
{
    /**
     * Module_Teamspeakviewer -> Execute
     */
    public function _initializeModule()
    {
        // load library / init libacts2
        include ROOT_LIBRARIES . 'libacts2/Absurd.php';
    }

    public function actionList()
    {
        $server = '';
        $starttime = '';
        $diff = '';

        // Switch to Windows-Client-like sorting (default is Absurd_TeamSpeak2_Object::SORT_LINUX [faster])
        define('LIBACTS2_SORTING_TYPE', Absurd_TeamSpeak2_Object::SORT_LINUX);

        // hardcoded for testing
        $server_address  = 'clansuite.com';
        $server_tcpport  = '10011';
        $server_udpport  = '9987';
        $server_password = '';

        $starttime = microtime(true);
        $adv_viewer = new AdvancedTeamSpeak2Viewer();
        Absurd_TeamSpeak2::connect('tcp://'.$server_address.':'.$server_tcpport)
                ->getServerByUdp($server_udpport)
                ->parseViewer($adv_viewer);
        $time = microtime(true) - $starttime;
        $server = $adv_viewer->getView();

        // Get Render Engine
        $view = $this->getView();
        $view->assign('server', $server);
        $view->assign('time', $time);

        $this->display();
    }

    public function widgetTeamspeak3MiniStatus($params)
    {
        $view = $this->getView();

        // Load Teamspeak3 Library
        include __DIR__.'/libraries/teamspeak3.lib.php';

        // hardcoded for testing
        $server_ip         = 'clansuite.com';
        $server_port       = '9987';
        $server_queryport  = '10011';
        $vserver_id        = '1';

        $ts3 = new Clansuite_Teamspeak3_ServerQueryInterface($server_ip, $server_queryport, $vserver_id);
        $ts3->selectVirtualServer(1);
        #\Koch\Debug\Debug::printR($ts3->serverViewer());
        #\Koch\Debug\Debug::printR($ts3->version()); // ok
        #\Koch\Debug\Debug::printR($ts3->channellist());
        #\Koch\Debug\Debug::printR($ts3->instanceinfo()); // ok
        #\Koch\Debug\Debug::printR($ts3->serverinfo()); // ??? what is wrong here?? no return values
        #$ts3->close();

        #\Koch\Debug\Debug::printR($serverinfo);

        $view->assign('serverinfo', $serverinfo);
    }

    public function widgetTeamspeak2MiniStatus($params)
    {
        $view = $this->getView();

        /*
        // get data
        $tsviewer = Doctrine_Query::create()
                    ->select('s.*')
                    ->from('CStsviewer s')
                    ->execute(array(), Doctrine::HYDRATE_ARRAY);
        */

        // hardcoded for testing
        $server_address  = 'clansuite.com';
        $server_tcpport  = '51234';
        $server_udpport  = '8000';

        $server_password = '';
        $server_location = 'Somewhere';
        $guest_nickname  = 'Guest';

        // get server object
        $server = Absurd_TeamSpeak2::connect('tcp://'.$server_address.':'.$server_tcpport)->getServerByUdp($server_udpport);
        $serverinfo = $server->getNodeInfo();

        // unregister the autoloader for performance, because it's only needed (once) here
        spl_autoload_unregister('Absurd::autoload');

        #var_dump($serverinfo);

        if (is_array($serverinfo)) {

            $serverinfo['request_ok'] = true;

            // assign
            $serverinfo['server_address']  = $server_address;
            $serverinfo['server_tcpport']  = $server_tcpport;
            $serverinfo['guest_nickname']  = $guest_nickname;
            $serverinfo['server_location'] = $server_location;
        } else {
            $serverinfo['request_ok'] = false;
            $serverinfo['server_address']  = $server_address;
            $serverinfo['server_tcpport']  = $server_tcpport;
        }

        $view->assign('serverinfo', $serverinfo);
    }

    public function widgetTeamspeak2Viewer($params)
    {
        $view = $this->getView();

        /*
        // get data
        $serverinfo = Doctrine_Query::create()
                    ->select('s.*')
                    ->from('CStsviewer s')
                    ->execute(array(), Doctrine::HYDRATE_ARRAY);
        */

        // hardcoded for testing
        $serverinfo['server_id'] = '77135';

        // assign
        $view->assign('serverinfo', $serverinfo);
    }

    public function widgetTeamspeak3Viewer($params)
    {
        $view = $this->getView();

        /*
        // get data
        $serverinfo = Doctrine_Query::create()
                    ->select('s.*')
                    ->from('CStsviewer s')
                    ->execute(array(), Doctrine::HYDRATE_ARRAY);
        */

        // hardcoded for testing
        $serverinfo['server_id'] = '77135';

        // assign
        $view->assign('serverinfo', $serverinfo);
    }
}

/**
 * Advanced Implemenation of the Absurd_TeamSpeak2_Viewer
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  TeamspeakViewer
 */
class AdvancedTeamSpeak2Viewer #implements Absurd_TeamSpeak2_Viewer

{
    public $view = '';

    public function displayObject(Absurd_TeamSpeak2_Object $object, array $moreSiblings)
    {
        // define image path constant
        if (!defined('TSVIEWER_IMAGES')) {
            define('TSVIEWER_IMAGES', WWW_ROOT . 'modules/teamspeakviewer/images/');
        }

        // Image A = |
        $image_a = '<img src="'.TSVIEWER_IMAGES.'treeimage1.png" alt="tree |"/>';
        // Image B = |-
        $image_b = '<img src="'.TSVIEWER_IMAGES.'treeimage2.png" alt="tree |-"/>';
        // Image C = |_
        $image_c = '<img src="'.TSVIEWER_IMAGES.'treeimage3.png" alt="tree |_"/>';
        // Image D = " " (spacer)
        $image_d = '<img src="'.TSVIEWER_IMAGES.'treeimage4.png" alt="space  "/>';

        // Channel
        $image_channel = '<img src="'.TSVIEWER_IMAGES.'channel.png" alt="Chan:"/>';

        // TS Logo
        $image_ts_logo = '<img src="'.TSVIEWER_IMAGES.'teamspeak_online.png" alt="TS Logo"/>';

        if (count($moreSiblings)) {
            $lastIcon = array_pop($moreSiblings);
            foreach ($moreSiblings as $lvl) {
                $this->view .= ($lvl) ? $image_a : $image_d;
            }
            $this->view .= ($lastIcon) ? $image_b : $image_c;
            $this->view .= '&nbsp;';
        }

        // USER CLIENT
        if ($object instanceof Absurd_TeamSpeak2_Client) {
            // pre Image
            $this->view .= $this->getUserFlagImage($object);
            // Name
            $this->view .= (string) $object;
            // post (R SA CA)
            $this->view .= ' ('. implode(' ', $object->getFlags()). ')';
            $this->view .= $this->getUserPrivilegeImage($object);
        }
        // CHANNEL
        else if ($object instanceof Absurd_TeamSpeak2_Channel && $object['parent'] == -1) {
            $this->view .= (string) $image_channel.'&nbsp;';
            $this->view .= (string) $object;
            $this->view .= ' ('. implode('', $object->getFlags()). ')';
        }
        // SUBCHANNEL
        else if ($object instanceof Absurd_TeamSpeak2_Channel) {
            $this->view .= (string) $image_channel.'&nbsp;';
            $this->view .= (string) $object;
        }
        // TOPLEVEL / ROOT Node
        else {
            $this->view .= (string) $image_ts_logo.'&nbsp; <b>'.$object.'</b>';
        }
        $this->view .= "<br />" . PHP_EOL;
    }

    /**
     * This handles the flags. They are bit-wise set.
     *
     * pprivs - allgemeine privilegien des users
     * 0 ...... keine Einstellungen vorgenommen
     * 1 ...... Channel Commander
     * 2 ...... Voice Request
     * 4 ...... Block Whispers
     * 8 ...... Away
     * 16 .... Mute Microphone
     * 32 .... Mute Speakers
     * 64 .... Recording
     *
     * Releates to
     * 0110000 = 48 : Mute Microphone, Mute Speakers
     * 0000011 = 3 : ChannelCommander, VoiceRequested
     *
     * cprivs - channelprivilegien des users
     * 1 .... ChannelAdmin
     * 2 .... Operator
     * 4 .... Voice
     * 8 .... AutoOperator
     * 16 ... AutoVoice
     *       var_dump($object['cprivs']);
     *       var_dump($object['pprivs']);
     *       var_dump($object['pflags']);
     */
    public function getUserFlagImage(Absurd_TeamSpeak2_Client $client)
    {
        // initialize Variables
        $icon = '';
        $playerPrivs = '';
        $privs = '';

        // fetch Client Info from Absurd_TS2_Object
        $client = $client->getNodeInfo();

        if (array_key_exists('pflags', $client)) {
            $privs = $client['pflags'];

            if ($privs == 0) {
                $icon = 'player_normal.png';
            }             // User default
            if ($privs & 0x01) {
                $icon = 'player_channelcommander.png';
            }   // Channel Commander
            if ($privs & 0x02) {
                $icon = 'player_requestvoice.png';
            }       // Voice Request
            if ($privs & 0x04) {
                $icon = 'player_blockwhispers.png';
            }      // Block Whispers
            if ($privs & 0x08) {
                $icon = 'player_away.png';
            }               // Away
            if ($privs & 0x10) {
                $icon = 'player_mutemicrophone.png';
            }     // Mute Microphone
            if ($privs & 0x20) {
                $icon = 'player_mutespeakers.png';
            }       // Mute Speakers
            if ($privs & 0x40) {
                $icon = 'player_record.png';
            }             // Recording

            return '<img src="'.TSVIEWER_IMAGES.$icon.'" />&nbsp;';
        }
    }

    public function getUserPrivilegeImage(Absurd_TeamSpeak2_Client $client)
    {
        // initialize Variables
        $playerPrivs = '';
        $privs = '';

        // fetch Client Info from Absurd_TS2_Object
        $client = $client->getNodeInfo();

        if (array_key_exists('pprivs', $client)) {
            $privs = $client['pprivs'];
            #if ($privs & 0x10) $playerPrivs .= '';    // Stickey
            #if ($privs & 0x08) $playerPrivs .= '';    // Internal Use
            if ($privs & 0x04) $playerPrivs .= 'R ';   // Registered
            if ($privs & 0x02) $playerPrivs .= 'r';    // Allow Registration
            if ($privs & 0x01) $playerPrivs .= 'SA ';  // ServerAdmin
            if ($privs == 0) $playerPrivs .= 'U ';     // User
        }

        if (array_key_exists('cprivs', $client)) {
            $privs = $client['cprivs'];
            if ($privs & 0x01) $playerPrivs .= 'CA '; // Channel Admin
            if ($privs & 0x02) $playerPrivs .= 'O ';  // Operator
            if ($privs & 0x04) $playerPrivs .= 'V ';  // Voice
            if ($privs & 0x08) $playerPrivs .= 'AO '; // AutoOperator
            if ($privs & 0x10) $playerPrivs .= 'AV '; // AutoVoice
        }

        return $playerPrivs;
    }

    public function getView()
    {
        return $this->view;
    }
}

/**
 * Text Implementation of the Absurd_TeamSpeak2_Viewer
 * This is SimpleTeamSpeak2Viewer by absurdcoding.
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  TeamspeakViewer
 */
class TextTeamSpeak2Viewer #implements Absurd_TeamSpeak2_Viewer

{
    public function displayObject(Absurd_TeamSpeak2_Object $object, array $moreSiblings)
    {
        if (count($moreSiblings)) {
            $lastIcon = array_pop($moreSiblings);
            foreach ($moreSiblings as $lvl) {
                echo ($lvl) ? '&#9474;' : ' ';
            }
            echo ($lastIcon) ? '&#9500;' : '&#9492;';
        }
        echo $object;
        if ($object instanceof Absurd_TeamSpeak2_Client) {
            echo ' (', implode(' ', $object->getFlags()), ')';
        } elseif ($object instanceof Absurd_TeamSpeak2_Channel && $object['parent'] == -1) {
            echo ' (', implode('', $object->getFlags()), ')';
        }
        echo "" . PHP_EOL;
    }
}

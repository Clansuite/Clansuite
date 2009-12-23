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
    *
    * @version    SVN: $Id$
    */

/**
 * Clansuite_Teamspeak3_ServerQueryInterface
 *
 * Purpose: The allmighty TeamSpeak3 ServerQuery Library.
 *
 * This class implements all commands of the Teamspeak3 ServerQuery Manual
 * by using method overloading via the magic method __call.
 * This method takes care of loading the specific serverquery command files.
 *
 * Official Website of Teamspeak
 * @link http://www.teamspeak.com
 *
 * Teamspeak 3 Serverquery Manual
 * @link http://ftp.4players.de/pub/hosted/ts3/server/doc/ts3_serverquery_manual.pdf
 *
 * TeamSpeak Community Forums > TeamSpeak 3 Customization >> Addons & Scripts
 * @link http://forum.teamspeak.com/forumdisplay.php?f=114
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards).
 * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
 * @version    0.1 SVN: $Id$
 *
 * This library is based on TS3Admin.class. Thanks and credits to:
 * @author     Stefan Z. <webmaster@par0noid.de>
 * @license    GNU/GPL
 *
 * @category    Clansuite
 * @package     Libraries
 * @subpackage  Teamspeak
 */
class Clansuite_Teamspeak3_ServerQueryInterface
{
    /**
     * @var boolean Connection Status
     * Default is false
     */
    private $connection_status = false;

    /**
     * @var boolean Login Status
     * Default is false
     */
    private $login_status = false;

    /**
     * @var boolean Server Admin Status
     * Default is false
     */
    private $server_admin_status = false;

    /**
     * @var boolean Virtual Server Selection Status
     * Default is false
     */
    private $vserver_select_status = false;

    /**
     * @var string Server IP
     */
    private $server_ip;

    /**
     * @var integer Query Port
     *
     * Default Port is "10011" TCP
     */
    private $queryport = '10011';

    /**
     * @var integer Virtual Server ID
     *
     * Default Virtual Server ID is "1"
     */
    private $vserver_id = '1';

    /**
     * Pointer to Open Internet or Unix domain socket connection
     *
     * @see fsockopen()
     * @see openConnection()
     * @var mixed | resource pointer or false
     */
    protected $socket;

    /**
     * The following variables change the behaviour of Server Query Commands
     * Check the according setter method for applicable values.
     *
     * @var integer
     */
    private $hostmessagemode;
    private $codec;
    private $textmessagetargetmode;
    private $loglevel;
    private $reasonidentifier;
    private $permissiongroupdatabasetypes;
    private $permissiongrouptypes;
    private $tokentype;

    /**
     * Constructor
     *
     * Instantiate the Clansuite_Teamspeak3_ServerQueryInterface object
     * with Server-IP, Server-QueryPort and Virtual Server ID
     *
     * @param string  $server_ip        server IP
     * @param integer $queryport        tcp queryport
     * @param integer $virtualServer_id virtual server id
     */
    public function __construct($server_ip, $queryport, $virtualserver_id)
    {
        $this->openConnection($server_ip, $queryport, $virtualserver_id);
    }

    /**
     * open connection
     *
     * @param string  $server_ip        server IP
     * @param integer $queryport        tcp queryport
     * @param integer $virtualServer_id virtual server id
     * @return boolean true on active connection
     */
    public function openConnection($server_ip, $queryport, $virtualserver_id)
    {
        $this->server_ip  = $server_ip;
        $this->queryport  = $queryport;
        $this->vserver_id = $virtualserver_id;

        # open remote connection to the server
        $this->socket = @fsockopen($this->server_ip, (int) $this->queryport, $errno, $errstr, 5);

        if($this->socket === false)
        {
            trigger_error('Connection to '. $this->server_ip . ':' . $this->queryport . ' [' . $this->vserver_id . '] could not be established.');
        }
        else
        {
            # check if the socket response qualifies the server as a TS3 instance
            if( (string) fgets($this->socket, 4) == 'TS3')
            {
                $this->setConnectionActive();
                return true;
            }
            else
            {
                trigger_error('A Teamspeak 3 Server not found.');
            }
        }
    }

    /**
     * close connection
     *
     * @todo what about sending server query command "QUIT"?
     */
    public function closeConnection()
    {
        @fclose($this->socket);
    }

    /**
     * Proxy/Convenience Method for openConnection()
     *
     * @see openConnection()
     */
    public function connect($server_ip, $queryport, $virtualserver_id)
    {
        $this->openConnection($server_ip, $queryport, $virtualserver_id);
    }

    /**
     * Proxy/Convenience Method for closeConnection()
     *
     * @see closeConnection()
     */
    public function close()
    {
        $this->closeConnection();
    }

    /**
     * Setter Method for the connection status
     *
     * @see $connection_status
     * @param boolean $connection_status
     */
    public function setConnectionActive($connection_status = true)
    {
        $this->connection_status = true;
    }

    /**
     * Getter Method for the connection status
     *
     * @see $connection_status
     * @return boolean
     */
    public function hasActiveConnection()
    {
        return $this->connection_status;
    }

    /**
     * Setter Method for the loggin status
     *
     * @see $login_status
     * @param boolean $login_status
     */
    public function setLoggedIn($login_status = true)
    {
        $this->login_status = $login_status;
    }

    /**
     * Getter Method for the connection status
     *
     * @see $login_status
     * @return boolean
     */
    public function isLoggedIn()
    {
        return $this->login_status;
    }

    /**
     * Setter Method for the server admin status
     *
     * @see $server_admin_status
     * @param boolean $server_admin_status
     */
    public function setServerAdmin($server_admin_status = false)
    {
        $this->server_admin_status = $login_status;
    }

    /**
     * Getter Method for the server admin status
     *
     * @see $server_admin_status
     * @return boolean
     */
    public function isServerAdmin()
    {
        return $this->server_admin_status;
    }

    /**
     * selectVirtualServer
     *
     * @param   integer $vserver_id Virtual Server ID
     * @return  boolean
     */
    public function selectVirtualServer($vserver_id = null)
    {
        if(isset($vserver_id))
        {
            $this->vserver_id = $vserver_id;
        }

        if( $this->executeWithoutFetch("use $this->vserver_id") == true )
        {
            $this->vserver_select_status = true;
        }
        else
        {
            $this->vserver_select_status = false;
        }

        return $this->vserver_select_status;
    }

    /**
     * Getter Method for the virtual server selection status
     *
     * @see $vserver_select_status
     * @return boolean
     */
    public function selectedVirtualServer()
    {
        return $this->vserver_select_status;
    }

    /**
     * Setter Method for the virtual server selection status
     *
     * @see $vserver_select_status
     * @return boolean
     */
    public function setVirtualServerStatus($vserver_select_status = false)
    {
        $this->vserver_select_status = $vserver_select_status;
    }

    /**
      * loginServerAdmin
      *
      * proxy method to login with additional server admin status setting
      *
      * @param  string  $username   username
      * @param  string  $password   password
      * @return boolean success
      */
    public function loginServerAdmin($username, $password)
    {
        if($this->selectedVirtualServer() == false)
        {
            return false;
        }

        if($this->login("login $username $password") == true)
        {
            $this->setServerAdmin(true);
            return true;
        }
        else
        {
            $this->setServerAdmin(false);
            return false;
        }
    }

    /**
     * ServerQueryCommand
     * Performs an direct ServerQueryCommand and returns the server response
     *
     * @param   string $servercommand
     * @return  mixed | serverquery response or boolean false
     */
    public function ServerQueryCommand($command)
    {
        if($this->hasActiveConnection())
        {
            # write command to server
            fputs($this->socket, $command."\n");

            $data = null;

            # read server response till a message string "msg=" was found
            do
            {
                $data .= fgets($this->socket);
            }
            while(strpos($data, 'error id=') === false or strpos($data, 'msg=') === false);

            #clansuite_xdebug::printR($data);

            # now check if the server response data contains an error
            if(strpos($data, 'error id=0') === false)
            {
                trigger_error('ServerQueryCommand Error: '.$this->replaceText($data));
                return false;
            }
            else
            {
                return $data;
            }
        }
    }

    /**
     * serverQuery
     *
     * @param  string  $command command
     * @return array data
     */
    public function serverQuery($command)
    {
        $fetchedUnfilteredData = $this->ServerQueryCommand($command);

        if($fetchedUnfilteredData !== false)
        {

            $fetchedUnfilteredData = str_replace('error id=0 msg=ok', '', $fetchedUnfilteredData);
            $fetchedUnfilteredData = str_replace('\/', '/', $fetchedUnfilteredData);
            $fetchedUnfilteredData = str_replace(array("\t", "\v", "\r", "\n", "\f"), '', $fetchedUnfilteredData);

            $data = array();

            $splitedKeys = explode(' ', $fetchedUnfilteredData);

            foreach($splitedKeys as $key)
            {
                $equalCount = substr_count($key, '=');
                if($equalCount > 1) {
                    $keyVals = explode('=', $key);
                    $val = $keyVals[1];
                    for($i=2; $i<=$equalCount; $i++) {
                        if(!empty($keyVals[$i])) {
                            $val .= '='.$keyVals[$i];
                        }else{
                            $val .= '=';
                        }
                    }
                    $data[$keyVals[0]] = str_replace('\s', ' ', str_replace('\p', '|', $val));
                }else{
                    $keyVals = explode('=', $key);
                    $data[$keyVals[0]] = str_replace('\s', ' ', str_replace('\p', '|', $keyVals[1]));
                }
            }
            return $data;
        }
        else
        {
            return false;
        }
    }

    /**
     * getExtendData: returns piped server data
     *
     * @param      string  $command    command
     * @return     multidimensional array data
     */
    public function extendedServerQuery($command)
    {
        $fetchedUnfilteredData = $this->ServerQueryCommand($command);

        if($fetchedUnfilteredData !== false) {

            $fetchedUnfilteredData = str_replace('error id=0 msg=ok', '', $fetchedUnfilteredData);
            $fetchedUnfilteredData = str_replace('\/', '/', $fetchedUnfilteredData);
            $fetchedUnfilteredData = str_replace(array("\t", "\v", "\r", "\n", "\f"), '', $fetchedUnfilteredData);

            $data = array();
            $pipeSplittedData = explode('|', $fetchedUnfilteredData);

            if(empty($fetchedUnfilteredData)) { return $data; }

            foreach($pipeSplittedData as $channelString) {
                $splittedKeys = explode(' ', $channelString);

                $keyArray = array();

                foreach($splittedKeys as $key) {
                    $equalCount = substr_count($key, '=');
                    if($equalCount > 1) {
                        $keyVal = explode('=', $key);
                        $val = $keyVal[1];
                        for($i=2; $i<=$equalCount; $i++) {
                            if(!empty($keyVal[$i])) {
                                $val .= '='.$keyVal[$i];
                            }else{
                                $val .= '=';
                            }
                        }
                        $keyArray[$keyVal[0]] = str_replace('\s', ' ', str_replace('\p', '|', $val));
                    }else{
                        $keyVal = explode('=', $key);
                        $keyArray[$keyVal[0]] = str_replace('\s', ' ', str_replace('\p', '|', $keyVal[1]));
                    }
                }

                $data[] = $keyArray;

            }
            return $data;
        }else{
            return false;
        }
    }

    /**
      * executeWithoutFetch: executes a command but dont fetches the response
      *
      * @param      string  $command    command
      * @return     boolean success
      */
    public function executeWithoutFetch($command)
    {
        fputs($this->socket, $command."\n");

        if(strpos(fgets($this->socket), 'id=0') !== false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }



    /**
     * @param $newClientNickName string New Client Nickname.
     */
    public function changeName($newClientNickName)
    {
        $newClientNickName = $this->replaceText($newClientNickName);

        return $this->executeWithoutFetch('clientupdate client_nickname='.$newClientNickName);
    }

    public function serverViewer()
    {
        $temp = $this->ServerQueryCommand('use 1');
        $temp .= $this->ServerQueryCommand("serverinfo");
        $temp .= $this->ServerQueryCommand('channellist');
        $temp .= $this->ServerQueryCommand('clientlist');

        if($temp == false)
        {
            echo "Server is down !";
        }
        else
        {
            $temp   = explode('error id=0 msg=ok', $temp);
            $temp1  = explode(' ', $temp[1]);

            $info['server']['name']          = str_replace('virtualserver_name=', '', $temp1[1]);
            $info['server']['maxclients']    = str_replace('virtualserver_maxclients=', '', $temp1[5]);
            $info['server']['clientsonline'] = (str_replace('virtualserver_clientsonline=', '', $temp1[7]))-(str_replace('virtualserver_queryclientsonline=', '', $temp1[38]));
            $info['server']['channels']      = (str_replace('virtualserver_channelsonline=', '', $temp1[8]));
            $info['server']['banner']        = (str_replace('virtualserver_hostbanner_gfx_url=', '', $temp1[21]));

            $temp2 = explode('cid=', $temp[2]);

            foreach ($temp2 as $var)
            {
                $buffer = explode(' ', $var);
                if(isset($buffer[5]))
                {
                    $info['channel'][$i]['id'] = $buffer[0];
                    $info['channel'][$i]['pid'] = str_replace('pid=', '', $buffer[1]);
                    $info['channel'][$i]['name'] = str_replace('channel_name=', '', $buffer[3]);
                    $i++;
                }
            }

            $i = 0;

            $temp3 = explode('clid=', $temp[3]);

            foreach ($temp3 as $var)
            {
                $buffer = explode(' ', $var);
                if(isset($buffer[4]))
                {
                    $buffer[4] = str_replace('|', '', $buffer[4]);

                    if(strpos($buffer[4], 'client_type=0') !== false)
                    {
                        $info['user'][$buffer[0]]['name']       = str_replace('client_nickname=', '', $buffer[3]);
                        $info['user'][$buffer[0]]['channel']    = str_replace('cid=', '', $buffer[1]);
                    }
                }
            }

            $u_cmd = '';
            $temp_a = '';
            #$this->ServerQueryCommand("use ".$this->vserver_id."");

            if(isset($info['user']))
            {
                foreach ($info['user'] as $u_key => $u_var)
                {
                    $temp_a .= $this->ServerQueryCommand("clientinfo clid=".$u_key."");
                }
                $temp_b = explode('msg=ok', $temp_a);
                $i=1;
                foreach ($info['user'] as $u_key => $u_var)
                {
                    $temp_a = explode(' ', $temp_b[0]);

                    foreach ($temp_a as $tu_key => $tu_var)
                    {
                        if(strpos($tu_var, 'client_channel_group_id') !== false)
                        {
                            $info['user'][$u_key]['cg']     = str_replace('client_channel_group_id=', '', $tu_var);
                        }
                        if(strpos($tu_var, 'client_away=') !== false)
                        {
                            $info['user'][$u_key]['away']   = str_replace('client_away=', '', $tu_var);
                        }
                        if(strpos($tu_var, 'client_input_muted') !== false)
                        {
                            $info['user'][$u_key]['mic']    = str_replace('client_input_muted=', '', $tu_var);
                        }
                        if(strpos($tu_var, 'client_output_muted') !== false)
                        {
                            $info['user'][$u_key]['head']   = str_replace('client_output_muted=', '', $tu_var);
                        }

                        if(strpos($tu_var, 'client_servergroups') !== false)
                        {
                            $temp_g = str_replace('client_servergroups=', '', $tu_var);
                            if(strpos($temp_g, ',')!== false)
                            {
                                $info['user'][$u_key]['sg'] = explode(',', $temp_g);
                            }
                            else
                            {
                                $info['user'][$u_key]['sg'][0] = $temp_g;
                            }
                        }
                    }
                    $i++;
                }
            }
        }
        return $info;
     }

    /**
     * replaceText
     *
     * @param  string $replace_text Text which should be imploded
     * @return string replaced Text
     */
    public function replaceText($text)
    {
        $chars        = array('\s','\p','\/');
        $replacements = array( ' ', '|', '/');

        $replaced_text = str_replace($chars, $replacements, $text);
        return $replaced_text;
    }

    /**
     * Magic Method Overloading via __call()
     *
     * This method takes care of loading the serverquery command files.
     *
     * This means that a currently non-existing methods or properties
     * of this class are dynamically "created".
     * Overloading methods are always in the "public" scope.
     *
     * @param string $name Name of Method or Property
     * @param mixed|array $arguments Single Argument or several Arguments
     */
    public function __call($method, $arguments)
    {
        # Because value of $name is case sensitive, its forced to be lowercase.
        $method = strtolower($method);

        # debug message for Method Overloading
        # makes it easier to see which method is called magically
        #echo 'DEBUG (Overloading): Calling object method "'.$method.'" '. implode(', ', $arguments). "\n";

        # construct the filename of the command
        $command_filename = dirname(__FILE__).DIRECTORY_SEPARATOR.'commands'.DIRECTORY_SEPARATOR.$method.'.php';

        # check if name is valid
        if(is_file($command_filename) and is_readable($command_filename))
        {
            # dynamically include the command
            include_once $command_filename;
            $classname = 'Teamspeak3_ServerQueryCommand_'.$method;
            $object = new $classname($this->server_ip, $this->queryport, $this->vserver_id);
            return call_user_func_array(array($object, $method), $arguments);

        }
        else
        {
            trigger_error('Teamspeak3 Server "Query Command File" not found: "'.$command_filename.'".');
        }
    }

    /**
     * destructor
     */
    function __destruct()
    {
        $this->logout();
        $this->closeConnection();
    }

    /**
     * =====================================
     *          Internal Methods
     * =====================================
     */

    /**
     * setHostMessageMode
     *
     * 1: display message in chatlog
     * 2: display message in modal dialog
     * 3: display message in modal dialog and close connection
     *
     * @param integer $hostmessagemode range 1-3
     */
    public function setHostMessageMode($hostmessagemode)
    {
        $this->hostmessagemode = $hostmessagemode;
    }

    /**
     * setCodec
     *
     * 0: speex narrowband      (mono, 16bit, 8kHz)
     * 1: speex wideband        (mono, 16bit, 16kHz)
     * 2: speex ultra-wideband  (mono, 16bit, 32kHz)
     * 3: celt mono             (mono, 16bit, 48kHz)
     *
     * @param integer $codec range 0-3
     */
    public function setCodec($codec)
    {
        $this->codec = $codec;
    }

    /**
     * setTextMessageTargetMode
     *
     * 1: target is a client
     * 2: target is a channel
     * 3: target is a virtual server
     *
     * @param integer $textmessagetargetmode range 1-3
     */
    public function setTextMessageTargetMode($textmessagetargetmode)
    {
        $this->textmessagetargetmode = $textmessagetargetmode;
    }

    /**
     * setLogLevel
     *
     * 1: everything that is really bad
     * 2: everything that might be bad
     * 3: output that might help find a problem
     * 4: informational output
     *
     * @param integer $loglevel range 1-4
     */
    public function setLogLevel($loglevel)
    {
        $this->loglevel = $loglevel;
    }

    /**
     * setReasonIdentifier
     *
     * 4: kick client from channel
     * 5: kick client from server
     *
     * @param integer $reasonidentifier 4,5
     */
    public function setReasonIdentifier($reasonidentifier)
    {
        $this->reasonidentifier = $reasonidentifier;
    }

    /**
     * setPermissionGroupDatabaseTypes
     *
     * 0: template group        (used for new virtual servers)
     * 1: regular group         (used for regular clients)
     * 2: global query group    (used for ServerQuery clients)
     *
     * @param integer $$permissiongroupdatabasetypes range 0-2
     */
    public function setPermissionGroupDatabaseTypes($permissiongroupdatabasetypes)
    {
        $this->permissiongroupdatabasetypes = $permissiongroupdatabasetypes;
    }

    /**
     * setPermissionGroupTypes
     *
     * 0: server group permission
     * 1: client specific permission
     * 2: channel specific permission
     * 3: channel group permission
     * 4: channel-client specific permission
     *
     * @param integer $permissiongrouptypes range 0-4
     */
    public function setPermissionGroupTypes($permissiongrouptypes)
    {
        $this->permissiongrouptypes = $permissiongrouptypes;
    }

    /**
     * setTokenType
     *
     * 0: server group token    (id1={groupID} id2=0)
     * 1: channel group token   (id1={groupID} id2={channelID})
     *
     * @param integer $tokentype 0,1
     */
    public function setTokenType($tokentype)
    {
        $this->tokentype = $tokentype;
    }
}
?>
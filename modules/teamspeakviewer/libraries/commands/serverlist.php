<?php
class Teamspeak3_ServerQueryCommand_serverlist extends Clansuite_Teamspeak3_ServerQueryInterface
{    
    /**
     * serverList: returns all server data from selected instance
     *
     * Output:<br>
     *
     * Array<br>
     * {<br>
     *  [virtualserver_id] => 2
     *  [virtualserver_port] => 9988
     *  [virtualserver_status] => online
     *  [virtualserver_clientsonline] => 0
     *  [virtualserver_queryclientsonline] => 0
     *  [virtualserver_maxclients] => 32
     *  [virtualserver_uptime] => 150
     *  [virtualserver_name] => Testserver
     *  [virtualserver_autostart] => 1
     *  [virtualserver_machine_id] => 
     * }
     *
     * @return multidimensional array serverlist
     */
	public function serverList()
	{
		return $this->extendedServerQuery("serverlist");
	}
}
?>
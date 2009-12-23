<?php
class Teamspeak3_ServerQueryCommand_serveridgetbyport extends Clansuite_Teamspeak3_ServerQueryInterface
{
    /**
     * getServerIdByPort
     *
     * @param	integer $port Virutalserver Port
     * @return integer returns the serverID which has the selected port
     */
    public function serveridgetbyport($port)
    {
		$ret = $this->getSimpleData("serveridgetbyport virtualserver_port=$port");

		if($ret !== false)
		{
			return $ret['server_id'];
		}
		else
		{
			return false;
		}
	}
?>
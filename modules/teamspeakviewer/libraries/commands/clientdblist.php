<?php
class Teamspeak3_ServerQueryCommand_clientdblist extends Clansuite_Teamspeak3_ServerQueryInterface
{
    /**
     * clientdblist
     *
     * array
     * (
     *    [cldbid]
     *    [client_unique_identifier]
     *    [client_nickname]
     *    [client_created]
     *    [client_lastconnected]
     *    [client_description]
     * )
     *
     * @return array returns all clients from db
     */
    public function clientdblist()
    {
        if($this->selectedVirtualServer() == false)
    	{
    	    return false;
    	}

        return $this->extendedServerQuery('clientdblist');
    }
}
?>
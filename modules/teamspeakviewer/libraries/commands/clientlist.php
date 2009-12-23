<?php
class Teamspeak3_ServerQueryCommand_clientinfo extends Clansuite_Teamspeak3_ServerQueryInterface
{
    /**
     * clientList
     *
     * Array
     * {
     *    [clid]
     *	  [cid]
     *	  [client_database_id]
     *	  [client_nickname]
     *	  [client_type]
     * )
     *
     * @return returns all online clients on the selected virtual server
     */
    public function clientlist()
    {
        if($this->hasActiveConnection())
        {
            return false;
        }

        return $this->extendedServerQuery('clientlist');
    }
}
?>
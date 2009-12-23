<?php
class Teamspeak3_ServerQueryCommand_servercreate extends Clansuite_Teamspeak3_ServerQueryInterface
{
    /**
     * serverStop: stops a server on the selected instance<br>
     *		Note: superAdmin login needed
     *
     * @param   integer $sid The serverID
     * @return  boolean success
     */
    public function serverStop($sid)
    {
        if($this->isServerAdmin() == false)
        {
            return false;
        }
    
        return $this->executeWithoutFetch("serverstop sid=$sid");
    }
}
?>
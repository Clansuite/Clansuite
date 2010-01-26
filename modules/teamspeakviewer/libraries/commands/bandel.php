<?php
class Teamspeak3_ServerQueryCommand_bandelete extends Clansuite_Teamspeak3_ServerQueryInterface
{   
    function bandelete($banID)
    {
        if($this->hasActiveConnection() and $this->isLoggedIn())
        {
            return false;
        }
    
        return $this->executeWithoutFetch('bandel banid='.$banID);
    }
}
?>
<?php
class Teamspeak3_ServerQueryCommand_bandeleteall extends Clansuite_Teamspeak3_ServerQueryInterface
{   
    public function bandeleteall()
    {
        if($this->hasActiveConnection() and $this->isLoggedIn())
        {
            return false;
        }
    
        return $this->executeWithoutFetch('bandelall');
    }
}
?>
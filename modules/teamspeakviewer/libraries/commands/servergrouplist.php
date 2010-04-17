<?php
class Teamspeak3_ServerQueryCommand_servergrouplist extends Clansuite_Teamspeak3_ServerQueryInterface
{  
    public function servergrouplist()
    {
        if($this->selectedVirtualServer() == false)
        {
            return false;
        }
        
        return $this->toArray($this->ServerQueryCommand('servergrouplist'));
    }
}
?>
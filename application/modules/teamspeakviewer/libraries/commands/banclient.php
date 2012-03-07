<?php
class Teamspeak3_ServerQueryCommand_banclient extends Clansuite_Teamspeak3_ServerQueryInterface
{   
    function banclient($clid, $time, $banreason = '')
    {
        if($this->hasActiveConnection() and $this->isLoggedIn())
        {
            return false;
        }
    
        if(!empty($banreason))
        {
            $msg = ' banreason='.$this->implodeText($banreason);
        }
        else
        {
            $msg = '';
        }
    
        fputs($this->socket, "banclient clid=$clid time=$time".$msg."\n");
    
        if(strpos(fgets($this->socket), 'banid=') !== false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>
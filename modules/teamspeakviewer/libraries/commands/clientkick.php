<?php
class Teamspeak3_ServerQueryCommand_clientkick extends Clansuite_Teamspeak3_ServerQueryInterface
{
    function clientkick($clid, $from, $kickmessage = '')
    {
        if($this->hasActiveConnection() and $this->isLoggedIn())
        {
            return false;
        }

        if($from == 5 or $from == 4)
        {
            if(!empty($kickmessage))
            {
                $message = ' reasonmsg='.$this->implodeText($kickmessage);
            }
            else
            {
                $message = '';
            }

            return $this->executeWithoutFetch("clientkick clid=$clid reasonid=".$from.$message);
        }
        else
        {
            return false;
        }
    }
}
?>
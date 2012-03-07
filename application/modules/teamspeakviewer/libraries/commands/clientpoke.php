<?php
function clientpoke($clid, $msg)
{
    if($this->hasActiveConnection() and $this->isLoggedIn())
    {
        return false;
    }

    return $this->executeWithoutFetch("clientpoke clid=$clid msg=".$this->implodeText($msg));
}
?>
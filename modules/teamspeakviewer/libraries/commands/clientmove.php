<?php
function clientmove($clid, $cid)
{
    if($this->hasActiveConnection() and $this->isLoggedIn())
    {
        return false;
    }

    return $this->executeWithoutFetch("clientmove clid=$clid cid=$cid");
}
?>
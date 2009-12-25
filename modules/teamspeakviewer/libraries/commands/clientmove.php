<?php
function clientmove($clid, $cid)
{
    if($this->hasActiveConnection() == false and $this->isLoggedIn() == false)
    {
        return false;
    }

    return $this->executeWithoutFetch("clientmove clid=$clid cid=$cid");
}
?>
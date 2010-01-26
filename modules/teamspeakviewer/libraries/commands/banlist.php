<?php
function banlist()
{
    return $this->toArray($this->ServerQueryCommand('banlist'));
}
?>
<?php
class Teamspeak3_ServerQueryCommand_sendmessage extends Clansuite_Teamspeak3_ServerQueryInterface
{   
    /**
     * sendMessage: sends a text message a specified target
     *
     * @param  integer $mode 3-1 {3: server| 2: channel|1: client}
     * @param  integer $target {serverID|channelID|clientID}
     * @param  string $msg The message
     * @return boolean success
     */
    function sendmessage($mode, $target, $msg)
    {
        return $this->executeWithoutFetch("sendtextmessage targetmode=$mode target=$target msg=".$this->replaceText($msg));
    }
}
?>
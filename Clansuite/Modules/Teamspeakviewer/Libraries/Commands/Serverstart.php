<?php
class Teamspeak3_ServerQueryCommand_Serverstart extends Clansuite_Teamspeak3_ServerQueryInterface
{
    /**
     * serverStart
     * starts a server on the selected instance
     *
     * @param  integer $sid The serverID
     * @return boolean success
     */
    public function serverStart($sid)
    {
        if ($this->isServerAdmin() == false) {
            return false;
        }

        return $this->executeWithoutFetch("serverstart sid=$sid");
    }
}

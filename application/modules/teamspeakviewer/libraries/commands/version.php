<?php
class Teamspeak3_ServerQueryCommand_version extends Clansuite_Teamspeak3_ServerQueryInterface
{   
    /**
     * version
     *
     * Array
     * (
     *   [version]
     *   [build]
     *   [platform]
     * )
     * 
     * @return Teamspeak3 Version Information
     */
    function version()
    {
        return $this->serverQuery('version');
    }
}
?>
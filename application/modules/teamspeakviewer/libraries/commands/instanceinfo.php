<?php
class Teamspeak3_ServerQueryCommand_instanceinfo extends Clansuite_Teamspeak3_ServerQueryInterface
{    
    /**
     * instanceinfo
     *
     * array
     * (
     *    [serverinstance_database_version]
     *    [serverinstance_filetransfer_port]
     *    [serverinstance_guest_serverquery_group]
     *    [serverinstance_template_serveradmin_group]
     *    [serverinstance_max_download_total_bandwidth]
     *    [serverinstance_max_upload_total_bandwidth]
     * )
     *
     * @return array returns all information about the teamspeak instance
     */
    public function instanceinfo()
    {
        return $this->serverQuery('instanceinfo');
    }
}
?>
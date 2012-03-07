<?php
class Teamspeak3_ServerQueryCommand_serverinfo extends Clansuite_Teamspeak3_ServerQueryInterface
{
    /**
     * serverinfo
     *
     * array
     * (
     *    [virtualserver_unique_identifier]
     *    [virtualserver_name]
     *    [virtualserver_welcomemessage]
     *    [virtualserver_platform]
     *    [virtualserver_version]
     *    [virtualserver_maxclients]
     *    [virtualserver_password]
     *    [virtualserver_clientsonline]
     *    [virtualserver_channelsonline]
     *    [virtualserver_created]
     *    [virtualserver_uptime]
     *    [virtualserver_hostmessage]
     *    [virtualserver_hostmessage_mode]
     *    [virtualserver_filebase]
     *    [virtualserver_default_server_group]
     *    [virtualserver_default_channel_group]
     *    [virtualserver_flag_password]
     *    [virtualserver_default_channel_admin_group]
     *    [virtualserver_max_download_total_bandwidth]
     *    [virtualserver_max_upload_total_bandwidth]
     *    [virtualserver_hostbanner_url]
     *    [virtualserver_hostbanner_gfx_url]
     *    [virtualserver_hostbanner_gfx_interval]
     *    [virtualserver_complain_autoban_count]
     *    [virtualserver_complain_autoban_time]
     *    [virtualserver_complain_remove_time]
     *    [virtualserver_min_clients_in_channel_before_forced_silence]
     *    [virtualserver_priority_speaker_dimm_modificator]
     *    [virtualserver_id]
     *    [virtualserver_antiflood_points_tick_reduce]
     *    [virtualserver_antiflood_points_needed_warning]
     *    [virtualserver_antiflood_points_needed_kick]
     *    [virtualserver_antiflood_points_needed_ban]
     *    [virtualserver_antiflood_ban_time]
     *    [virtualserver_client_connections]
     *    [virtualserver_query_client_connections]
     *    [virtualserver_hostbutton_tooltip]
     *    [virtualserver_hostbutton_url]
     *    [virtualserver_queryclientsonline]
     *    [virtualserver_download_quota]
     *    [virtualserver_upload_quota]
     *    [virtualserver_month_bytes_downloaded]
     *    [virtualserver_month_bytes_uploaded]
     *    [virtualserver_total_bytes_downloaded]
     *    [virtualserver_total_bytes_uploaded]
     *    [virtualserver_port]
     *    [virtualserver_autostart]
     *    [virtualserver_machine_id]
     *    [virtualserver_needed_identity_security_level]
     *    [virtualserver_status]
     *
     *    [connection_filetransfer_bandwidth_sent]
     *    [connection_filetransfer_bandwidth_received]
     *    [connection_packets_sent_total]
     *    [connection_bytes_sent_total]
     *    [connection_packets_received_total]
     *    [connection_bytes_received_total]
     *    [connection_bandwidth_sent_last_second_total]
     *    [connection_bandwidth_sent_last_minute_total]
     *    [connection_bandwidth_received_last_second_total]
     *    [connection_bandwidth_received_last_minute_total]
     * )
     *
     * @return array returns all information about the selected virtual server
     */
    public function serverinfo()
    {
        if($this->selectedVirtualServer() == false)
        {
            return false;
        }

        return $this->toArray($this->serverQuery("serverinfo"));
    }
}
?>
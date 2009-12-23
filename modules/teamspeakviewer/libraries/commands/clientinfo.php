<?php
class Teamspeak3_ServerQueryCommand_clientinfo extends Clansuite_Teamspeak3_ServerQueryInterface
{   
   /**
    * clientinfo
    * returns all information about a client
    *
    * Array<br>
    * {<br>
    *  [client_unique_identifier] => <br>
    *  [client_nickname] => <br>
    *  [client_version] => <br>
    *  [client_platform] => <br>
    *  [client_input_muted] => <br>
    *  [client_output_muted] => <br>
    *  [client_outputonly_muted] => <br>
    *  [client_input_hardware] => <br>
    *  [client_output_hardware] => <br>
    *  [client_default_channel] => <br>
    *  [client_meta_data] => <br>
    *  [client_is_recording] => 0<br>
    *  [client_login_name] => <br>
    *  [client_database_id] => <br>
    *  [client_channel_group_id] => <br>
    *  [client_servergroups] => <br>
    *  [client_created] => <br>
    *  [client_lastconnected] => <br>
    *  [client_totalconnections] => <br>
    *  [client_away] => <br>
    *  [client_away_message] =><br> 
    *  [client_type] => <br>
    *  [client_flag_avatar] => <br>
    *  [client_talk_power] => <br>
    *  [client_talk_request] => <br>
    *  [client_talk_request_msg] => <br>
    *  [client_description] => <br>
    *  [client_is_talker] => <br>
    *  [client_month_bytes_uploaded] => <br>
    *  [client_month_bytes_downloaded] => <br>
    *  [client_total_bytes_uploaded] => <br>
    *  [client_total_bytes_downloaded] => <br>
    *  [client_is_priority_speaker] => <br>
    *  [client_unread_messages] => <br>
    *  [client_nickname_phonetic] => <br>
    *  [client_needed_serverquery_view_power] => <br>
    *  [client_base64HashClientUID] => <br>
    *  [connection_filetransfer_bandwidth_sent] => <br>
    *  [connection_filetransfer_bandwidth_received] => <br>
    *  [connection_packets_sent_total] => <br>
    *  [connection_bytes_sent_total] => <br>
    *  [connection_packets_received_total] => <br>
    *  [connection_bytes_received_total] => <br>
    *  [connection_bandwidth_sent_last_second_total] =><br>
    *  [connection_bandwidth_sent_last_minute_total] => <br>
    *  [connection_bandwidth_received_last_second_total] => <br>
    *  [connection_bandwidth_received_last_minute_total] =><br>
    * }
    *
    * @return array clientinfo
    */
    public function clientinfo($cid)
    {
        return $this->serverQuery('clientinfo clid='.$cid);
    }
}
?>
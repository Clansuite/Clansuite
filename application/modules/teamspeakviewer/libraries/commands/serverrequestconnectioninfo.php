<?php
class Teamspeak3_ServerQueryCommand_serverrequestconnectioninfo extends Clansuite_Teamspeak3_ServerQueryInterface
{      
     /**
      * serverRequestConnectionInfo
      *
      * Array
      * (
      *   [connection_filetransfer_bandwidth_sent]
      *   [connection_filetransfer_bandwidth_received]
      *   [connection_packets_sent_total]
      *   [connection_bytes_sent_total]
      *   [connection_packets_received_total]
      *   [connection_bytes_received_total]
      *   [connection_bandwidth_sent_last_second_total]
      *   [connection_bandwidth_sent_last_minute_total]
      *   [connection_bandwidth_received_last_second_total]
      *   [connection_bandwidth_received_last_minute_total]
      *   [connection_connected_time]
      * )
      *
      * @return array detailed connection information of the selected virtual server
      */
    public function serverrequestconnectioninfo()
    {
        if($this->selectedVirtualServer() == false)
        { 
            return false;
        }
        
        return $this->serverQuery("serverrequestconnectioninfo");
    }  
}
?>
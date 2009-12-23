<?php
class Teamspeak3_ServerQueryCommand_hostinfo extends Clansuite_Teamspeak3_ServerQueryInterface
{   
     /**
      * hostinfo
      * returns all information about the connected host
      *
      * Array<br>
      *   {<br>
      *    [instance_uptime] => <br>
      *    [host_timestamp_utc] => <br>
      *    [virtualservers_running_total] => <br>
      *    [connection_filetransfer_bandwidth_sent] => <br>
      *    [connection_filetransfer_bandwidth_received] => <br>
      *    [connection_packets_sent_total] => <br>
      *    [connection_bytes_sent_total] => <br>
      *    [connection_packets_received_total] => <br>
      *    [connection_bytes_received_total] => <br>
      *    [connection_bandwidth_sent_last_second_total] => <br>
      *    [connection_bandwidth_sent_last_minute_total] => <br>
      *    [connection_bandwidth_received_last_second_total] => <br>
      *    [connection_bandwidth_received_last_minute_total] => <br>
      * }
      *
      * @return     array host information
      */
    public function hostinfo()
    {
        return $this->serverQuery('hostinfo');
    }
}
?>
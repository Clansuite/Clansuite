<?php
class Teamspeak3_ServerQueryCommand_channellist extends Clansuite_Teamspeak3_ServerQueryInterface
{
    /**
      * channelList
      *
      * Array
      * (
      *    [cid]
      *    [pid]
      *    [channel_order]
      *    [channel_name]
      *    [total_clients]
      *    [channel_needed_subscribe_power]
      * )
      *
      * @return array returns all channels on the selected server
      */
    public function channellist()
    {  
        if($this->selectedVirtualServer() == false)
        {
            return false;
        }
        
        return $this->toArray($this->ServerQueryCommand('channellist'));
    }
}
?>
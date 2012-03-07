<?php
class Teamspeak3_ServerQueryCommand_servercreate extends Clansuite_Teamspeak3_ServerQueryInterface
{
  /**
   * serverCreate: creates a server on the selected instance
   *        Note: superAdmin login needed
   *
   * @param        string $server_name Name of the virtual Server
   * @param        integer $maxslots Slots
   * @param        integer $port Virutalserver Port
   * @return     string token
   */
  public function servercreate($server_name, $maxslots, $port)
  {
        if($this->isServerAdmin() == false)
        { 
            return false;
        }
        
        $response = $this->serverQuery("servercreate virtualserver_name=".$this->replaceText($server_name)." virtualserver_maxclients=$maxslots virtualserver_port=$port");
        
        if($response !== false)
        {
            return $response['token'];
        }
        else
        {
            return false;
        }
    }
}
?>
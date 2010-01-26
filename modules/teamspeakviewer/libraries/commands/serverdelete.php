<?php
class Teamspeak3_ServerQueryCommand_servercreate extends Clansuite_Teamspeak3_ServerQueryInterface
{
    /**
     * serverDelete: deletes a server on the selected instance<br>
     *		Note: superAdmin login needed
     *
     * @author     Par0noid Solutions
     * @access		public
     * @param		integer $sid The serverID
     * @return     boolean success
     */
	public function serverDelete($sid)
	{
		if($this->isServerAdmin() == false)
		{
		    return false;
		}
		
		$this->serverStop($sid);
		
		return $this->executeWithoutFetch("serverdelete sid=$sid");
	}  
}
?>
<?php
class Teamspeak3_ServerQueryCommand_logout extends Clansuite_Teamspeak3_ServerQueryInterface
{
    /**
      * logout
      * performs logout at server and resets the login status
      *
      * @return     boolean success
      */
	public function logout()
	{
		if($this->isLoggedIn() == false)
		{
		    return false;
		}

		if($this->executeWithoutFetch("logout") == true)
		{
			$this->setLoggedIn(false);
			return true;
		}
		else
		{
			$this->setLoggedIn(true);
			return false;
		}
	}
}
?>
<?php
class Teamspeak3_ServerQueryCommand_login extends Clansuite_Teamspeak3_ServerQueryInterface
{   
    /**
     * login
     *
     * This method performs an login with username and password.
     * On successfull login, one gets access (if permitted) to additional server query commands.
     *
     * @param  string $username username
     * @param  string $password password
     * @return boolean
     */
    function login($username, $password)
    {
        if( $this->executeWithoutFetch("login $username $password") == true )
        {
            $this->setLoggedIn();
        }
        else
        {
            $this->setLoggedIn(false);
        }
    }
}
?>
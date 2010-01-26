<?php
class Teamspeak3_ServerQueryCommand_whoami extends Clansuite_Teamspeak3_ServerQueryInterface
{
    /**
      * whoami
      * returns informations about you
      *
      * Array<br>
      * {<br>
      *  [virtualserver_status] => <br>
      *  [virtualserver_id] => <br>
      *  [virtualserver_unique_identifier] => <br>
      *  [client_channel_id] => <br>
      *  [client_nickname] => <br>
      *  [client_database_id] => <br>
      *  [client_login_name] => <br>
      *  [client_unique_identifier] => <br>
      * }
      *
      * @return     array clientinformation
      */
    public function whoami()
    {
        return $this->serverQuery('whoami');
    }
}
?>
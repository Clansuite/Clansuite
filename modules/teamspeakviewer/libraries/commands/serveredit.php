<?php
class Teamspeak3_ServerQueryCommand_serveredit extends Clansuite_Teamspeak3_ServerQueryInterface
{
   /**
    * serverEdit
    * edits serversettings on selected virtualserver<br>
    *		Note: login needed / for some settings superAdmin login
    *
    *	Howto edit:<br>
    *	<br>
    *	$newSettings = array();<br>
    *	<br>
    * $newSettings[] = array('setting', 'value');<br>
    * $newSettings[] = array('setting', 'value');<br>
    *	<br>
    * serverEdit($newSettings);<br>
    * <br>
    * server must be selected serverSelect();<br>
    * <br>
    * <br>
    * <br>
    * Possible:<br>
    * <br>
    * name<br>
    * welcomemessage<br>
    * maxclients<br>
    * password<br>
    * default_servergroup<br>
    * default_channelgroup<br>
    * default_channeladmingroup<br>
    * ft_settings<br>
    * ft_quotas<br>
    * channel_forced_silence<br>
    * complain<br>
    * antiflood<br>
    * hostmessage<br>
    * hostbanner<br>
    * hostbutton<br>
    * port<br>
    * autostart<br>
    * needed_identity_security_level
    *
    * @param    multiarray $newSettings Server setting
    * @return   boolean
    */
    public function serveredit($newSettings)
    {
    	if($this->selectedVirtualServer() == false)
    	{
    	    return false;
    	}
    	
    	$settingsString = '';
    	
    	foreach($newSettings as $setting)
    	{
    		$settingsString .= ' virtualserver_'.$setting[0].'='.$this->replaceText($setting[1]);
    	}
    	
    	return $this->executeWithoutFetch("serveredit".$settingsString);
    }
}
?>
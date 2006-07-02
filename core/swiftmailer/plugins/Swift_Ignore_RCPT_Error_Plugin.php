<?php

class Swift_Ignore_RCPT_Error_Plugin implements Swift_IPlugin
{
	public $pluginName = 'ignore_rcpt_error';
	private $swiftInstance;
	
	public function loadBaseObject(&Object)
	{
		$this->swiftInstance =& $object;
	}
	
	public function onFail()
	{
		if ($this->swiftInstance->responseCode == 550
		&& $this->swiftInstance->commandKeyword == 'rcpt')
		{
			$this->swiftInstance->failed = false;
		}
	}
}

?>
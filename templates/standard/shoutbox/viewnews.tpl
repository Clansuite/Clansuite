<h2>Shoutbox</h2>

{* Soll das Formular angezeigt werden *}
{if $showForm === true}
	<form action="{$request}" method="post">
		<input 	type="text" name="name" 
				value="{$field_value_name}" 
				onclick="if(this.value == '{$field_value_name}') this.value = ''" 
				onblur="if(this.value == '') this.value = '{$field_value_name}'" /><br />
		<input 	type="text" name="mail" 
				value="{$field_value_mail}" 
				onclick="if(this.value == '{$field_value_mail}') this.value = ''" 
				onblur="if(this.value == '') this.value = '{$field_value_mail}'" /><br />
		<input 	type="text" name="msg" 
				value="{$field_value_msg}" 
				onclick="if(this.value == '{$field_value_msg}') this.value = ''" 
				onblur="if(this.value == '') this.value = '{$field_value_msg}'" /><br />	
			
		<input type="submit" name="sent" value="{$save_entry}" />
	</form>
{/if}

{* Ist ein Fehler aufgetreten *}
{if $isError === true}
	{include file='service/showErrorList.tpl'}
{/if}

{* Wurde der Eintrag gespeichert *}
{if $isSaved === true}
	{$save_msg}
{/if}
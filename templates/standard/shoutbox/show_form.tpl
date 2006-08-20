{* Soll das Formular angezeigt werden *}
{if $show_form === true}
	<div id="request_return">
	
	</div>
	<br />
	
	<form action="{$request}" method="post" onsubmit="return sendAjaxRequest('name,mail,msg', '{$request}&check=true', 'request_return');">
		<input 	id="name" type="text" name="name" 
				value="{$field_value_name}" 
				onclick="if(this.value == '{$field_value_name}') this.value = ''" 
				onblur="if(this.value == '') this.value = '{$field_value_name}'" /><br />
		<input 	id="mail" type="text" name="mail" 
				value="{$field_value_mail}" 
				onclick="if(this.value == '{$field_value_mail}') this.value = ''" 
				onblur="if(this.value == '') this.value = '{$field_value_mail}'" /><br />
		<input 	id="msg" type="text" name="msg" 
				value="{$field_value_msg}" 
				onclick="if(this.value == '{$field_value_msg}') this.value = ''" 
				onblur="if(this.value == '') this.value = '{$field_value_msg}'" /><br />	
			
		{* Falls Js aktiviert ist, wird der Submit Button automaisch ausgeblendet! *}
		<input id="shoutbox_submit" type="submit" name="sent" value="{$save_entry}" />
	</form>
{/if}

{* Ist ein Fehler aufgetreten *}
{if $is_error === true}
	{include file='service/showErrorList.tpl'}
{/if}

{* Wurde der Eintrag gespeichert *}
{if $is_saved === true}
	{$save_msg}
{else}
	{literal}
		<script language="javascript">
		// Baut eine Connection auf
		function getXMLRequester( )
		{
			var xmlHttp = false;
				   
			// try to create a new instance of the xmlhttprequest object       
			try
			{
				// Internet Explorer
				if( window.ActiveXObject )
				{
					for( var i = 5; i; i-- )
					{
						try
						{
							// loading of a newer version of msxml dll (msxml3 - msxml5) failed
							// use fallback solution
							// old style msxml version independent, deprecated
							if( i == 2 )
							{
								xmlHttp = new ActiveXObject( "Microsoft.XMLHTTP" );   
							}
							// try to use the latest msxml dll
							else
							{
							   
								xmlHttp = new ActiveXObject( "Msxml2.XMLHTTP." + i + ".0" );
							}
							break;
						}
						catch( excNotLoadable )
						{                       
							xmlHttp = false;
						}
					}
				}
				// Mozilla, Opera und Safari
				else if( window.XMLHttpRequest )
				{
					xmlHttp = new XMLHttpRequest();
				}
			}
			// loading of xmlhttp object failed
			catch( excNotLoadable )
			{
				xmlHttp = false;
			}
			return xmlHttp ;
		}

		var id;	// In dem Div mit dieser ID wird die Ausgabe des Ajax Requests gespeichert
		
		// @param	form_field_ids					Per Komma getrennte ID's von Feldern, die an die Date geschickt werden sollen
		// @param	file							An welche Datei der Request gesendet werden soll
		// @param	display_returning_output_id		Bei einem Fehler gibt die Datei eine Fehlerliste zurí¤« (Fehler von %%% getrennt).
		//											Ansonsten gibt die Datei einen leeren Output zurí¤«
		function sendAjaxRequest(form_field_ids, file, display_returning_output_id)
		{
			con = getXMLRequester();
		    con.open('POST', file, true);
			id = display_returning_output_id;
			ids = form_field_ids.split(',');
			param = '';	// Parameter, die an die Ajax Datei í£¥rmittelt werden
			
			// build param
			for(i = 0; i < ids.length; i++) 
			{
				param += (param == '') ? '' : '&';
				param += document.getElementById(ids[ i ]).name + '=' + document.getElementById(ids[ i ]).value;
			}
			
			//debug
			//window.alert('PARAMS: ' + param);
			
			con.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    con.setRequestHeader("Content-length", param.length);
			con.onreadystatechange = handleResponse;
			con.send(param);
			con.close;
			
		    return false;
		}

		// Response des Ajax zugriffs erhalten
		function handleResponse() {
			// Checke, ob der Zugriff erfolgreich war
			if (con.readyState == 4) {
				var response = con.responseText;
				//alert('RESPONSE: ' + response);
				
				if(response != '') {
					// Error ...
					var errors = response.split('%%%');
					
					response_text = '<span class="shoutbox_error">Several Errors occured while saving:</span>';	// muss noch Ã¼bersetzt werden.
					for(i = 0; i < errors.length; i++)
					{
						response_text += '<br />- ' + errors[ i ];
					}
				}
				else {
					response_text = '<span class="shoutbox_success">Your entry was saved successfuly!</span>';	// muss noch Ã¼bersetzt werden.
				}
				
				document.getElementById(id).innerHTML = response_text;
											
				return true;
			}
										   
			return false;
		}
		</script>
	{/literal}
{/if}
<div id="loading" style="width:100%;height:20px;background:lightblue;text-align:center;display:none;position:absolute;top:0;left:0;">
Loading...
</div>
{if $show_form === true}
<h3>{translate}Shoutbox{/translate}</h3>
<form action="{$request}" method="post" onsubmit="return sendAjaxRequest('name,mail,msg', 'index.php?mod=shoutbox&amp;action=check&amp;check=true', 'request_return');">
	<fieldset>
		<dl>
			<dt><label for="shout_name">{translate}Name{/translate}:</label></dt>
			<dd><input class="input_text" id="shout_name" type="text" name="name" value="{$smarty.session.user.nick|escape:"html"}" /></dd>
		</dl>
		<dl>
			<dt><label for="shout_mail">{translate}Mail{/translate}:</label></dt>
			<dd><input class="input_text" id="shout_mail" type="text" name="mail" value="{$smarty.session.user.email|escape:"html"}" /></dd>
		</dl>
		<dl>
			<dt><label for="shout_msg">{translate}Message{/translate}:</label></dt>
			<dd><textarea class="input_textarea" id="shout_msg" name="msg" cols="17" rows="3"></textarea></dd>
		</dl>
	</fieldset>
	<div class="form_bottom">
		<input class="ButtonGreen" id="shoutbox_submit" type="submit" name="sent" value="{$save_entry}" />
	</div>
</form>
<div id="request_return" style="text-align:center">

</div>
<br />
<div id="entries_box">
	{$entries_box}
</div>
{$entries}
{/if}

{* Ist ein Fehler aufgetreten *}
{if $is_error === true && $show_error === true}
	{include file='service/showErrorList.tpl'}
{/if}

{* Wurde der Eintrag gespeichert *}
{if $is_saved === true}
	{$save_entry}
{else}
	{literal}
<script type="text/javascript">
//<![CDATA[
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
// @param	display_returning_output_id		Bei einem Fehler gibt die Datei eine Fehlerliste zurück (Fehler von %%% getrennt).
//											Ansonsten gibt die Datei einen leeren Output zurück
function sendAjaxRequest(form_field_ids, file, display_returning_output_id)
{
	con = getXMLRequester();
	con.open('POST', file, true);
	id = display_returning_output_id;
	ids = form_field_ids.split(',');
	param = '';	// Parameter, die an die Ajax Datei ürmittelt werden

	// build param
	for(i = 0; i < ids.length; i++)
	{
		param += (param == '') ? '' : '&';
		param += document.getElementById(ids[ i ]).name + '=' + escape(encodeURIComponent(document.getElementById(ids[ i ]).value));
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
function handleResponse()
{
	// Checke, ob der Zugriff erfolgreich war
	if (con.readyState == 4) {
		var response = con.responseText;
		//alert('RESPONSE: ' + response);

		if(response != '')
		{
			if(response.search(/---error---/) != -1)
			{
				// Error ...
				var errors = response.split('---error---');
				response_text = '<strong>{/literal}{translate}Error:{/translate}{literal}</strong>';
				for(i = 0; i < errors.length; i++)
				{
					if ( errors[ i ] != '' )
					{
						response_text += '<br />- ' + errors[ i ];
					}
				}
				document.getElementById('request_return').innerHTML = response_text;
			}
			else
			{
				response_text = '<span class="shoutbox_success">{/literal}{translate}...saved...{/translate}{literal}</span>';
				document.getElementById('entries_box').innerHTML = response;
				document.getElementById('msg').value='';
				document.getElementById(id).innerHTML = response_text;
			}
		}
		else
		{
			response_text = '<span class="shoutbox_success">{/literal}{translate}Database Error!{/translate}{literal}</span>';
			document.getElementById(id).innerHTML = response_text;
		}

		document.getElementById('loading').style.display = 'none';
		return true;
	}
	else
	{
		document.getElementById('loading').style.display = 'block';
	}

	return false;
}
//]]>
</script>
	{/literal}
{/if}
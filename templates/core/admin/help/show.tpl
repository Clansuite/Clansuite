{doc_raw}
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/clip.js"></script>
    
    {literal}
        <script type="text/javascript">

		// Baut eine Connection auf
		function getXMLRequester ()
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
		// @param	display_returning_output_id		Bei einem Fehler gibt die Datei eine Fehlerliste zur�ck (Fehler von %%% getrennt).
		//											Ansonsten gibt die Datei einen leeren Output zur�ck
		function sendAjaxHelpRequest(type, param, file)
		{
			con = getXMLRequester();
		    con.open('POST', file, true);
            
            param = 'save_mod='+escape(encodeURIComponent(document.getElementById('save_mod').value));
            param += '&save_sub='+escape(encodeURIComponent(document.getElementById('save_sub').value));
            param += '&save_action='+escape(encodeURIComponent(document.getElementById('save_action').value));
            
            if ( type == 'helptext' )
            {
                param += '&helptext='+escape(encodeURIComponent(document.getElementById('helptext').value));
            }
            
            if ( type == 'related_links' )
            {
                param += '&related_links='+escape(encodeURIComponent(document.getElementById('related_links').value));
            }

			con.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    con.setRequestHeader("Content-length", param.length);
            
            if ( type == 'helptext' )
            {
                con.onreadystatechange = handleHelptextResponse;
            }
            
            if ( type == 'related_links' )
            {
                con.onreadystatechange = handleLinksResponse;
            }            

			con.send(param);
			con.close;
			
		    return false;
		}
            
        function handleLinksResponse()
        {
            // Checke, ob der Zugriff erfolgreich war
			if (con.readyState == 4)
            {
				var response = con.responseText;
        
                document.getElementById('related_links_container').innerHTML = response;
                document.getElementById('loading').style.display = 'none';
                
                return true;
			}
            else
            {
                document.getElementById('loading').style.display = 'block';
            }									   
			return false;
		}
        
        function handleHelptextResponse()
        {
            // Checke, ob der Zugriff erfolgreich war
			if (con.readyState == 4)
            {
				var response = con.responseText;
        
                document.getElementById('helptext_container').innerHTML = response;
                document.getElementById('loading').style.display = 'none';
                
                return true;
			}
            else
            {
                document.getElementById('loading').style.display = 'block';
            }									   
			return false;
		}
    </script>
    {/literal}
{/doc_raw}
<div id="loading" style="display: none; position: absolute; top: 0px; left: 0px; width: 100%; height: 20px; text-align: center; background-color: lightblue;">
Loading...
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="border-bottom: 1px solid #ACA899; padding: 5px">
            <b>&raquo; {$smarty.request.mod} {if $smarty.request.sub!=''}&raquo; {$smarty.request.sub} {/if}&raquo; {$smarty.request.main_action}</b>
            <input type="hidden" id="save_mod" name="save_mod" value="{$smarty.request.mod}" />
            <input type="hidden" id="save_sub" name="save_sub" value="{$smarty.request.sub}" />
            <input type="hidden" id="save_action" name="save_action" value="{$smarty.request.main_action}" />
        </td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #ACA899; border-top: 1px solid #FFFFFF; padding: 15px">
            <div id="helptext_container">
                {$info.helptext}
            </div>
            {if $info.helptext!=''}

            {else}
                {translate}There is no helptext assigned.{/translate}<br />
                <a href="javascript:void(0)" onClick="clip_id('help_add');">{translate}Add a helptext{/translate}</a>
            {/if}
            <div style="display: none;padding: 10px; text-align: center;" id="help_add">
                <textarea style=" width: 100%; height: 200px;" class="input_textarea" name="info[helptext]" id="helptext"></textarea><br />
                <input type="button" class="ButtonGreen" value="{translate}Add help{/translate}" onClick="return sendAjaxRequest('related_links', '', 'index.php?mod=admin&sub=help&action=save_helptext')" />
            </div>
        </td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #ACA899; border-top: 1px solid #FFFFFF; padding: 5px">
            <b>&raquo; {translate}Related Links{/translate}</b>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid #FFFFFF; padding: 15px">
            <div id="related_links_container">
                {$info.related_links}
            </div>

        </td>
    </tr>
</table>
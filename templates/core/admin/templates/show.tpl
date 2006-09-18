{doc_raw}
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/adminmenu/DynamicTree.css" />

    {literal}
        <script type="text/javascript" src="{$www_core_tpl_root}/javascript/ajax.js"></script>
        <style type="text/css">
            body { background: #F1EFE2; }
            body, table { font-family: georgia, sans-serif; font-size: 11px; }
            form { margin: 0; }
            input[readonly] { border: 1px solid #7F9DB9; background: #ffffff; }
            a { color: #0000ee; text-decoration: none; }
            a:hover { color: #0000ee; text-decoration: underline; }
            p { margin-top: 0; margin-bottom: 1em; }
            #tree-plugin, #tree-plugin-button-import-html { display: none; }
            #tree-plugin-textarea { white-space: nowrap; }
        </style>
        <script type="text/javascript">

        function node_click(id)
        {
            if( document.getElementById('section-' + id).style.display == 'none' )
            {
                document.getElementById('section-' + id).style.display = 'block';
                document.getElementById('node-' + id).src = '{/literal}{$www_core_tpl_root}{literal}/admin/adminmenu/images/tree-node-open.gif';
            }
            else
            {
                document.getElementById('section-' + id).style.display = 'none';
                document.getElementById('node-' + id).src = '{/literal}{$www_core_tpl_root}{literal}/admin/adminmenu/images/tree-node.gif';
            }
        }

		function sendAjaxRequest(type, param, file)
		{
			con = getXMLRequester();
		    con.open('POST', file, true);
            
            if( type == 'get' )
            {
			    document.getElementById('tpl_path').innerHTML = param;
                param = 'tpl_path='+param;
            }
            
            if( type == 'save' )
            {
                param = 'tpl_path='+escape(encodeURIComponent(document.getElementById('tpl_path').innerHTML))+'&content='+escape(encodeURIComponent(document.getElementById('ajax_textarea').value));
            }

			con.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		    con.setRequestHeader("Content-length", param.length);
			if ( type == 'get' )
            {
                con.onreadystatechange = handleGetResponse;
            }
			if ( type == 'save' )
            {
                con.onreadystatechange = handleSaveResponse;
            }
			con.send(param);
			con.close;
			
		    return false;
		}

		// Response des Ajax zugriffs erhalten
		function handleGetResponse()
        {
            // Checke, ob der Zugriff erfolgreich war
			if (con.readyState == 4)
            {
				var response = con.responseText;
        
                //document.getElementById('ajax_textarea').value = response;

                document.getElementById('loading').style.display = 'none';
                document.getElementById('ajax_textarea').value = response;
                return true;
			}
            else
            {
                document.getElementById('loading').style.display = 'block';
                document.getElementById('ajax_confirm').innerHTML = '';
            }
            return false;
        }
            
        function handleSaveResponse()
        {
            // Checke, ob der Zugriff erfolgreich war
			if (con.readyState == 4)
            {
				var response = con.responseText;
        
                //document.getElementById('ajax_textarea').value = response;

                document.getElementById('loading').style.display = 'none';
                document.getElementById('ajax_confirm').innerHTML = '...saved...';
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

<table cellspacing="0" cellpadding="10" style="margin-top: 1em;">
<tr>
    <td valign="top" width="250">
        <div class="DynamicTree">
            <div class="wrap1">
                <div class="top">{translate}Template Folder{/translate}</div>
                <div class="wrap2" id="tree">
                    {$folder_tree}
                </div>
            </div>
        </div>
    </td>
    <td valign="top" width="100%">
        <b>{translate}Current Template:{/translate}</b>&nbsp;<div id="tpl_path">&nbsp;</div>
        <textarea class="input_textarea" wrap="off" rows="30" style="width: 100%;" id="ajax_textarea"></textarea>
    </td>
</tr>
<tr>
<td align="center" colspan="2">
    <input class="ButtonGrey" onclick="return sendAjaxRequest('save', '', 'index.php?mod=admin&sub=templates&action=ajax_save')" type="submit" value="{translate}Update{/translate}" /><br />
    <div id="ajax_confirm" align="center"></div>
</td>
</tr>
</table>


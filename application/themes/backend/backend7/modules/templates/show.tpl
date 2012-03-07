{move_to target="pre_head_close"}
<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}admin/adminmenu/DynamicTree.css" />
{* Prototype + Scriptaculous + Smarty_Ajax *}
<script type="text/javascript" src="{$www_root_themes_core}javascript/prototype/prototype.js" ></script>
<script type="text/javascript" src="{$www_root_themes_core}javascript/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}javascript/smarty_ajax.js"></script>

    
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
                document.getElementById('node-' + id).src = '{$www_root_themes_core}admin/adminmenu/images/tree-node-open.gif';
            }
            else
            {
                document.getElementById('section-' + id).style.display = 'none';
                document.getElementById('node-' + id).src = '{$www_root_themes_core}admin/adminmenu/images/tree-node.gif';
            }
        }   
       
        </script>
        
        <script type="text/javascript">
        	function getTemplateFile(filename){
        	    var url = 'index.php?mod=controlcenter&sub=templates&action=ajax_get';
        		//alert('File:' + filename.id);
        		var getAjax = new Ajax.Updater(
        				'template_textarea', url, 
        				{method: 'post', parameters: 'filename=' + filename.id})
        	    $('filename').innerHTML = filename.id;
        	    //alert('Filename:' + $('filename').innerHTML);
        	}   
        	
        	function saveTemplateFile(){
        	    var url = 'index.php?mod=controlcenter&sub=templates&action=ajax_save';
        		//alert('File:' + $('filename').innerHTML);
        		//alert('Content:' + $('template_textarea').value );
        		var saveAjax = new Ajax.Updater(
        				'ajax-success', url, 
        				{method: 'post', parameters: 'filename=' + $('filename').innerHTML + '&content=' + $('template_textarea').value });
        	}  
        </script>
    
    
{/move_to}


<table cellspacing="0" cellpadding="10" style="margin-top: 1em;">
<tr>
    <td valign="top" width="250">
        <div class="DynamicTree">
            <div class="wrap1">
                <div class="top">{t}Template Folder{/t}</div>
                <div class="wrap2" id="tree">
                    {$folder_tree}
                </div>
            </div>
        </div>
    </td>
    <td valign="top" width="100%">
        <strong>{t}Current Template:{/t}</strong>&nbsp;
        <div id="filename">&nbsp; Select Filename ... </div>
        <textarea class="input_textarea" wrap="off" rows="30" style="width: 100%;" id="template_textarea"></textarea>
    </td>
</tr>
<tr>
<td align="center" colspan="2">
    <input class="ButtonGrey" value="{t}Update{/t}"
    onclick="saveTemplateFile()" />
    <br />
    <div id="ajax-success"></div>
</td>
</tr>
</table>
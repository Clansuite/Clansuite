{$chmod_tpl}
{doc_raw}
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/adminmenu/DynamicTree.css" />
    {literal}
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
    function checker(checkboxen, caller)
    {
        if ( !this.loaded )
        {
            this.loaded = new Array;
        }
        
        checkbox = checkboxen.split(",");

        for( x=0; x<checkbox.length; x++ )
        {
            if( document.getElementById(caller).checked )
            {
                document.getElementById(checkbox[x]).checked=1;
                
                if ( this.loaded[checkbox[x]] >= 1 )
                {
                    this.loaded[checkbox[x]]++;
                }
                else
                {
                    this.loaded[checkbox[x]] = 1;
                }
            }
            else
            {
                //document.write(this.loaded[checkbox[x]]);
                this.loaded[checkbox[x]]--;
                if( this.loaded[checkbox[x]] == 0 )
                    document.getElementById(checkbox[x]).checked=0;
            }
        }
    }
        
    function clip_menu(name)
    {
        if(document.getElementById(name).style.display == 'none')
        {
            document.getElementById(name).style.display = "block";
        }
        else
        {
            document.getElementById(name).style.display = "none";
        }
    }
    </script>
    {/literal}
{/doc_raw}
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    
    <td class="td_header" width="120px">
    {translate}Title{/translate}
    </td>
    
    <td class="td_header" width="65%">
    {translate}Information{/translate}
    </td>
    
    <td class="td_header" width="15%" align="center">
    {translate}Option{/translate}
    </td>

</tr>

{foreach key=schluessel item=wert from=$content.whitelisted}
<form action="index.php?mod=admin&sub=modules&action=export" method="POST" name="{$wert.name}">
<tr>

    <td class="cell1" align="center">
    <b>{$wert.title}</b><br />
    <img width="100px" height="100px" src="{$www_core_tpl_root}/images/{$wert.image_name}">
    </td>
    
    <td class="cell2">
    <table cellpadding="2" cellspacing="2" border="0">
        <tr><td><b>{translate}Description:{/translate}</b></td><td>{$wert.description}</td></tr>
        <tr><td><b>{translate}Foldername:{/translate}</b></td><td>{$wert.folder_name}</td></tr>
        <tr><td><b>{translate}Classname:{/translate}</b></td><td>{$wert.class_name}</td></tr>
        <tr><td><b>{translate}Filename:{/translate}</b></td><td>{$wert.file_name}</td></tr>
        <tr><td><b>{translate}URL:{/translate}</b></td><td><a href="index.php?mod={$wert.name}">index.php?mod={$wert.name}</a></td></tr>
    </table>
    </td>
    
    <td class="cell1" align="center">
        <div id="menucontainer_{$wert.name}" style="display: none;">
            <table cellspacing="0" cellpadding="10" style="margin-top: 1em;">
            <tr>
                <td valign="top">
                    <div class="DynamicTree">
                        <div class="wrap1">
                            <div class="top">{translate}Adminmenu{/translate}</div>
                            <div class="wrap2" id="tree">
                                {assign var=name value=$wert.name}
                                {mod name="admin" sub="menueditor" func="get_export_div" params=||$name}
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            </table>
        </div>
        <input type="hidden" name="name" value="{$wert.name}">
        <p>
            <input class="input_submit" type="submit" value="{translate}Export{/translate}" name="submit">
        </p>
        <p>
            <a href="javascript:clip_menu('menucontainer_{$wert.name}');">{translate}Add menu...{/translate}</a>
        </p>
        </td>

</tr>
</form>
{/foreach}
</table>
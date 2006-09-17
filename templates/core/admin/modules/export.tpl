{$chmod_tpl}
{doc_raw}
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/luna.css" />
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/tabpane.js"></script>
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
    
    function str_replace (search, replace, subject)
    {
      var result = "";
      var  oldi = 0;
      for (i = subject.indexOf (search)
         ; i > -1
         ; i = subject.indexOf (search, i))
      {
        result += subject.substring (oldi, i);
        result += replace;
        i += search.length;
        oldi = i;
      }
      return result + subject.substring (oldi, subject.length);
    }
    
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
    
    function show_tree(name)
    {
        document.getElementById(name + '_tree').innerHTML = str_replace('{name}', name, document.getElementById('tree_container').innerHTML);
    }
    </script>
    {/literal}
{/doc_raw}
<div id="tree_container" style="display: none">
    {$folder_tree}
</div>
<div id="loading" style="display: none; position: absolute; top: 0px; left: 0px; width: 100%; height: 20px; text-align: center; background-color: lightblue;">
Loading...
</div>
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
    <img width="100px" height="100px" src="{$www_core_tpl_root}/images/modules/{$wert.image_name}">
    </td>
    
    <td class="cell2">
        <div class="tab-pane" id="{$wert.name}">
    
            <script type="text/javascript">
                tp1 = new WebFXTabPane( document.getElementById( "{$wert.name}" ) );
            </script>
    	    <div class="tab-page" id="{$wert.name}_generals">
    	       <h2 class="tab">{translate}General{/translate}</h2>
    	       <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_generals" ) );</script>
                <table cellpadding="2" cellspacing="2" border="0">
                    <tr><td><b>{translate}Description:{/translate}</b></td><td>{$wert.description}</td></tr>
                    <tr><td><b>{translate}Foldername:{/translate}</b></td><td>{$wert.folder_name}</td></tr>
                    <tr><td><b>{translate}Classname:{/translate}</b></td><td>{$wert.class_name}</td></tr>
                    <tr><td><b>{translate}Filename:{/translate}</b></td><td>{$wert.file_name}</td></tr>
                    <tr><td><b>{translate}URL:{/translate}</b></td><td><a href="index.php?mod={$wert.name}">index.php?mod={$wert.name}</a></td></tr>
                </table>
            </div>

            <div class="tab-page" id="{$wert.name}_adminmenu">
                <h2 class="tab">{translate}Adminmenu{/translate}</h2>
                <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_adminmenu" ) );</script>
                <div id="menucontainer_{$wert.name}">
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
            </div>
            <div class="tab-page" id="{$wert.name}_files">
                <h2 class="tab"><span onClick="show_tree('{$wert.name}')">{translate}Files{/translate}</span></h2>
                <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_files" ) );</script>
                <div class="DynamicTree">
                    <div class="wrap1">
                        <div class="top">{translate}Root Folder{/translate}</div>
                        <div class="wrap2" id="{$wert.name}_tree">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-page" id="{$wert.name}_language">
                <h2 class="tab">{translate}SQL{/translate}</h2>
                <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_language" ) );</script>
                SQL Container
            </div>
        </div>

    </td>
    
    <td class="cell1" align="center">
        <input type="hidden" name="name" value="{$wert.name}">
        <p>
            <input class="ButtonGreen" type="submit" value="{translate}Export{/translate}" name="submit">
        </p>
    </td>

</tr>
</form>
{/foreach}
</table>

<script type="text/javascript">
    setupAllTabs();
</script>
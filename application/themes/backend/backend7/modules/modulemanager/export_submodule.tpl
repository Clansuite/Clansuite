{$chmod_tpl}
{move_to target="pre_head_close"}
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}admin/luna.css" />
    <script type="text/javascript" src="{$www_root_themes_core}javascript/tabpane.js"></script>
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}admin/adminmenu/DynamicTree.css" />
    
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
    </script>
    
{/move_to}
<div id="loading" style="display: none; position: absolute; top: 0px; left: 0px; width: 100%; height: 20px; text-align: center; background-color: lightblue;">
Loading...
</div>

{foreach key=schluessel item=wert from=$content.whitelisted}
<form action="index.php?mod=controlcenter&sub=modules&action=export" method="post" accept-charset="UTF-8">
<table cellspacing="0" cellpadding="0" border="0" width="100%">

<tr>

    <td class="cell1" align="center"  width="120px">
    <strong>{$wert.title}</strong><br />
    <img width="100px" height="100px" src="{$www_root_themes_core}images/modules/{$wert.image_name}">
    </td>

    <td class="cell2" width="65%">
        <div class="tab-pane" id="{$wert.name}_tabs">

            <script type="text/javascript">
                tp1 = new WebFXTabPane( document.getElementById( "{$wert.name}_tabs" ) );
            </script>
    	    <div class="tab-page" id="{$wert.name}_generals">
    	       <h2 class="tab">{t}General{/t}</h2>
    	       <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_generals" ) );</script>
                <table cellpadding="2" cellspacing="2" border="0">
                    <tr><td><strong>{t}Description:{/t}</strong></td><td>{$wert.description}</td></tr>
                    <tr><td><strong>{t}Foldername:{/t}</strong></td><td>{$wert.folder_name}</td></tr>
                    <tr><td><strong>{t}Classname:{/t}</strong></td><td>{$wert.class_name}</td></tr>
                    <tr><td><strong>{t}Filename:{/t}</strong></td><td>{$wert.file_name}</td></tr>
                    <tr><td><strong>{t}URL:{/t}</strong></td><td><a href="index.php?mod={$wert.name}">index.php?mod={$wert.name}</a></td></tr>
                </table>
            </div>

            <div class="tab-page" id="{$wert.name}_adminmenu">
                <h2 class="tab">{t}Adminmenu{/t}</h2>
                <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_adminmenu" ) );</script>
                <div id="menucontainer_{$wert.name}">
                    <div class="DynamicTree">
                        <div class="wrap1">
                            <div class="top">{t}Adminmenu{/t}</div>
                            <div class="wrap2" id="tree">
                                {assign var=name value=$wert.name}
                                {mod name="admin" sub="menueditor" func="get_export_div" params=||$name}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-page" id="{$wert.name}_files">
                <h2 class="tab">{t}Files{/t}</h2>
                <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_files" ) );</script>
                {assign var=filebrowsername value=$wert.name}
                {mod name="filebrowser" func="instant_show" params="|admin/modules/filebrowser.tpl|admin/modules/filebrowser_sections.tpl|$filebrowsername"}
            </div>
            <div class="tab-page" id="{$wert.name}_language">
                <h2 class="tab">{t}SQL{/t}</h2>
                <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_language" ) );</script>
                <div class="DynamicTree">
                    <div class="wrap1">
                        <div class="top">{t}SQL Tables{/t}</div>
                        <div class="wrap2" id="tree">
                            {foreach key=key item=item from=$sql_tables}
                                <div class='doc'>
                                    <img src="{$www_root_themes_core}admin/adminmenu/images/tree-leaf.gif" width="18" height="18" border="0">
                                    <input type="checkbox" name="tables[{$wert.name}][]" value="{$item}" />
                                    <img src="{$www_root_themes_core}admin/adminmenu/images/tree-doc.gif" width="18" height="18" border="0">
                                    {$item}
                                </div>
                            {/foreach}
                        </div>
                    </div>
                </div>

                <br />
                <table>
                	<tr>
                	<td>
		                <input type="checkbox" value="true" name="use_sql_textarea">
		       		</td>
		       		<td style="padding-top: 3px;">
                		{t}I want to use own SQL commands (textarea below){/t}
         			</td>
         			</tr>
         		</table>
                <br />
                <textarea style="border: 1px solid grey;" rows="20" cols="40" name="sql_textarea"></textarea>

            </div>
        </div>

    </td>

    <td class="cell1" align="center" width="15%">
        <input type="hidden" name="name" value="{$wert.name}">
        <p>
            <input class="ButtonGreen" type="submit" value="{t}Export{/t}" name="submit">
        </p>
    </td>
</tr>
</table>
</form>
{/foreach}

<script type="text/javascript">
    setupAllTabs();
</script>
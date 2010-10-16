{move_to target="pre_head_close"}
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.jstree.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.cookie.js"></script>

<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}css/jquery/jstree/themes/default/style.css" />
{/move_to}

{modulenavigation}

<div class="ModuleHeading">{t}Adminmenu - Administration{/t}</div>
<div class="ModuleHeadingSmall">{t}With the Menueditor you may modify the menu entries. You can also add new ones or modify or delete existing ones.{/t}</div>

<script type="text/javascript">
$(function(){
     $("#tree1").jstree({ "core"    : { "initially_open" : [ "node_1" ] },
                          "plugins" : [ "themes", "html_data", "dnd" ],});
     $("#tree2").jstree({ "core"    : { "initially_open" : [ "node_1" ] },
                          "plugins" : [ "themes", "html_data", "dnd"],});
});
</script>

<table border=1>
<thead>
<td>Menu</td>
<td>Items</td>
</thead>
<tbody>
<tr>
  <td>
    <div id="tree1">{$tree}</div>
  </td>
  <td>
    <div id="tree2">
    <ul>
        <li id="node_1" class="jstree-open"><a href="#">Root node 1</a>
            <ul>
                <li><a href="#">Child node 1</a></li>
                <li><a href="#">Child node 2</a></li>
                <li><a href="#">Some other child node with longer text</a></li>
            </ul>
        </li>
        <li><a href="#">Root node 2</a></li>
    </ul>
    </div>
  </td>
</tr>
</tbody>
</table>
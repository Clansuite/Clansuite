{move_to target="pre_head_close"}
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.tree.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.cookie.js"></script>

<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}css/jquery/jstree/themes/default/style.css" />
{/move_to}

{modulenavigation}

<div class="ModuleHeading">{t}Adminmenu - Administration{/t}</div>
<div class="ModuleHeadingSmall">{t}With the Menueditor you may modify the menu entries. You can also add new ones or modify or delete existing ones.{/t}</div>

<script type="text/javascript">
$(function(){
     $("#basic_html").tree({ rules : { multitree : true } });
					$("#tree").tree({ rules : { multitree : true } });
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
    <div id="tree" class="tree">{$tree}</div>
  </td>
  <td>
    <div id="basic_html">
        <ul>
         <li class="open"><a href="#"><ins>&nbsp;</ins>Root node 1</a>
          <ul>
           <li ><a href="#"><ins>&nbsp;</ins>Child node 1</a></li>
           <li ><a href="#"><ins>&nbsp;</ins>Child node 2</a></li>
           <li ><a href="#"><ins>&nbsp;</ins>Some other child node with longer text</a></li>
          </ul>
         </li>
         <li ><a href="#"><ins>&nbsp;</ins>Root node 2</a></li>
        </ul>
    </div>
  </td>
</tr>
</tbody>
</table>
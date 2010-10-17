{move_to target="pre_head_close"}
<script src="{$www_root_themes_core}javascript/jquery/jquery.js" type="text/javascript"></script>
<script src="{$www_root_themes_core}javascript/jquery/jquery.jstree.js" type="text/javascript"></script>
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.cookie.js"></script>
<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}css/jquery/jstree/themes/default/style.css" />
{/move_to}

{modulenavigation}
<div class="ModuleHeading">{t}Template - Administration{/t}</div>
<div class="ModuleHeadingSmall">{t}You can write, edit and delete them by using the Templateeditor.{/t}</div>

{t}You have selected the templates of module:{/t} <font color="red" size="2"> {$templateeditor_modulename} </font>

<br /><br />

<script language="javascript"><!--
$(function () {
    $("#templates_menu_tree").jstree({
        "core"    : { "initially_open" : [ "templates_menu_tree_root" ] },
        "plugins" : [ "themes", "html_data", ],
    });
});
--></script>

<div id="templates_menu_tree"
     style="width:400px; float:left; background:none repeat scroll 0 0 #FFFFFF; border:1px solid #919B9C; padding:10px;">
    {* ROOT *}
    <ul id="templates_menu_tree_root">
        {* FOLDER *}
        <li class="open" rel="root">
            <a href="#"><ins>&nbsp;</ins>{$templateeditor_modulename|ucfirst} Templates</a>
            {* FILES *}
            <ul rel="folder">
            {foreach from=$templates item=template name=tpls}

                <li id="file-{$smarty.foreach.tpls.iteration}" rel="file"><ins>&nbsp;</ins>
                    [{$smarty.foreach.tpls.iteration}] <a href="{$www_root}index.php?mod=templatemanager&amp;sub=admin&amp;action=edit&amp;file={$templateeditor_modulename}/view/{$template.filename}">
                    <ins>&nbsp;</ins>
                    {$template.filename}
                    </a>
                </li>

            {/foreach}
            </ul>
        </li>
    </ul>
</div>
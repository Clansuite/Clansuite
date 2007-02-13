{doc_raw}
<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/adminmenu/DynamicTree.css" />
{* bei src= die anf�hrungsstriche setzen *}                    
<script type="text/javascript" src="{$www_core_tpl_root}/admin/adminmenu/DynamicTreeBuilder.js"></script>
<script type="text/javascript" src="{$www_core_tpl_root}/admin/adminmenu/plugins.js"></script>
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
    {/literal}
{/doc_raw}


<table cellspacing="0" cellpadding="10" style="margin-top: 1em;">
    <tr>
        <td valign="top">

            <div class="DynamicTree">
                <div class="wrap1">
                    <div class="top">{translate}Adminmenu{/translate}</div>
                    <div class="wrap2" id="tree">
                        {mod name="admin" sub="menueditor" func="get_adminmenu_div"}
                    </div>
                </div>
                <div class="actions">
                    <a id="tree-moveUp" class="moveUp" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/moveUp.gif" width="20" height="20" alt="Menueditor - MoveUp Icon" /></a>
                    <a id="tree-moveDown" class="moveDown" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/moveDown.gif" width="20" height="20" alt="Menueditor - MoveDown Icon" /></a>
                    <a id="tree-moveLeft" class="moveLeft" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/moveLeft.gif" width="20" height="20" alt="Menueditor - MoveLeft Icon" /></a>
                    <a id="tree-moveRight" class="moveRight" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/moveRight.gif" width="20" height="20" alt="Menueditor - MoveRight Icon" /></a>
                    <a id="tree-insert" class="insert" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/insert.gif" width="20" height="20" alt="Menueditor - Insert Icon" /></a>
                    <a id="tree-info" class="info" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/info.gif" width="20" height="20" alt="Menueditor - Info Icon" /></a>
                    <a id="tree-remove" class="remove" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/delete.gif" width="20" height="20" alt="Menueditor - Delete Icon" /></a>
                    <div class="tooltip" id="tree-tooltip"></div>
                </div>
                <div id="tree-insert-form">
                    <form action="javascript:void(0)" method="get">
                        <table cellspacing="0" cellpadding="0">
                        <tr id="tree-insert-where-div">
                            <td class="label">Where</td>
                            <td><select id="tree-insert-where" name="tree-insert-where" class="where"><option value="before">Before</option><option value="after">After</option></select></td>
                        </tr>
                        <tr>
                            <td class="label">Type</td>
                            <td><select id="tree-insert-type" name="tree-insert-type"><option value="doc">Document</option><option value="folder">Folder</option></select></td>
                        </tr>
                        <tr>
                            <td class="label">Name</td>
                            <td><input class="input" size="20" id="tree-insert-name" name="tree-insert-name" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">Href</td>
                            <td><input class="input" size="20" id="tree-insert-href" name="tree-insert-href" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">Title</td>
                            <td><input class="input" size="20" id="tree-insert-title" name="tree-insert-href" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">Target</td>
                            <td><input class="input" size="20" id="tree-insert-target" name="tree-insert-target" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">{translate}Icon{/translate}</td>
                            <td>
                                <select onChange="document.getElementById('insert_icon').src='{$www_core_tpl_root}/images/icons/'+document.getElementById('tree-insert-custom_icon').options[document.getElementById('tree-insert-custom_icon').options.selectedIndex].text" class="input" id="tree-insert-custom_icon">
                                    <option name=""></option>
                                    {foreach key=key item=item from=$icons}
                                        <option style="background-image:url({$www_core_tpl_root}/images/icons/{$item});background-repeat:no-repeat;padding-left:20px;height:20px;line-height:20px;" name="{$item}">{$item}</option>
                                    {/foreach}
                                </select>
                                <img alt="insert icon" src="" id="insert_icon" width="16" height="16" border="1" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input id="tree-insert-button" class="button" type="button" value="Insert" />
                                <input id="tree-insert-cancel" type="button" value="Cancel" />
                            </td>
                        </tr>
                        </table>
                    </form>
                </div>
                <div id="tree-info-form">
                    <form action="javascript:void(0)" method="get">
                        <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="label">{translate}Name{/translate}</td>
                            <td><input class="input" size="20" id="tree-info-name" name="tree-info-name" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">{translate}Href{/translate}</td>
                            <td><input class="input" size="20" id="tree-info-href" name="tree-info-href" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">{translate}Title{/translate}</td>
                            <td><input class="input" size="20" id="tree-info-title" name="tree-info-title" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">{translate}Target{/translate}</td>
                            <td><input class="input" size="20" id="tree-info-target" name="tree-info-target" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">{translate}Icon{/translate}</td>
                            <td>
                                <select onChange="document.getElementById('update_icon').src='{$www_core_tpl_root}/images/icons/'+document.getElementById('tree-info-custom_icon').options[document.getElementById('tree-info-custom_icon').options.selectedIndex].text" class="input" id="tree-info-custom_icon">
                                    <option name=""></option>
                                    {foreach key=key item=item from=$icons}
                                        <option style="background-image:url({$www_core_tpl_root}/images/icons/{$item});background-repeat:no-repeat;padding-left:20px;height:20px;line-height:20px;" name="{$item}">{$item}</option>
                                    {/foreach}
                                </select>
                                <img alt="update icon" src="" name="update_icon" id="update_icon" width="16" height="16" border="1" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input class="ButtonGreen" id="tree-info-button" type="button" value="Update" />
                                <input class="ButtonGrey" id="tree-info-cancel" type="button" value="Cancel" />
                            </td>
                        </tr>
                        </table>
                    </form>
                </div>
            </div>

        </td>
        <td valign="top">

            <p>
                <input type="button" class="ButtonYellow" onClick="window.open('{$www_core_tpl_root}/admin/adminmenu/help.html', 'Contents', 'width=400,height=400,scrollbars=yes')" value="{translate}Help{/translate}" />
            </p>
            <p>
                <input type="button" class="ButtonGreen" value="{translate}Generate Menu{/translate}" onClick="treePluginGenerateMenu();" />
            </p>
            <p>
                <input type="button" onClick="self.location.href='index.php?mod=admin&sub=menueditor&action=restore'"class="ButtonRed" value="{translate}Restore last menu{/translate}" />
            </p>
        </td>
        <td valign="top">

            <div id="tree-plugin">
                <form action="index.php?mod=admin&sub=menueditor&action=update" method="POST">
                <div id="tree-plugin-content"></div>
                <b>{translate}The menu has been generated.{/translate}</b> <br />
                {translate}Click the button below, to save the menu into the Database.{/translate} <br />
                <p>
                    <input class="ButtonGreen" type="submit" name="submit" value="{translate}Update the menu{/translate}">
                </p>
                </form>
            </div>

        </td>
    </tr>
    </table>
    
    <script type="text/javascript">
    var tree = new DynamicTreeBuilder("tree", "{$www_core_tpl_root}/admin/adminmenu/images/", "{$www_core_tpl_root}/images/icons/");
    tree.init();
    DynamicTreePlugins.call(tree);
    </script>
    <script type="text/javascript" src="{$www_core_tpl_root}/admin/adminmenu/actions.js"></script>
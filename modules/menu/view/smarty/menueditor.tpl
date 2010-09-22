{modulenavigation}

{move_to target="pre_head_close"}
<link rel="stylesheet" type="text/css" href="{$www_root_mod}css/DynamicTree.css" />
<script type="text/javascript" src="{$www_root_mod}javascript/DynamicTreeBuilder.js"></script>
<script type="text/javascript" src="{$www_root_mod}javascript/plugins.js"></script>


    <style type="text/css">
        form { margin: 0; }
        input[readonly] { border: 1px solid #7F9DB9; background: #ffffff; }
        a { color: #0000ee; text-decoration: none; }
        a:hover { color: #0000ee; text-decoration: underline; }
        p { margin-top: 0; margin-bottom: 1em; }
        #tree-plugin, #tree-plugin-button-import-html { display: none; }
        #tree-plugin-textarea { white-space: nowrap; }
    </style>


{/move_to}

<div class="ModuleHeading">{t}Adminmenü - Verwaltung{/t}</div>
<div class="ModuleHeadingSmall">{t}Mit dem Menüeditor können Sie die Menüpunkte des Adminmenüs verändern oder neue hinzufügen, sowie alte entfernen.{/t}</div>

<table cellspacing="0" cellpadding="10" style="margin-top: 1em;">
    <tr>
        <td valign="top">

            <!-- Dynamic Tree Builder for Adminmenu -->
            <div class="DynamicTree">
            Dynamic Tree Builder for Adminmenu
                <div class="wrap1">
                    <div class="top">{t}Adminmenu{/t}</div>
                    <div class="wrap2" id="tree">

                        <!-- Adminmenu Tree Display -->
                        {load_module name="menu" sub="admin" action="get_adminmenu_div"}

                    </div>
                </div>

            <!-- Actions for editing the Adminmenu -->
            <div class="actions">
                <b>Actions</b><br /><br />

                    <!-- Up -->
                    <a id="tree-moveUp" class="moveUp" href="javascript:void(0)">
                        <img src="{$www_root_mod}images/moveUp.gif" width="20" height="20" alt="Menueditor - MoveUp Icon" />
                    </a>

                    <!-- Down -->
                    <a id="tree-moveDown" class="moveDown" href="javascript:void(0)">
                        <img src="{$www_root_mod}images/moveDown.gif" width="20" height="20" alt="Menueditor - MoveDown Icon" />
                    </a>

                    <!-- Left -->
                    <a id="tree-moveLeft" class="moveLeft" href="javascript:void(0)">
                        <img src="{$www_root_mod}images/moveLeft.gif" width="20" height="20" alt="Menueditor - MoveLeft Icon" />
                    </a>

                    <!-- Right -->
                    <a id="tree-moveRight" class="moveRight" href="javascript:void(0)">
                        <img src="{$www_root_mod}images/moveRight.gif" width="20" height="20" alt="Menueditor - MoveRight Icon" />
                    </a>

                     <!-- Insert / Add new Element -->
                    <a id="tree-insert" class="insert" href="javascript:void(0)">
                        <img src="{$www_root_mod}images/insert.gif" width="20" height="20" alt="Menueditor - Insert Icon" />
                    </a>

                     <!-- Info / Edit Element -->
                    <a id="tree-info" class="info" href="javascript:void(0)">
                        <img src="{$www_root_mod}images/info.gif" width="20" height="20" alt="Menueditor - Info Icon" />
                    </a>

                    <!-- Delete Element -->
                    <a id="tree-remove" class="remove" href="javascript:void(0)">
                        <img src="{$www_root_mod}images/delete.gif" width="20" height="20" alt="Menueditor - Delete Icon" />
                    </a>

                    <!-- Action Tooltip -->
                    <div class="tooltip" id="tree-tooltip">Choose Action</div>
                </div>

                <br />
                <br />

                <!-- Form Elements for INSERTING a NEW ITEM into Adminmenu -->
                <div id="tree-insert-form">
                    <div class="actions">
                        <b>{t}Insert new Menuelement{/t}</b>
                        <br />
                        <br />
                    </div>
                    <form action="javascript:void(0)" method="get">
                        <table cellspacing="0" cellpadding="0">
                        <tr id="tree-insert-where-div">
                            <td class="label">Where</td>
                            <td><select id="tree-insert-where" name="tree-insert-where" class="input_text"><option value="before">Before</option><option value="after">After</option></select></td>
                        </tr>
                        <tr>
                            <td class="label">Type</td>
                            <td><select id="tree-insert-type" name="tree-insert-type" class="input_text"><option value="doc">Document</option><option value="folder">Folder</option></select></td>
                        </tr>
                        <tr>
                            <td class="label">Name</td>
                            <td><input class="input_text" size="40" id="tree-insert-name" name="tree-insert-name" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">Href</td>
                            <td><input class="input_text" size="40" id="tree-insert-href" name="tree-insert-href" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">Title</td>
                            <td><input class="input_text" size="40" id="tree-insert-title" name="tree-insert-href" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">Target</td>
                            <td><input class="input_text" size="40" id="tree-insert-target" name="tree-insert-target" type="text" value="_self" /></td>
                        </tr>
                        <tr>
                            <td class="label">{t}View Permission{/t}</td>
                            <td><input class="input_text" size="20" id="tree-insert-permission" name="tree-insert-permission" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">{t}Icon{/t}</td>
                            <td>
                                <select onchange="document.getElementById('insert_icon').src='{$www_root_themes_core}images/icons/'+document.getElementById('tree-insert-custom_icon').options[document.getElementById('tree-insert-custom_icon').options.selectedIndex].text"
                                        class="input" id="tree-insert-custom_icon">
                                    <option value="">{t}No icon{/t}</option>

                                    {foreach item=icon from=$icons}

                                        <option style="background-image:url({$www_root_themes_core}images/icons/{$icon});
                                                       background-repeat:no-repeat;padding-left:20px;
                                                       height:20px;
                                                       line-height:20px;"
                                                       value="{$icon}">{$icon}</option>

                                    {/foreach}

                                </select>
                                <img alt="insert icon" src="" id="insert_icon" width="16" height="16" border="1" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input class="ButtonGreen" id="tree-insert-button" class="button" type="button" value="Insert" />
                                <input class="ButtonGrey" id="tree-insert-cancel" type="button" value="Cancel" />
                            </td>
                        </tr>
                        </table>
                    </form>
                </div>

                <!-- Form Elements for EDITING a ITEM into Adminmenu -->
                <div id="tree-info-form">
                    <div class="actions">
                        <b>{t}Edit the Menuelement{/t}</b>
                        <br />
                        <br />
                    </div>
                    <form action="javascript:void(0)" method="get">
                        <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="label">{t}Name{/t}</td>
                            <td><input class="input_text" size="40" id="tree-info-name" name="tree-info-name" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">{t}URL (href){/t}</td>
                            <td><input class="input_text" size="40" id="tree-info-href" name="tree-info-href" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">{t}Tooltip Title{/t}</td>
                            <td><input class="input_text" size="40" id="tree-info-title" name="tree-info-title" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">{t}Target{/t}</td>
                            <td><input class="input_text" size="40" id="tree-info-target" name="tree-info-target" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">{t}View Permission{/t}</td>
                            <td><input class="input_text" size="40" id="tree-info-permission" name="tree-info-permission" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">{t}Icon{/t}</td>
                            <td>
                                <select onchange="document.getElementById('update_icon').src='{$www_root_themes_core}images/icons/'+document.getElementById('tree-info-custom_icon').options[document.getElementById('tree-info-custom_icon').options.selectedIndex].text"
                                        class="input_text" id="tree-info-custom_icon">
                                    <option value="">{t}No icon{/t}</option>

                                    {foreach item=icon from=$icons}

                                        <option style="background-image:url({$www_root_themes_core}images/icons/{$icon});
                                                       background-repeat:no-repeat;
                                                       padding-left:20px;
                                                       height:20px;
                                                       line-height:20px;"
                                                value="{$icon}">{$icon}</option>

                                    {/foreach}

                                </select>
                                <img alt="update icon" src="" name="update_icon" id="update_icon" width="16" height="16" border="1" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input class="ButtonGreen" id="tree-info-button" type="button" value="Update Details" />
                                <input class="ButtonGrey" id="tree-info-cancel" type="button" value="Cancel &amp; Leave Details" />
                            </td>
                        </tr>
                        </table>
                    </form>
                </div>
            </div>
        </td>

        <td valign="top">

            <p>
               <input type="button" onclick="self.location.href='index.php?mod=menu&amp;sub=admin&amp;action=restore'"
                     class="ButtonRed" value="{t}Restore the last Adminmenu{/t}" />
            </p>
            <p>
               <input type="button" class="ButtonGreen" value="{t}Generate Menu{/t}" onclick="treePluginGenerateMenu();" />
            </p>


            <div id="tree-plugin">
                <form action="index.php?mod=menu&amp;sub=admin&amp;action=update" method="post" accept-charset="UTF-8">
                <div id="tree-plugin-content"></div>
                 <strong>{t}The menu has been generated.{/t}</strong>
                    <br />
                    {t}Click the button below, to save the menu into the Database.{/t}
                <p>
                    <input class="ButtonGreen" type="submit" name="submit" value="{t}Update the Adminmenu{/t}" />
                </p>
                </form>
            </div>

        </td>
        <td>
            {include file="help.html"}
        </td>
    </tr>
    </table>

    <script type="text/javascript">
        var tree = new DynamicTreeBuilder("tree", "{$www_root}modules/menu/images/", "{$www_root_themes_core}images/icons/");
        tree.init();
        DynamicTreePlugins.call(tree);
    </script>
    <script type="text/javascript" src="{$www_root_mod}javascript/actions.js"></script>
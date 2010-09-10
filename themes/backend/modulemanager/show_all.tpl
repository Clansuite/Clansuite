{move_to target="pre_head_close"}
    {* Dynamic Tree *}
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}admin/adminmenu/DynamicTree.css" />
    {* Tabs *}
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}admin/luna.css" />
    <script type="text/javascript" src="{$www_root_themes_core}javascript/tabpane.js"></script>
    {* Prototype + Scriptaculous + Smarty_Ajax *}
    <script src="{$www_root_themes_core}javascript/prototype/prototype.js" type="text/javascript"></script>
    <script src="{$www_root_themes_core}javascript/scriptaculous/scriptaculous.js" type="text/javascript"></script>
    <script src="{$www_root_themes_core}javascript/smarty_ajax.js" type="text/javascript"></script>
    {* Tablegrid Extension *}
    <script type="text/javascript" src="{$www_root_themes_core}javascript/tablegrid.js"></script>
    
        <script type="text/javascript">
        function toggle(x,id)
        {
            if ($(x).className == "ButtonOrange")
            {
                $(x).Classnames.set = "ButtonGreen";
            }

            var bold = false; // to use in the bold-allowing script

            x.src = (x.src== 'bold_uit.gif')?'bold_aan.gif':'bold_uit.gif'
            bold = (x.src == 'bold_aan.gif')
        }
        </script>
    
{/move_to}

{* <a id="addrow" href="#"><img src="newrow.gif" alt="" /></a> *}
{* #### TAB PANE 1 - MODULES - NOT IN WHITELIST #### *}
{if isset($content.not_in_whitelist)}
    <div class="tab-pane" id="tab-pane-1">
        <div class="tab-page">
              <h2 class="tab">New Modules!</h2>
              {include file="admin/modules/showall_notinwhitelist.tpl"}
        </div>
    </div>  <!-- tab-pane-1 closed -->
    <br />
{/if}
{*






    @todo: XHTML requires a letter to be the first at an id!!! so stuff like id="{$wert.module_id}_{$wert.name}_{$item}" wont't work!!!
            Next step: Xilinus without this crap!!!







*}
{* #### TAB PANE 2 - MODULES - IN WHITELIST | NORMAL and CORE #### *}
<div class="tab-pane" id="tab-pane-2">
    {* #### TAB PAGE - NORMAL MODULES | IN WHITELIST #### *}
    <div class="tab-page">
        <h2 class="tab">Normal</h2>
        <table cellspacing="0" cellpadding="0" border="0" style="width:100%">
        <tr>
            <td class="td_header" colspan="5">
				{t}Normal modules{/t}
			</td>
        </tr>
        <tr>
            <td class="td_header_small" style="width:15px"></td>
            <td class="td_header_small" style="width:120px">{t}Title{/t}</td>
            <td class="td_header_small" style="width:80%">{t}Information{/t}</td>
            <td class="td_header_small" style="width:5%;text-align:center">{t}Enabled{/t}</td>
            <td class="td_header_small" style="text-align:center">{t}Uninstall{/t}</td>
        </tr>
        {foreach key=schluessel item=wert from=$content.whitelisted.normal}
        <tr>
            <td class="cell1" style="text-align:center">
                <input type="hidden" name="ids[]" value="{$wert.module_id}" />
                <img src="{$www_root_themes_core}images/modules/{if $wert.enabled == 1}module-active.gif{else}module-inactive.gif{/if}" id="modul_onoff_image_{$wert.module_id}" alt="" />
            </td>
            <td class="cell2" style="text-align:center">
                <strong>{$wert.title} </strong> (#{$wert.module_id})<br />
                <img src="{$www_root_themes_core}images/modules/{$wert.image_name}" id="modul_onoff_image_{$wert.module_id}" alt="" />
                <small>Version: xy</small>
            </td>
            <td class="cell1">
                <div class="tab-pane" id="{$wert.name}_tabs">
                <script type="text/javascript">tp1 = new WebFXTabPane( document.getElementById( "{$wert.name}_tabs" ) );</script>
                {* #### MODULES - GENERALS #### *}
                <div class="tab-page" id="{$wert.name}_generals">
                   <h2 class="tab">{t}General{/t}</h2>
                   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_generals" ) );</script>
                    <table class="tableedit" id="table_for_modules_{$wert.module_id}" cellpadding="2" cellspacing="2" border="0">
                    {* Content of $content.generals = Title, Author, Description, Homepage *}
                    {foreach key=key item=item from=$content.generals}
                    <tr>
                        <td style="width:40px"><strong>{t}{$key}:{/t}</strong></td>
                        <td class="editcell" id="{$wert.module_id}_{$wert.name}_{$item}">{$wert.$item}</td>
                    </tr>
                    {/foreach}
                    </table>
                    <script type="text/javascript">new TableGrid('table_for_modules_{$wert.module_id}', '2', 'index.php?mod=controlcenter&amp;sub=modules&amp;action=ajaxupdate_modules');</script>
                </div>
                {* #### MODULES - DETAILS #### *}
             <div class="tab-page" id="{$wert.name}_details">
                <h2 class="tab"{t}>Moduledetails{/t}</h2>
                   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_details" ) );</script>
                    <table class="tableedit" id="details_table_{$wert.module_id}" cellpadding="2" cellspacing="2" border="0">
                    {foreach key=key item=item from=$content.more}
                        <tr>
                            <td style="width:40px"><strong>{t}{$key}:{/t}</strong></td>
                            <td class="editcell" id="{$wert.module_id}_{$wert.name}_{$item}">{$wert.$item}</td>
                        </tr>
                    {/foreach}
                    </table>
                    <script type="text/javascript">new TableGrid('details_table_{$wert.module_id}', '2', 'index.php?mod=controlcenter&amp;sub=modules&amp;action=ajaxupdate_modules');</script>
                </div>
                {* #### MODULES - SUBMODULES #### *}
                <div class="tab-page" id="{$wert.name}_subs">
                   <h2 class="tab">{t}Submodules{/t}</h2>
                   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_subs" ) );</script>
                   <table cellpadding="2" cellspacing="2" border="0" style="width:100%">
                    <tr>
                        <td><strong>{t}Submodules:{/t}</strong></td>
                        <td>
                           {if is_array($wert.subs)} {* #### SUBMODULES FOUND #### *}
                            {* #### Debug of Submodules Array #### {$wert.subs|@var_dump}*}
                            {foreach key=key item=item from=$wert.subs}
                            <table class="tableedit" id="submodules_table_{$wert.module_id}_{$item.submodule_id}" cellspacing="0" cellpadding="0" border="0" style="width:100%">
                            <tr>
                                <td style="width:40px"><strong>Name</strong> (#{$item.submodule_id}):</td>
								<td class="editcell">{$key}</td>
                            </tr>
                            <tr>
                                <td style="width:40px"><strong>File:</strong></td>
								<td class="editcell">{$item.file_name}</td>
                            </tr>
                            <tr>
                                <td style="width:40px"><strong>Class:</strong></td>
								<td class="editcell">{$item.class_name}</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    {$wert.module_id}_sub_{$item.submodule_id} - Delete submodule: {$key} (#{$item.submodule_id}) from whitelist
                                </td>
                            </tr>
                            </table>
                            <script type="text/javascript">new TableGrid('submodules_table_{$wert.module_id}_{$item.submodule_id}', '2', 'index.php?mod=controlcenter&amp;sub=modules&amp;action=ajaxupdate_submodules');</script>
                            {/foreach}
                            {else} {* #### NO SUBMODULES FOUND #### *}
                                {t}No submodules.{/t}
                            {/if}
                           <br />
						   {$wert.module_id}_subs Add a submodule
                        </td>
                    </tr>
                    </table>
                </div>
                {* #### MODULES - CONFIG #### *}
             <div class="tab-page" id="{$wert.name}_config">
                <h2 class="tab"{t}>Config{/t}</h2>
                    <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_config" ) );</script>
                    <table class="tableedit" id="details_table_{$wert.module_id}" cellpadding="2" cellspacing="2" border="0">
                    {foreach key=key item=item from=$content.more}
                        <tr>
                            <td style="width:40px"><strong>{t}{$key}:{/t}</strong> todo config settings per module</td>
                            <td class="editcell" id="{$wert.module_id}_{$wert.name}_{$item}">{$wert.$item}</td>
                        </tr>
                    {/foreach}
                    </table>
                    <script type="text/javascript">new TableGrid('details_table_{$wert.module_id}', '2', 'index.php?mod=controlcenter&amp;sub=modules&amp;action=ajaxupdate_modules');</script>
                </div>
                </div>
            </td>
            <td class="cell2" style="text-align:center;vertical-align:middle">
                <div id="module_disabled_{$wert.module_id}" {if $wert.enabled==1}style="display: block"{else}style="display: none"{/if}><input class="ButtonOrange" type="button" onclick="{ajax_update url="index.php?mod=controlcenter&amp;sub=modules&amp;action=ajaxupdate_onoffswitch" params="module_id=`$wert.module_id`&amp;value=0" callback="document.getElementById(\'module_disabled_`$wert.module_id`\').style.display=\'none\';document.getElementById(\'module_enabled_`$wert.module_id`\').style.display=\'block\';" method="get"}" value="{t}Disable{/t}" name="submit" /></div>
                <div id="module_enabled_{$wert.module_id}" {if $wert.enabled==1}style="display: none"{else}style="display: block"{/if}><input class="ButtonGreen" type="button" onclick="{ajax_update url="index.php?mod=controlcenter&amp;sub=modules&amp;action=ajaxupdate_onoffswitch" params="module_id=`$wert.module_id`&amp;value=1" callback="document.getElementById(\'module_disabled_`$wert.module_id`\').style.display=\'block\';document.getElementById(\'module_enabled_`$wert.module_id`\').style.display=\'none\';" method="get"}" value="{t}Enable{/t}" name="submit" /></div>

            </td>
            <td class="cell1" style="text-align:center;vertical-align:middle">
                <form action="index.php?mod=controlcenter&amp;sub=modules&amp;action=uninstall&amp;module_id={$wert.module_id}&amp;folder_name={$wert.folder_name}" method="post" accept-charset="UTF-8">
                    <input type="hidden" name="module_name" value="{$wert.title}" />
                    <input class="ButtonRed" type="submit" value="{t}Uninstall{/t}" name="submit" />
                </form>
            </td>
        </tr>
        {/foreach}
        </table>
   </div>
   {* #### TAB PAGE - CORE MODULES | IN WHITELIST #### *}
   <div class="tab-page">
      <h2 class="tab">Core</h2>
      <table cellspacing="0" cellpadding="0" border="0" style="width:100%">
        <tr>
            <td class="td_header" colspan="5">{t}Core modules{/t}</td>
        </tr>
        <tr>
            <td class="td_header_small" style="width:15px"></td>
            <td class="td_header_small" style="width:120px">{t}Title{/t}</td>
            <td class="td_header_small" style="width:80%">{t}Information{/t}</td>
            <td class="td_header_small" style="width:5%;text-align:center">{t}Enabled{/t}</td>
            <td class="td_header_small" style="text-align:center">{t}Uninstall{/t}</td>
        </tr>
        {foreach key=schluessel item=wert from=$content.whitelisted.core}
        <tr>
            <td class="cell1" style="text-align:center">
                <input type="hidden" name="ids[]" value="{$wert.module_id}" />
                <img src="{$www_root_themes_core}images/modules/{if $wert.enabled == 1}module-active.gif{else}module-inactive.gif{/if}" id="modul_onoff_image_{$wert.module_id}" alt="" />
            </td>
            <td class="cell2" style="text-align:center">
                <strong>{$wert.title}</strong> (#{$wert.module_id})<br />
                <img src="{$www_root_themes_core}images/modules/{$wert.image_name}" alt=""/>
                <small>Version: xy</small>
            </td>
            <td class="cell1">
                <div class="tab-pane" id="{$wert.name}_tabs">
                <script type="text/javascript">tp1 = new WebFXTabPane( document.getElementById( "{$wert.name}_tabs" ) );</script>
                {* #### MODULES - GENERALS #### *}
                <div class="tab-page" id="{$wert.name}_generals">
                   <h2 class="tab">{t}General{/t}</h2>
                   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_generals" ) );</script>
                    <table class="tableedit" id="table_{$wert.module_id}" cellpadding="2" cellspacing="2" border="0">
                    {* Content of $content.generals = Title, Author, Description, Homepage *}
                    {foreach key=key item=item from=$content.generals}
                        <tr>
                        <td style="width:40px"><strong>{t}{$key}:{/t}</strong></td>
                        <td class="editcell" id="{$wert.module_id}_{$wert.name}_{$item}">{$wert.$item}</td>
                        </tr>
                    {/foreach}
                    </table>
                    <script type="text/javascript">new TableGrid('table_{$wert.module_id}', '2', 'index.php?mod=controlcenter&amp;sub=modules&amp;action=ajaxupdate_modules');</script>
                </div>
                {* #### MODULES - DETAILS #### *}
                <div class="tab-page" id="{$wert.name}_details">
                   <h2 class="tab"{t}>Moduledetails{/t}</h2>
                   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_details" ) );</script>
                    <table class="tableedit" id="details_table_{$wert.module_id}" cellpadding="2" cellspacing="2" border="0">
                    {foreach key=key item=item from=$content.more}
                        <tr>
                            <td style="width:40px"><strong>{t}{$key}:{/t}</strong></td>
                            <td class="editcell" id="{$wert.module_id}_{$wert.name}_{$item}">{$wert.$item}</td>
                        </tr>
                    {/foreach}
                    </table>
                    <script type="text/javascript">new TableGrid('details_table_{$wert.module_id}', '2', 'index.php?mod=controlcenter&amp;sub=modules&amp;action=ajaxupdate_modules');</script>
                </div>
                {* #### MODULES - SUBMODULES  #### *}
                <div class="tab-page" id="{$wert.name}_subs">
                   <h2 class="tab">{t}Submodules{/t}</h2>
                   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_subs" ) );</script>
                   <table cellpadding="2" cellspacing="2" border="0">
                    <tr>
                        <td><strong>{t}Submodules:{/t}</strong></td>
                        <td>
                           {if is_array($wert.subs)} {* #### SUBMODULES FOUND #### *}
                            {* #### Debug of Submodules Array #### {$wert.subs|@var_dump} *}
                            {foreach key=key item=item from=$wert.subs}
                            <table class="tableedit" id="submodules_table_{$wert.module_id}_{$item.submodule_id}" cellspacing="0" cellpadding="0" border="0" style="width:100%">
                            <tr>
                                <td style="width:40px"><strong>Name</strong> (#{$item.submodule_id}):</td>
                                <td class="editcell">{$key}</td>
                            </tr>
                            <tr>
                                <td style="width:40px"><strong>File:</strong></td>
								<td class="editcell">{$item.file_name}</td>
                            </tr>
                            <tr>
                                <td style="width:40px"><strong>Class:</strong></td>
								<td class="editcell">{$item.class_name}</td>
                            </tr>
                            <tr>
                                <td colspan="2">{$wert.module_id}_sub_{$item.submodule_id} - Delete submodule: {$key} (#{$item.submodule_id}) from whitelist</td>
                            </tr>
                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            </table>
                            <script type="text/javascript">new TableGrid('submodules_table_{$wert.module_id}_{$item.submodule_id}', '2', 'index.php?mod=controlcenter&amp;sub=modules&amp;action=ajaxupdate_submodules');</script>
                            {/foreach}
                            {else} {* #### NO SUBMODULES FOUND #### *}
                            {t}No submodules.{/t}
                            {/if}
                            <br />
							{$wert.module_id}_subs - Add a submodule
                        </td>
                    </tr>
                    </table>
                    <script type="text/javascript">new TableGrid('details_table_{$wert.module_id}', '2', 'index.php?mod=controlcenter&amp;sub=modules&amp;action=ajaxupdate_modulesdetails');</script>
                </div>
                </div>
            </td>
            <td class="cell2" style="text-align:center;vertical-align:middle">
                {if $wert.enabled==1}
                <input id="modul_button_{$wert.module_id}" class="ButtonOrange" type="button" onclick="{ajax_update url='index.php?mod=controlcenter&amp;sub=modules&amp;action=ajaxupdate_onoffswitch' update_id="modul_id_`$wert.module_id`" params="module_id=`$wert.module_id`&amp;value=0" callback="new Effect.Puff(\'modul_onoff_image_`$wert.module_id`\')"}" value="{t}Disable{/t}" name="submit" />
                {* new `$(this).Classnames.set = 'ButtonGreen'` *}
                {else}
                <input id=" "class="ButtonGreen" type="submit" onclick="{ajax_update url='index.php?mod=controlcenter&amp;sub=modules&amp;action=ajax_onoffswitch' update_id="server_details_`$server.server_id`" params="server_id=`$server.server_id`" callback="new Effect.Appear(\'modul_onoff_image_`$wert.module_id`\')"}" value="{t}Enable{/t}" name="submit" />
                {/if}
            </td>
            <td class="cell1" style="text-align:center;vertical-align:middle">
                <form action="index.php?mod=controlcenter&amp;sub=modules&amp;action=uninstall&amp;module_id={$wert.module_id}" method="post" accept-charset="UTF-8">
                    <input type="hidden" name="module_name" value="{$wert.title}" />
                    <input class="ButtonRed" type="submit" value="{t}Uninstall{/t}" name="submit" />
                </form>
            </td>
        </tr>
        {/foreach}
        </table>
   </div>
</div> <!-- tab pane 2 closed -->
{* #### Init TabPane #### *}
<script type="text/javascript">setupAllTabs();</script>
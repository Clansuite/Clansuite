{doc_raw}
    {* Dynamic Tree *}
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/adminmenu/DynamicTree.css" />
    {* Tabs *}
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/luna.css" />
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/tabpane.js"></script>
    {* Prototype + Scriptaculous + Smarty_Ajax *}
    <script src="{$www_core_tpl_root}/javascript/prototype/prototype.js" type="text/javascript"></script>
    <script src="{$www_core_tpl_root}/javascript/scriptaculous/scriptaculous.js" type="text/javascript"></script>
    <script src="{$www_core_tpl_root}/javascript/smarty_ajax.js" type="text/javascript"></script>
    {* Tablegrid Extension *}
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/tablegrid.js"></script>
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/css/tablegrid.css" />

    {literal}
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
    {/literal}

{/doc_raw}

{* <a id="addrow" href="#"><img border="0" src="newrow.gif" /></a> *}

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


{* #### TAB PANE 2 - MODULES - IN WHITELIST | NORMAL and CORE #### *}

<div class="tab-pane" id="tab-pane-2">


    {* #### TAB PAGE - NORMAL MODULES | IN WHITELIST #### *}

    <div class="tab-page">
        <h2 class="tab">Normal</h2>

      <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <thead>
        <tr>
            <td class="td_header" colspan="5">    {translate}Normal modules{/translate}    </td>
        </tr>
        <tr>
            <td class="td_header_small" width="15px">                                                   </td>
            <td class="td_header_small" width="120px">              {translate}Title{/translate}        </td>
            <td class="td_header_small" width="80%">                {translate}Information{/translate}  </td>
            <td class="td_header_small" width="5%" align="center">  {translate}Enabled{/translate}      </td>
            <td class="td_header_small" width="5%" align="center">  {translate}Uninstall{/translate}       </td>
        </tr>
        </thead>

        {foreach key=schluessel item=wert from=$content.whitelisted.normal}

        <tr>
            <input type="hidden" name="ids[]" value="{$wert.module_id}">
            <td class="cell1" align="center">
                <img id="modul_onoff_image_{$wert.module_id}" width="13px" height="13px" src="{$www_core_tpl_root}/images/modules/{if $wert.enabled == 1}module-active.gif{else}module-inactive.gif{/if}">
            </td>

            <td class="cell2" align="center">
                <b>{$wert.title} </b> (#{$wert.module_id})<br />
                <img id="modul_onoff_image_{$wert.module_id}" width="100px" height="100px" src="{$www_core_tpl_root}/images/modules/{$wert.image_name}">
                <small>Version: xy</small>
            </td>

            <td class="cell1">

                <div class="tab-pane" id="{$wert.name}_tabs">
                <script type="text/javascript">tp1 = new WebFXTabPane( document.getElementById( "{$wert.name}_tabs" ) );</script>


                {* #### MODULES - GENERALS #### *}


                <div class="tab-page" id="{$wert.name}_generals">
                   <h2 class="tab">{translate}General{/translate}</h2>
                   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_generals" ) );</script>

                    <table class="tableedit" id="table_for_modules_{$wert.module_id}" cellpadding="2" cellspacing="2" border="0">

                    {* Content of $content.generals = Title, Author, Description, Homepage *}
                    {foreach key=key item=item from=$content.generals}

                    <tr>
                        <td width="40"><b>{translate}{$key}:{/translate}</b></td>
                        <td class="editcell" id="{$wert.module_id}_{$wert.name}_{$item}">{$wert.$item}</td>
                    </tr>
                    {/foreach}

                    </table>
                    <script type="text/javascript">new TableGrid('table_for_modules_{$wert.module_id}', '2', 'index.php?mod=admin&sub=modules&action=ajaxupdate_modules');</script>
                </div>


                {* #### MODULES - DETAILS #### *}


             <div class="tab-page" id="{$wert.name}_details">
                <h2 class="tab"{translate}>Moduledetails{/translate}</h2>
                   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_details" ) );</script>

                    <table class="tableedit" id="details_table_{$wert.module_id}" cellpadding="2" cellspacing="2" border="0">

                    {foreach key=key item=item from=$content.more}
                        <tr>
                            <td width="40"><b>{translate}{$key}:{/translate}</b></td>
                            <td class="editcell" id="{$wert.module_id}_{$wert.name}_{$item}">{$wert.$item}</td>
                        </tr>
                    {/foreach}

                    </table>
                    <script type="text/javascript">new TableGrid('details_table_{$wert.module_id}', '2', 'index.php?mod=admin&sub=modules&action=ajaxupdate_modules');</script>
                </div>


                {* #### MODULES - SUBMODULES #### *}


                <div class="tab-page" id="{$wert.name}_subs">
                   <h2 class="tab">{translate}Submodules{/translate}</h2>
                   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_subs" ) );</script>

                   <table cellpadding="2" cellspacing="2" border="0" width="100%">

                    <tr>
                        <td><b>{translate}Submodules:{/translate}</b></td>
                        <td>

                           {if is_array($wert.subs)} {* #### SUBMODULES FOUND #### *}

                            {* #### Debug of Submodules Array #### {$wert.subs|@var_dump}*}

                            {foreach key=key item=item from=$wert.subs}

                            <table class="tableedit" id="submodules_table_{$wert.module_id}_{$item.submodule_id}" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td width="40"><b>Name</b> (#{$item.submodule_id}) :</td><td class="editcell">{$key}</td>
                            </tr>
                            <tr>
                                <td width="40"><b>File:</b></td><td class="editcell">{$item.file_name}</td>
                            </tr>
                            <tr>
                                <td width="40"><b>Class:</b></td><td class="editcell">{$item.class_name}</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    {$wert.module_id}_sub_{$item.submodule_id} - Delete submodule: {$key} (#{$item.submodule_id}) from whitelist
                                </td>
                            </tr>

                            </table>
                            <script type="text/javascript">new TableGrid('submodules_table_{$wert.module_id}_{$item.submodule_id}', '2', 'index.php?mod=admin&sub=modules&action=ajaxupdate_submodules');</script>

                            {/foreach}

                            {else} {* #### NO SUBMODULES FOUND #### *}

                                {translate}No submodules.{/translate}

                            {/if}

                           <br> {$wert.module_id}_subs Add a submodule</td>

                        </td>
                    </tr>

                    </table>
                </div>

                {* #### MODULES - CONFIG #### *}


             <div class="tab-page" id="{$wert.name}_config">
                <h2 class="tab"{translate}>Config{/translate}</h2>
                   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_config" ) );</script>

                    <table class="tableedit" id="details_table_{$wert.module_id}" cellpadding="2" cellspacing="2" border="0">

                    {foreach key=key item=item from=$content.more}
                        <tr>
                            <td width="40"><b>{translate}{$key}:{/translate}</b> todo config settings per module</td>
                            <td class="editcell" id="{$wert.module_id}_{$wert.name}_{$item}">{$wert.$item}</td>
                        </tr>
                    {/foreach}

                    </table>
                    <script type="text/javascript">new TableGrid('details_table_{$wert.module_id}', '2', 'index.php?mod=admin&sub=modules&action=ajaxupdate_modules');</script>
                </div>

            </td>

            <td class="cell2" align="center">
                {if $wert.enabled==1}
                <p align="center">
                    <input class="ButtonOrange" type="submit" value="{translate}Disable{/translate}" name="submit" />
                </p>
                {else}
                <p align="center">
                    <input class="ButtonGreen" type="submit" value="{translate}Enable{/translate}" name="submit" />
                </p>
                {/if}
            </td>

            <td class="cell1" align="center">
                <p align="center">
                    <form action="index.php?mod=admin&sub=modules&action=uninstall&module_id={$wert.module_id}&folder_name={$wert.folder_name}" method="post">
                        <input type="hidden" name="module_name" value="{$wert.title}" />
                        <input class="ButtonRed" type="submit" value="{translate}Uninstall{/translate}" name="submit" />
                    </form>
                </p>
            </td>

        </tr>
        {/foreach}
        </table>

   </div>

   {* #### TAB PAGE - CORE MODULES | IN WHITELIST #### *}

   <div class="tab-page"><h2 class="tab">Core</h2>

      <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td class="td_header" colspan="5">   {translate}Core modules{/translate}  </td>
        </tr>
        <tr>
            <td class="td_header_small" width="15px">                                                   </td>
            <td class="td_header_small" width="120px">              {translate}Title{/translate}        </td>
            <td class="td_header_small" width="80%">                {translate}Information{/translate}  </td>
            <td class="td_header_small" width="5%" align="center">  {translate}Enabled{/translate}      </td>
            <td class="td_header_small" width="5%" align="center">  {translate}Uninstall{/translate}       </td>
        </tr>

        {foreach key=schluessel item=wert from=$content.whitelisted.core}

        <tr>
            <input type="hidden" name="ids[]" value="{$wert.module_id}">
            <td class="cell1" align="center">
                <img id="modul_onoff_image_{$wert.module_id}" width="13px" height="13px" src="{$www_core_tpl_root}/images/modules/{if $wert.enabled == 1}module-active.gif{else}module-inactive.gif{/if}"
            </td>


            <td class="cell2" align="center">
                <b>{$wert.title} </b> (#{$wert.module_id})<br />
                <img width="100px" height="100px" src="{$www_core_tpl_root}/images/modules/{$wert.image_name}">
                <small>Version: xy</small>
            </td>

            <td class="cell1">

                <div class="tab-pane" id="{$wert.name}_tabs">
                <script type="text/javascript">tp1 = new WebFXTabPane( document.getElementById( "{$wert.name}_tabs" ) );</script>

                {* #### MODULES - GENERALS #### *}

                <div class="tab-page" id="{$wert.name}_generals">
                   <h2 class="tab">{translate}General{/translate}</h2>
                   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_generals" ) );</script>

                    <table class="tableedit" id="table_{$wert.module_id}" cellpadding="2" cellspacing="2" border="0">

                    {* Content of $content.generals = Title, Author, Description, Homepage *}
                    {foreach key=key item=item from=$content.generals}
                        <tr>
                        <td width="40"><b>{translate}{$key}:{/translate}</b></td>
                        <td class="editcell" id="{$wert.module_id}_{$wert.name}_{$item}">{$wert.$item}</td>
                        </tr>
                    {/foreach}

                    </table>
                    <script type="text/javascript">new TableGrid('table_{$wert.module_id}', '2', 'index.php?mod=admin&sub=modules&action=ajaxupdate_modules');</script>
                </div>

                {* #### MODULES - DETAILS #### *}

                <div class="tab-page" id="{$wert.name}_details">
                   <h2 class="tab"{translate}>Moduledetails{/translate}</h2>
                   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_details" ) );</script>

                    <table class="tableedit" id="details_table_{$wert.module_id}" cellpadding="2" cellspacing="2" border="0">

                    {foreach key=key item=item from=$content.more}
                        <tr>
                            <td width="40"><b>{translate}{$key}:{/translate}</b></td>
                            <td class="editcell" id="{$wert.module_id}_{$wert.name}_{$item}">{$wert.$item}</td>

                        </tr>
                    {/foreach}

                    </table>
                    <script type="text/javascript">new TableGrid('details_table_{$wert.module_id}', '2', 'index.php?mod=admin&sub=modules&action=ajaxupdate_modules');</script>
                </div>


                {* #### MODULES - SUBMODULES  #### *}


                <div class="tab-page" id="{$wert.name}_subs">
                   <h2 class="tab">{translate}Submodules{/translate}</h2>
                   <script type="text/javascript">tp1.addTabPage( document.getElementById( "{$wert.name}_subs" ) );</script>

                   <table cellpadding="2" cellspacing="2" border="0">
                    <tr>
                        <td><b>{translate}Submodules:{/translate}</b></td>
                        <td>

                           {if is_array($wert.subs)} {* #### SUBMODULES FOUND #### *}

                            {* #### Debug of Submodules Array #### {$wert.subs|@var_dump} *}

                            {foreach key=key item=item from=$wert.subs}

                            <table class="tableedit" id="submodules_table_{$wert.module_id}_{$item.submodule_id}" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td width="40"><b>Name</b> (#{$item.submodule_id}) :</td>
                                <td class="editcell">{$key}</td>
                            </tr>
                            <tr>
                                <td width="40"><b>File:</b></td><td class="editcell">{$item.file_name}</td>
                            </tr>
                            <tr>
                                <td width="40"><b>Class:</b></td><td class="editcell">{$item.class_name}</td>
                            </tr>
                            <tr>
                                <td colspan="2">{$wert.module_id}_sub_{$item.submodule_id} - Delete submodule: {$key} (#{$item.submodule_id}) from whitelist</a>
                                </td>
                            </tr>
                            <tr>
                            <td colspan="3">&nbsp;</td>
                            </tr>
                            </table>
                            <script type="text/javascript">new TableGrid('submodules_table_{$wert.module_id}_{$item.submodule_id}', '2', 'index.php?mod=admin&sub=modules&action=ajaxupdate_submodules');</script>

                            {/foreach}

                            {else} {* #### NO SUBMODULES FOUND #### *}

                            {translate}No submodules.{/translate}

                            {/if}

                            <br> {$wert.module_id}_subs - Add a submodule

                        </td>
                    </tr>
                    </table>
                    <script type="text/javascript">new TableGrid('details_table_{$wert.module_id}', '2', 'index.php?mod=admin&sub=modules&action=ajaxupdate_modulesdetails');</script>

                </div>

            </td>

            <td class="cell2" align="center">
                {if $wert.enabled==1}
                <p align="center">
                    <input id="modul_button_`$wert.module_id`" class="ButtonOrange" type="submit"
                     onclick="{ajax_update url='index.php?mod=admin&sub=modules&action=ajax_onoffswitch'
                               update_id="modul_id_`$wert.module_id`"
                               params="server_id=`$server.server_id`"

                               callback="new Effect.Puff(\'modul_onoff_image_`$wert.module_id`\')"
                              }" value="{translate}Disable{/translate}" name="submit" />

                {* new `$(this).Classnames.set = 'ButtonGreen'` *}

                </p>
                {else}
                <p align="center">
                    <input id=" "class="ButtonGreen" type="submit"
                    onclick="{ajax_update url='index.php?mod=admin&sub=modules&action=ajax_onoffswitch'
                              update_id="server_details_`$server.server_id`"
                              params="server_id=`$server.server_id`"
                              callback="new Effect.Appear(\'modul_onoff_image_`$wert.module_id`\')"
                             }" value="{translate}Enable{/translate}" name="submit" />
                </p>
                {/if}
            </td>

            <td class="cell1" align="center">
                <p align="center">
                    <form action="index.php?mod=admin&sub=modules&action=uninstall&module_id={$wert.module_id}" method="post">
                        <input type="hidden" name="module_name" value="{$wert.title}" />
                        <input class="ButtonRed" type="submit" value="{translate}Uninstall{/translate}" name="submit" />
                    </form>
                </p>
            </td>

        </tr>
        {/foreach}
        </table>
   </div>

</div> <!-- tab pane 2 closed -->

{* #### Init TabPane #### *}
<script type="text/javascript">setupAllTabs();</script>
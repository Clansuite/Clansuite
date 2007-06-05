{doc_raw}
    {* Prototype + Scriptaculous + Smarty_Ajax + Xilinus*}
    <script src="{$www_root_tpl_core}/javascript/prototype/prototype.js" type="text/javascript"></script>
  	<script type="text/javascript" src="{$www_root_tpl_core}/javascript/scriptaculous/effects.js"></script>
  	<script type="text/javascript" src="{$www_root_tpl_core}/javascript/xilinus/window.js"></script>
  	<script type="text/javascript" src="{$www_root_tpl_core}/javascript/xilinus/window_effects.js"></script>
  	<script type="text/javascript" src="{$www_root_tpl_core}/javascript/smarty_ajax.js"></script>

  	{* Xilinus Themes *}
  	<link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/javascript/xilinus/themes/alphacube.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/javascript/xilinus/themes/alert.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/javascript/xilinus/themes/default.css" />
{/doc_raw}

{literal}
    <script type="text/javascript">
        function clip_area(id)
        {
            if( !this.old_area )
            {
                document.getElementById('select_text').style.display = 'none'
                var old_area = '';
            }
            if( this.old_area )
            {
                //new Effect.Fade( this.old_area );
                //new Effect.Scale(this.old_area, 0, {scaleContent: false, scaleFrom: $(this.old_area).offsetHeight, scaleMode: { originalHeight: 0 } });
                document.getElementById(this.old_area).style.display = 'none';
            }

            if( document.getElementById(id).style.display == 'none' )
            {
                document.getElementById(id).style.display = 'block';
                this.old_area = id;
                //new Effect.Appear( id );
                if( $('table_' + id).offsetHeight < 400 )
                    height = 400;
                else
                    height = $('table_' + id).offsetHeight;

                new Effect.Scale(id, 100, {scaleContent: false, scaleFrom: 0, scaleMode: { originalHeight: height }});
            }
            else
            {
                //new Effect.Fade( id );
                new Effect.Scale(id, 0, {scaleContent: false, scaleFrom: $('table_' + id).offsetHeight, scaleMode: { originalHeight: 0 } });
                //document.getElementById(id).style.display = 'none'
            }
        }
    </script>
{/literal}

{* Debuganzeige, wenn DEBUG = 1 | {$permissions_data|@var_dump}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$permissions_data} {/if}*}
<table align="center" cellpadding="0" cellspacing="0" border="0">
    <tr class="tr_header">
        <td align="center" width="100">{translate}Areas{/translate}</td>
        <td>{translate}Rights{/translate}</td>
    </tr>
    <tr class="tr_row1">
        <td align="center">
            <div id="areas" style="clear: both">
            {foreach key=area_id item=area_array from=$areas}
                <input type="button" value="{$area_array.name}" onclick="clip_area('area_{$area_array.area_id}')" class="ButtonYellow" style="width: 80px;" /><br />
            {/foreach}
            {if count($unassigned) > 0}
                <input type="button" value="{translate}Unassigned{/translate}" onclick="clip_area('area_unassigned')" class="ButtonRed" style="width: 80px;" />
            {/if}
            </div>
        </td>
        <td align="center" width="700" style="height: 100%; padding: 0px;">
            <div style="height: 100%" id="select_text">
            {translate}Select the area on the left side{/translate}
            </div>
            {foreach key=area_id item=area_array from=$areas}
            <form action="index.php?mod=admin&amp;sub=permissions&amp;action=delete_right" method="post" accept-charset="UTF-8">
                <div style="height: 1px; display: none; overflow: hidden" id="area_{$area_array.area_id}">
                <table cellpadding="0" cellspacing="0" border="0" id="table_area_{$area_array.area_id}">
                    <tr class="tr_header_small">
                        <td width="10">{translate}ID{/translate}</td>
                        <td>{translate}Right Name{/translate}</td>
                        <td width="40%">{translate}Description{/translate}</td>
                        <td width="200">{translate}Actions{/translate}</td>
                        <td width="1%"> {translate}Del{/translate}</td>
                    </tr>
                    {foreach key=right_name item=right_array from=$rights.$area_id}
                    <tr class="{cycle values="tr_row1, tr_row2"}">
                        <td style="vertical-align: middle"><input type="hidden" name="ids[]" value="{$right_array.right_id}" />{$right_array.right_id}</td>
                        <td style="vertical-align: middle" align="left"><b>{if substr($right_array.name, 0, 3) == 'cc_'}<span style="color: red">{$right_array.name}</span>{else}{$right_array.name}{/if}</b></td>
                        <td style="vertical-align: middle">{$right_array.description}</td>
                        <td align="center">
                            <input type="button" class="ButtonGreen" onclick='{literal}Dialog.info({url: "index.php?mod=admin&amp;sub=permissions&amp;action=edit_right&amp;right_id={/literal}{$right_array.right_id}{literal}", options: {method: "get"}}, {className: "alphacube", width:300, height: 130});{/literal}' value="{translate}Edit{/translate}" />
                            <input type="button" class="ButtonYellow" onclick="self.location.href='index.php?mod=admin&amp;sub=permissions&amp;action=lookup_users&amp;right_id={$right_array.right_id}'" value="{translate}Lookup users{/translate}" />
                        </td>
                        <td align="center" style="vertical-align: middle">
                            <input type="hidden" name="ids[]" value="{$right_array.right_id}" />
                            <input name="delete[]" type="checkbox" value="{$right_array.right_id}" />
                        </td>
                    </tr>
                    {/foreach}
                    <tr class="tr_row1">
                        <td align="right" colspan="5">
                            <input type="button" onclick='{literal}Dialog.info({url: "index.php?mod=admin&amp;sub=permissions&amp;action=create_right&amp;area_id={/literal}{$area_id}{literal}", options: {method: "get"}}, {className: "alphacube", width:300, height: 130});{/literal}' class="ButtonGreen" value="{translate}Create right{/translate}" />
                            <input type="button" onclick='{literal}Dialog.info({url: "index.php?mod=admin&amp;sub=permissions&amp;action=edit_area&amp;area_id={/literal}{$area_id}{literal}", options: {method: "get"}}, {className: "alphacube", width:300, height: 130});{/literal}' class="ButtonGreen" value="{translate}Edit area{/translate}" />
                            <input type="button" onclick="self.location.href='index.php?mod=admin&amp;sub=permissions&amp;action=delete_area&amp;area_id={$area_id}'" class="ButtonRed" value="{translate}Delete area{/translate}" />
                            <input type="submit" name="submit" class="ButtonRed" value="{translate}Delete selected Permissions{/translate}" />
                            <input type="reset" name="submit" class="ButtonGrey" value="{translate}Reset{/translate}" />
                        </td>
                    </tr>
                </table>
                </div>
            </form>
            {/foreach}
            <form action="index.php?mod=admin&amp;sub=permissions&amp;action=delete_right" method="post" accept-charset="UTF-8">
                <table style="display: none;" id="area_unassigned" cellpadding="0" cellspacing="0" border="0">
                    <tr class="tr_header_small">
                        <td width="100">{translate}Right ID{/translate}</td>
                        <td width="100">{translate}Right Name{/translate}</td>
                        <td width="200">{translate}Description{/translate}</td>
                        <td width="200">{translate}Actions{/translate}</td>
                        <td width="5%"> {translate}Delete{/translate}</td>
                    </tr>
                    {foreach key=right_name item=right_array from=$unassigned}
                    <tr class="{cycle values="tr_row1, tr_row2"}">
                        <td><input type="hidden" name="ids[]" value="{$right_array.right_id}" />{$right_array.right_id}</td>
                        <td>{$right_array.name}</td>
                        <td>{$right_array.description}</td>
                        <td align="center">
                            <input onclick='{literal}Dialog.info({url: "index.php?mod=admin&amp;sub=permissions&amp;action=edit_rght&amp;right_id={/literal}{$right_array.right_id}{literal}", options: {method: "get"}}, {className: "alphacube", width:300, height: 130});{/literal}' type="Button" class="ButtonGreen" value="{translate}Edit{/translate}" />
                            <input onclick="self.location.href='index.php?mod=admin&amp;sub=permissions&amp;action=lookup_users&amp;right_id={$right_array.right_id}'" type="Button" class="ButtonYellow" value="{translate}Lookup users{/translate}" />
                        </td>
                        <td align="center">
                            <input type="hidden" name="ids[]" value="{$right_array.right_id}">
                            <input name="delete[]" type="checkbox" value="{$right_array.right_id}">
                        </td>
                    </tr>
                    {/foreach}
                    <tr class="tr_row1">
                        <td align="right" colspan="5">
                            <input type="submit" name="submit" class="ButtonRed" value="{translate}Delete selected right(s){/translate}" />
                            <input type="reset" name="submit" class="ButtonGrey" value="{translate}Reset{/translate}" />
                        </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
    <tr class="tr_row1">
        <td colspan="2" align="right">
            <input onclick='{literal}Dialog.info({url: "index.php?mod=admin&amp;sub=permissions&amp;action=create_area", options: {method: "get"}}, {className: "alphacube", width:300, height: 130});{/literal}' type="button" class="ButtonGreen" value="{translate}Create new area{/translate}" />
            <input onclick='{literal}Dialog.info({url: "index.php?mod=admin&amp;sub=permissions&amp;action=create_right", options: {method: "get"}}, {className: "alphacube", width:300, height: 130});{/literal}' type="button" class="ButtonGreen" value="{translate}Create new right{/translate}" />
        </td>
    </tr>
</table>
{doc_raw}
<script src="{$www_core_tpl_root}/javascript/prototype.js" type="text/javascript"></script>
<script src="{$www_core_tpl_root}/javascript/scriptaculous.js" type="text/javascript"></script>
<script src="{$www_core_tpl_root}/javascript/smarty_ajax.js" type="text/javascript"></script>
{/doc_raw}

<script type="text/javascript">
{literal}
var Serverform = {

  params: function() {
    return { gametype: $F("gametype"),
             ip: $F("ip"),
             port: $F("gametype") }
  }
}
{/literal}
</script>

{* Debuganzeige, wenn DEBUG = 1 |  {$servers|@var_dump}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$servers} {/if} *}

  <form action="index.php?mod=serverlist&sub=admin&action=delete" method="post">

    <table cellpadding="0" cellspacing="0" border="0" width="700" align="center">
        <thead>
        	<tr class="tr_header">
        		<td align="center">{translate}ID{/translate}</td>
        		<td align="center">{translate}IP{/translate}</td>
        		<td align="center">{translate}Port{/translate}</td>
        		<td align="center">{translate}Name{/translate}</td>
        		<td align="center">{translate}Gametype{/translate}</td>
        		<td align="center">{translate}Country{/translate}</td>
        		<td align="center">{translate}Edit{/translate}</td>
        		<td align="center">{translate}Delete{/translate}</td>
        	</tr>

            {foreach key=key item=server from=$servers}
                <tr class="{cycle values="tr_row1,tr_row2"}">
                   <input type="hidden" name="ids[]" value="{$server.server_id}" />
                    <td align="center">{$server.server_id}</td>
                    <td align="center">{$server.ip}</td>
                    <td align="center">{$server.port}</td>
                    <td align="center">{$server.name}</td>
                    <td align="center">{$server.gametype}</td>
                    <td align="center">
                        {if $server.image_country==''}
                            <img src="{$www_core_tpl_root}/images/empty.png" width="16" height="16" class="border3d">
                        {else}
                            <img src="{$www_core_tpl_root}/images/countries/{$server.image_country}" class="border3d">
                        {/if}
                    </td>
                    <td align="center"><input onclick="self.location.href='index.php?mod=serverlist&sub=admin&action=edit&id={$server.server_id}'" type="button" value="{translate}Edit{/translate}" class="ButtonGreen" /></td>
                    <td align="center"><input type="checkbox" name="delete[]" value="{$server.group_id}"></td>

                </tr>
            {/foreach}
            <tr>
                <td colspan="9" align="right" class="cell1">
                    <input onclick="javascript:clip_span('add_{$server.server_id}')" class="ButtonGreen" type="button" value="{translate}Enter new Server{/translate}" />
                    <input class="ButtonGrey" type="reset" name="reset" value="{translate}Reset{/translate}"/>
                    <input class="ButtonRed" type="submit" name="submit" value="{translate}Delete the selected servers{/translate}" />
                </td>
            </tr>
        </table>
</form>


<br />

<span id="span_add_{$server.server_id}" style="display: none;">

{ajax_form method='post' id='lookup_server' url='index.php?mod=serverlist&sub=admin'}

<table cellpadding="0" cellspacing="0" border="0" width="700" align="center">
        <thead>
        	<tr class="tr_header">
        		<td align="center">{translate}Server Engine / Gametype{/translate}</td>
        		<td align="center">{translate}IP{/translate} {translate}Port{/translate}</td>
        	</tr>
    <tr class="tr_row1">
        <td>
            <select name="gametype">
                  <option ></option>
                  <option value="steam" selected="selected">   Steam           </option>
                  <option value="q3a">              Quake 3 Arena   </option>

            </select>
        </td>
        <td>
            <input name="ip" value="85.214.27.93"/>
            <input name="port" value="27339"/>
        </td>
    </tr>
    <tr class="tr_row2">
        <td>
            Servername
        </td>
        <td>
            <div id="lookupresult">123</div>
        </td>
    </tr>
    <tr>
        <td colspan="9" align="right" class="cell1">
            <input class="ButtonOrange" value="{translate}Lookup Server{/translate}" id="send"
                   onclick="{* {ajax_call url="index.php?mod=serverlist&amp;sub=admin&amp;action=lookup_server"
                                       method="post"
                                       params="Serverform.params"
                                       } *}">
            <input class="ButtonGreen" type="button" value="{translate}Add a new Server{/translate}" />
            <input class="ButtonGrey" type="reset" name="reset" value="{translate}Reset{/translate}"/>
        </td>
    </tr>
</table>

{/ajax_form}

</span>
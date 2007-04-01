{* Debugausgabe des Arrays:  {$guestbook|@var_dump} {html_alt_table loop=$guestbook} *}
    {*<script type="text/javascript" src="{$www_core_tpl_root}/javascript/ajax_inplace_fader.js"></script>*}
{doc_raw}
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/prototype/prototype.js"> </script>
  	<script type="text/javascript" src="{$www_core_tpl_root}/javascript/scriptaculous/effects.js"> </script>
  	<script type="text/javascript" src="{$www_core_tpl_root}/javascript/xilinus/window.js"> </script>
  	<script type="text/javascript" src="{$www_core_tpl_root}/javascript/xilinus/window_effects.js"> </script>
  	<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/javascript/xilinus/themes/alphacube.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/javascript/xilinus/themes/alert.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/javascript/xilinus/themes/default.css" />
{/doc_raw}

<div class="admin_guestbook">

{include file="tools/paginate.tpl"}

<form action="index.php?mod=guestbook&amp;sub=admin&amp;action=delete" method="post">
    <table cellpadding="0" cellspacing="0" border="0" align="center" style="text-align:center">
        <tr class="tr_header">
         	<td width="1%">{columnsort html='ID'}</td>
       		<td>{columnsort html='Author/Nick'}</td>
       		<td>{columnsort selected_class="selected" html='Date/Added'}</td>
       		<td>{columnsort html='Email'}</td>
       		<td>{columnsort html='ICQ'}</td>
       		<td>{columnsort html='Website'}</td>
       		<td>{columnsort html='Town'}</td>
       		<td>{columnsort html='IP'}</td>
       		<td align="center" width="1%">{translate}Edit{/translate}</td>
       		<td align="center" width="1%">{translate}Delete{/translate}</td>
       	</tr>

        {foreach item=entry from=$guestbook}
            <tr class="{cycle values="tr_row1,tr_row2"}">
                <td rowspan="3">{$entry.gb_id}</td>
                <td style="font-weight:bold">{$entry.gb_nick}</td>
                <td>{$entry.gb_added}</td>
                <td>{$entry.gb_email}</td>
                <td>{$entry.gb_icq}</td>
                <td><a href="{$entry.gb_website}" target="_blank">{$entry.gb_website|truncate:15:"...":true}</a></td>
                <td>{$entry.gb_town}</td>
                <td>{$entry.gb_ip}</td>
                <td align="center" rowspan="2"><input class="ButtonGreen" type="button" value="{translate}Edit{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;sub=admin&amp;action=edit&amp;id={/literal}{$entry.gb_id}{literal}", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' /></td>
            
                <td align="center">
                            <input type="hidden" name="ids[]" value="{$entry.gb_id}" />
                            <input type="checkbox" name="delete[]" value="{$entry.gb_id}" />
                </td>
            </tr>
            <tr class="tr_row1">
                <td colspan="1"><b>{translate}Message:{/translate}</b></td>
                <td colspan="6">
                    {$entry.gb_text}
                </td>
            </tr>
            <tr class="tr_row1">
                <td colspan="1"><b>{translate}Comment:{/translate}</b></td>
                <td colspan="6">
                    <div id="gb_comment" height="1%">{if !empty($entry.gb_comment)}{$entry.gb_comment}{else}&nbsp;{/if}</div>
                </td>
            </tr>
            {*
            {literal}
            <script type="text/javascript">
            //<![CDATA[
            new Ajax.InPlaceEditorPlusFader('gb_comment',
                               'index.php?mod=guestbook&sub=admin&action=save_comment&id={/literal}{$entry.gb_id}{literal}',
                               {handleLineBreaks: false, highlightendcolor: '#3BC2F2', formClassName: 'ajax_input_text', okText: '{/literal}Save{literal}',cancelButton: true, cancelLink: false, cancelText:'{/literal}Cancel{literal}',okButtonClass: 'ButtonGreen',cancelButtonClass: 'ButtonGrey',rows:15,cols:35, loadTextURL:'index.php?mod=guestbook&sub=admin&action=get_comment&id={/literal}{$entry.gb_id}{literal}'}
                                );
            //]]>
            </script>
            {/literal}
            *}
        {/foreach}

        <tr>
            <td colspan="11" align="right" class="cell1">
                <input class="ButtonGrey" type="reset" name="reset" value="{translate}Reset{/translate}" />
                <input class="ButtonRed" type="submit" name="submit" value="{translate}Delete the selected entries{/translate}" />
            </td>
        </tr>
</table>
</form>

{include file="tools/paginate.tpl"}

</div>
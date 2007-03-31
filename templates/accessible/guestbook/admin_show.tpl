{* Debugausgabe des Arrays:  {$guestbook|@var_dump} {html_alt_table loop=$guestbook} *}

<script type="text/javascript" src="{$www_core_tpl_root}/javascript/ajax_inplace_fader.js"></script>

{include file="tools/paginate.tpl"}

<form action="index.php?mod=guestbook&amp;sub=admin&amp;action=delete" method="post">
    <table cellpadding="0" cellspacing="0" border="0" width="800px" align="center" style="text-align:center">
        <tr class="tr_header">
         	<td width="1%">{columnsort html='ID'}</td>
       		<td>{columnsort html='Author/Nick'}</td>
       		<td>{columnsort selected_class="selected" html='Date/Added'}</td>
       		<td>{columnsort html='Email'}</td>
       		<td>{columnsort html='ICQ'}</td>
       		<td>{columnsort html='Website'}</td>
       		<td>{columnsort html='Town'}</td>
       		<td>{columnsort html='Message'}</td>
       		<td>{columnsort html='IP'}</td>
       		<td width="1%">{translate}Delete{/translate}</td>
       	</tr>

        {foreach item=entry from=$guestbook}
            <tr class="{cycle values="tr_row1,tr_row2"}">
                <td rowspan="2">{$entry.gb_id}</td>
                <td style="font-weight:bold" rowspan="2">{$entry.gb_nick}</td>
                <td>{$entry.gb_added}</td>
                <td>{$entry.gb_email}</td>
                <td>{$entry.gb_icq}</td>
                <td>{$entry.gb_website}</td>
                <td>{$entry.gb_town}</td>
                <td>{$entry.gb_text}</td>
                <td>{$entry.gb_ip}</td>
                <td align="center">
                            <input type="hidden" name="ids[]" value="{$entry.gb_id}" />
                            <input type="checkbox" name="delete[]" value="{$entry.gb_id}" />
                </td>
            </tr>
            <tr class="tr_row1">
                <td colspan="2"><b>{translate}Comment:{/translate}</b></td>
                <td colspan="11" style="background: #3BC2F2;">
                    <div id="gb_comment">{if !empty($entry.gb_comment)}{$entry.gb_comment}{else}{translate}There is no comment. Click here to add one.{/translate}{/if}</div>
                </td>
            </tr>
            {* GB Comment AJAX *}
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
{foreach key=key item=item from=$info.helptext}
    {if $item!=''}
        {$item|nl2br}
    {/if}
{/foreach}
{if $info.helptext!=''}
    <p align="center">
        <input type="button" class="ButtonGreen" value="{translate}Edit helptext{/translate}" onClick="clip_id('helptext_add');" />
    </p>

    <div style="display: none;padding: 10px; text-align: center;" id="helptext_add">
        <textarea style="width: 100%; height: 200px;" name="info[helptext]" class="input_textarea" id="helptext">{$info.helptext}</textarea><br />
        <input type="button" class="ButtonGreen" value="{translate}Update{/translate}" onClick="return sendAjaxHelpRequest('helptext', '', 'index.php?mod=admin&sub=help&action=save_helptext')" />
    </div>
{else}
    <p align="center">
    {translate}There is no helptext assigned.{/translate}<br />
        <input type="button" class="ButtonGreen" value="{translate}Add helptext{/translate}" onClick="clip_id('helptext_add');" />
    </p>

    <div style="display: none;padding: 10px; text-align: center;" id="helptext_add">
        <textarea style="width: 100%; height: 200px;" name="info[helptext]" class="input_textarea" id="helptext"></textarea><br />
        <input type="button" class="ButtonGreen" value="{translate}Save{/translate}" onClick="return sendAjaxHelpRequest('helptext', '', 'index.php?mod=admin&sub=help&action=save_helptext')" />
    </div>
{/if}
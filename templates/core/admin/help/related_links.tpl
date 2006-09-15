{foreach key=key item=item from=$info.related_links}
    {if $item!=''}
        <a href="{$item|nl2br}">{$item|nl2br}</a><br />
    {/if}
{/foreach}
{if count($info.related_links)!=0}
    <p align="center">
        <input type="button" class="ButtonGreen" value="{translate}Edit links (each line a link){/translate}" onClick="clip_id('links_add');" />
    </p>

    <div style="display: none;padding: 10px; text-align: center;" id="links_add">
        <textarea style="width: 100%; height: 200px;" name="info[related_links]" class="input_textarea" id="related_links">{$info.orig_related_links}</textarea><br />
        <input type="button" class="ButtonGreen" value="{translate}Update{/translate}" onClick="return sendAjaxRequest('related_links', '', 'index.php?mod=admin&sub=help&action=save_related_links')" />
    </div>
{else}
    <p align="center">
    {translate}There are no links assigned.{/translate}<br />
        <input type="button" class="ButtonGreen" value="{translate}Add links (each line a link){/translate}" onClick="clip_id('links_add');" />
    </p>

    <div style="display: none;padding: 10px; text-align: center;" id="links_add">
        <textarea style="width: 100%; height: 200px;" name="info[related_links]" class="input_textarea" id="related_links"></textarea><br />
        <input type="button" class="ButtonGreen" value="{translate}Save{/translate}" onClick="return sendAjaxRequest('related_links', '', 'index.php?mod=admin&sub=help&action=save_related_links')" />
    </div>
{/if}
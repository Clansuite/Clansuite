<h1>{t}Guestbook{/t}</h1>
{* Debugausgabe des Arrays: {$guestbook|@var_dump} 
{html_alt_table loop=$guestbook}*}

{*
    {foreach item=entry from=$guestbook}
        {$entry.gb_id}
        {$entry.gb_added}
        {$entry.gb_nick} <a href='index.php?mod=users&show'>{$entry.gb_nick}</a>
        {$entry.gb_email}
        {$entry.gb_icq}
        {$entry.gb_website}
        {$entry.gb_town}
        {$entry.gb_text}
        {$entry.gb_ip}
        {$entry.CsImage|@var_dump}
        {$entry.CsImage.type}
    {/foreach}
*}

<div class="guestbook">
    {pagination}
    <div class="options-top">
        <input class="ButtonGreen" type="button" value="{t}Add a guestbook entry{/t}" />
    </div>
    {foreach from=$guestbook item=entry key=key}
	<div class="gb">
        <div class="gbhead">
            <div class="author">
                {t}Autor{/t}
            </div>
            <div class="message">
                {t}Message{/t}
            </div>
        </div>
        <div class="gbleft">
            <span>{t}Name{/t}</span>: <span class="user-info">{$entry.gb_nick}</span><br />
            
            {$entry.CsImage.type}
            
            {if $entry.CsImage.type == 'url'}
                <img src="{$entry.location}" alt="{$entry.gb_nick}" class="the_pic" />
            {elseif $entry.CsImage.type == 'upload'}
                <a href="index.php?mod=account&amp;sub=profile&amp;action=show&amp;id={$entry.user_id.1}"><img src="index.php?mod=guestbook&amp;action=show_avatar&amp;id={$entry.gb_id}" alt="{$entry.gb_nick}"  class="the_pic" /></a>
            {else}
                <img src="{$www_root_themes_core}/images/no_avatar_small.jpg" alt="{t}No avatar{/t}: {$entry.gb_nick}"  class="the_pic" />
            {/if}

            <br />
            <span>{t}Date{/t}</span>: {t}{$entry.gb_added|date_format:"%A"}{/t}, {t}{$entry.gb_added|date_format:"%B"}{/t}{$entry.gb_added|date_format:" %e, %Y"}<br />
        </div>
        <div class="gbright">
            {$entry.gb_text}
            {if !empty($entry.gb_comment)}
            <fieldset>
                <legend>{t}Comment{/t}</legend>
                {$entry.gb_comment}
            </fieldset>
            {/if}
        </div>
        <div class="gbfooter">
        {* AJAX Needed *}
        {if ( isset($smarty.session.user.rights.permission_edit_gb) AND isset($smarty.session.user.rights.permission_access) )
        OR ($smarty.session.user.user_id == $entry.user_id) }
            {if $smarty.session.user.user_id == $entry.user_id.1}
            <input class="ButtonGreen" type="button" value="{t}Edit my entry{/t}" />
            {/if}
            {if isset($smarty.session.user.rights.permission_edit_gb) AND isset($smarty.session.user.rights.permission_access)}
            <input class="ButtonGreen" type="button" value="{t}Edit or add comment{/t}" />
            {/if}
        {/if}
        </div>
    </div>
{*
                <dl>
                    <dt>{t}Date{/t}: </dt>
                        <dd>{t}{$entry.gb_added|date_format:"%A"}{/t}, {t}{$entry.gb_added|date_format:"%B"}{/t}{$entry.gb_added|date_format:" %e, %Y"}</dd>
                    <dt>{t}eMail{/t}: </dt>
                        <dd><a href="mailto:{$entry.gb_email}">{$entry.gb_email}</a></dd>
                    <dt>ICQ: </dt>
                        <dd>{$entry.gb_icq}</dd>
                    <dt>{t}Website{/t}: </dt>
                        <dd>
                            {if empty($entry.gb_website)}
                                <a href="http://www.clansuite.com" target="_blank">{t}no website{/t} | powered by clansuite.com - free clan cms</a>
                            {else}
                                <a href="{$entry.gb_website}" target="_blank">{$entry.gb_website}</a>
                            {/if}
                        </dd>
                    <dt>{t}City{/t}: </dt>
                        <dd>{$entry.gb_town}</dd>
                    {if $smarty.session.user.rights.permission_edit_gb == '1' AND $smarty.session.user.rights.permission_access == '1'}
                    <dt>IP: </dt>
                        <dd>{$entry.gb_ip}</dd>
                    {/if}
                </dl>
*}
    {/foreach}
    <div class="options-bottom">
        <input class="ButtonGreen" type="button" value="{t}Add a guestbook entry{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
    </div>
    {pagination}
</div>

{$form}
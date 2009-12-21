<h1>{t}Guestbook{/t}</h1>
{* Debugausgabe des Arrays: {$guestbook|@var_dump} {html_alt_table loop=$guestbook} *}

{move_to target="pre_head_close"}
    <script src="{$www_root_themes_core}/javascript/prototype/prototype.js" type="text/javascript"></script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/scriptaculous/effects.js"> </script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/xilinus/window.js"> </script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/xilinus/window_effects.js"> </script>
{/move_to}

<div class="guestbook">
    {pagination}
    <div class="options-top">
        <input type="button" value="{t}Add a guestbook entry{/t}" onclick='Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});' />
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
            {if $entry.CsImage.type == 'url'}
            <img src="{$entry.location}" alt="{$entry.gb_nick}" class="the_pic" />
            {elseif $entry.CsImage.type == 'upload'}
            <a href="index.php?mod=account&amp;sub=profile&amp;action=show&amp;id={$entry.user_id.1}"><img src="index.php?mod=guestbook&amp;action=show_avatar&amp;id={$entry.gb_id}" alt="{$entry.gb_nick}"  class="the_pic" /></a>
            {else}
            <img src="{$www_root_theme}/images/no_avatar_small.jpg" alt="{t}No avatar{/t}: {$entry.gb_nick}"  class="the_pic" />
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
        {if (isset($smarty.session.user.rights.permission_edit_gb) AND isset($smarty.session.user.rights.permission_access)) OR ($smarty.session.user.user_id == $entry.user_id)}
            {if $smarty.session.user.user_id == $entry.user_id.1}
            <input type="button" value="{t}Edit my entry{/t}" onclick='Dialog.info({url: "index.php?mod=guestbook&amp;action=edit&amp;id={$entry.gb_id}", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});' />
            {/if}
            {if isset($smarty.session.user.rights.permission_edit_gb) AND isset($smarty.session.user.rights.permission_access)}
            <input type="button" value="{t}Edit or add comment{/t}" onclick='Dialog.info({url: "index.php?mod=guestbook&amp;sub=admin&amp;action=edit&amp;id={$entry.gb_id}&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});' />
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
        <input type="button" value="{t}Add a guestbook entry{/t}" onclick='Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});' />
    </div>
    {pagination}
</div>
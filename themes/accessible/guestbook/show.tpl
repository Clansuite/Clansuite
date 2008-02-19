<h1>{t}Guestbook{/t}</h1>
{* Debugausgabe des Arrays: {$guestbook|@var_dump} {html_alt_table loop=$guestbook} *}

{doc_raw}
    <script src="{$www_root_themes_core}/javascript/prototype/prototype.js" type="text/javascript"></script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/scriptaculous/effects.js"> </script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/xilinus/window.js"> </script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/xilinus/window_effects.js"> </script>
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/alphacube.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/alert.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/default.css" />
{/doc_raw}

<div class="guestbook">
    {include file="tools/paginate.tpl"}
    <div class="options-top">
        <input class="ButtonGreen" type="button" value="{t}Add a guestbook entry{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
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
            <span>{t}Name{/t}</span>: <span class="user-info">{$entry.nick}</span><br />
            {if $entry.type == url}
            <img src="{$entry.location}" alt="{$entry.nick}" class="the_pic" />
            {elseif $entry.type == upload}
            <a href="index.php?mod=account&amp;sub=profile&amp;action=show&amp;id={$entry.user_id.1}"><img src="index.php?mod=guestbook&amp;action=show_avatar&amp;id={$entry.gb_id}" alt="{$entry.nick}"  class="the_pic" /></a>
            {else}
            <img src="{$www_root_themes}/images/no_avatar_small.jpg" alt="{t}No avatar{/t}: {$entry.nick}"  class="the_pic" />
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
        {if ($smarty.session.user.rights.cc_edit_gb == 1 AND $smarty.session.user.rights.cc_access == 1) OR ($smarty.session.user.user_id == $entry.user_id) }
            {if $smarty.session.user.user_id == $entry.user_id.1}
            <input class="ButtonGreen" type="button" value="{t}Edit my entry{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;action=edit&amp;id={/literal}{$entry.gb_id}{literal}", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
            {/if}
            {if $smarty.session.user.rights.cc_edit_gb == 1 AND $smarty.session.user.rights.cc_access == 1}
            <input class="ButtonGreen" type="button" value="{t}Edit or add comment{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;sub=admin&amp;action=edit&amp;id={/literal}{$entry.gb_id}{literal}&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
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
                    {if $smarty.session.user.rights.cc_edit_gb == 1 AND $smarty.session.user.rights.cc_access == 1}
                    <dt>IP: </dt>
                        <dd>{$entry.gb_ip}</dd>
                    {/if}
                </dl>
*}
    {/foreach}
    <div class="options-bottom">
        <input class="ButtonGreen" type="button" value="{t}Add a guestbook entry{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
    </div>
    {include file="tools/paginate.tpl"}
</div>
<h1>{translate}Guestbook{/translate}</h1>
{* Debugausgabe des Arrays: {$guestbook|@var_dump} {html_alt_table loop=$guestbook} *}

{doc_raw}
    <script src="{$www_root_tpl_core}/javascript/prototype/prototype.js" type="text/javascript"></script>
    <script src="{$www_root_tpl_core}/javascript/lightbox/lightbox.js" type="text/javascript"></script>
  	<script type="text/javascript" src="{$www_root_tpl_core}/javascript/scriptaculous/effects.js"> </script>
  	<script type="text/javascript" src="{$www_root_tpl_core}/javascript/xilinus/window.js"> </script>
  	<script type="text/javascript" src="{$www_root_tpl_core}/javascript/xilinus/window_effects.js"> </script>
  	<link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/javascript/xilinus/themes/alphacube.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/javascript/xilinus/themes/alert.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/javascript/xilinus/themes/default.css" />
{/doc_raw}

<div class="guestbook">
    {include file="tools/paginate.tpl"}
    <div class="options-top">
        <input class="ButtonGreen" type="button" value="{translate}Add a guestbook entry{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
    </div>
    {foreach from=$guestbook item=entry key=key}
	<div class="gb">
        <div class="gbhead">
            <div class="author">
                {translate}Autor{/translate}
            </div>
            <div class="message">
                {translate}Message{/translate}
            </div>
        </div>
        <div class="gbleft">
            <span>{translate}Name{/translate}</span>: <span class="user-info">{$entry.nick}</span><br />
            {if $entry.type == url}
            <img src="{$entry.location}" alt="{$entry.nick}" class="the_pic" />
            {elseif $entry.type == upload}
            <a href="index.php?mod=account&amp;sub=profile&amp;action=show&amp;id={$entry.user_id.1}"><img src="index.php?mod=guestbook&amp;action=show_avatar&amp;id={$entry.gb_id}" alt="{$entry.nick}"  class="the_pic" /></a>
            {else}
            <img src="{$www_root_tpl}/images/no_avatar_small.jpg" alt="{translate}No avatar{/translate}: {$entry.nick}"  class="the_pic" />
            {/if}
            <br />
            <span>{translate}Date{/translate}</span>: {translate}{$entry.gb_added|date_format:"%A"}{/translate}, {translate}{$entry.gb_added|date_format:"%B"}{/translate}{$entry.gb_added|date_format:" %e, %Y"}<br />
        </div>
        <div class="gbright">
            {$entry.gb_text}
            {if !empty($entry.gb_comment)}
            <fieldset>
                <legend>{translate}Comment{/translate}</legend>
                {$entry.gb_comment}
            </fieldset>
            {/if}
        </div>
        <div class="gbfooter">
        {* AJAX Needed *}
        {if ($smarty.session.user.rights.cc_edit_gb == 1 AND $smarty.session.user.rights.cc_access == 1) OR ($smarty.session.user.user_id == $entry.user_id) }
            {if $smarty.session.user.user_id == $entry.user_id.1}
            <input class="ButtonGreen" type="button" value="{translate}Edit my entry{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;action=edit&amp;id={/literal}{$entry.gb_id}{literal}", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
            {/if}
            {if $smarty.session.user.rights.cc_edit_gb == 1 AND $smarty.session.user.rights.cc_access == 1}
            <input class="ButtonGreen" type="button" value="{translate}Edit or add comment{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;sub=admin&amp;action=edit&amp;id={/literal}{$entry.gb_id}{literal}&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
            {/if}
        {/if}
        </div>
    </div>
{*
                <dl>
                    <dt>{translate}Date{/translate}: </dt>
                        <dd>{translate}{$entry.gb_added|date_format:"%A"}{/translate}, {translate}{$entry.gb_added|date_format:"%B"}{/translate}{$entry.gb_added|date_format:" %e, %Y"}</dd>
                    <dt>{translate}eMail{/translate}: </dt>
                        <dd><a href="mailto:{$entry.gb_email}">{$entry.gb_email}</a></dd>
                    <dt>ICQ: </dt>
                        <dd>{$entry.gb_icq}</dd>
                    <dt>{translate}Website{/translate}: </dt>
                        <dd>
                            {if empty($entry.gb_website)}
                                <a href="http://www.clansuite.com" target="_blank">{translate}no website{/translate} | powered by clansuite.com - free clan cms</a>
                            {else}
                                <a href="{$entry.gb_website}" target="_blank">{$entry.gb_website}</a>
                            {/if}
                        </dd>
                    <dt>{translate}City{/translate}: </dt>
                        <dd>{$entry.gb_town}</dd>
                    {if $smarty.session.user.rights.cc_edit_gb == 1 AND $smarty.session.user.rights.cc_access == 1}
                    <dt>IP: </dt>
                        <dd>{$entry.gb_ip}</dd>
                    {/if}
                </dl>
*}
    {/foreach}
    <div class="options-bottom">
        <input class="ButtonGreen" type="button" value="{translate}Add a guestbook entry{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
    </div>
    {include file="tools/paginate.tpl"}
</div>
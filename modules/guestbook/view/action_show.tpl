{* Debugausgabe des Arrays: {html_alt_table loop=$guestbook} {$guestbook|@var_dump} *}

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
{/foreach}
*}         

<h1>{t}Guestbook{/t}</h1> 
     
<div class="guestbook">
    {pagination}
    <div class="options-top">
        <input class="ButtonGreen" type="button" value="{t}Add a guestbook entry{/t}" />
    </div>
    
    {foreach $guestbook  as entry}
    
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
            <span>{t}Name{/t}</span>: <span class="user-info">{$entry.gb_nick}</span>
            <br />            
            {gravatar email="`$entry.gb_email`"}            
            <br />
            <span>{t}Date{/t}</span>: {t}{$entry.gb_added|date_format:"%A"}{/t}, {t}{$entry.gb_added|date_format:"%B"}{/t}{$entry.gb_added|date_format:" %e, %Y"}<br />
        </div>
        <div class="gbright">
            
             <!-- GB ENTRY TEXT --> 
            {$entry.gb_text}
            
             <!-- Show Comment if one exists --> 
            {if !empty($entry.gb_comment)}
            <fieldset>
                <legend>{t}Comment{/t}</legend>
                {$entry.gb_comment}
            </fieldset>
            {/if}
            
        </div>
        <div class="gbfooter">
        {* AJAX Needed *} {*
        {if ( isset($smarty.session.user.rights.permission_edit_gb) AND isset($smarty.session.user.rights.permission_access) )
        OR ($smarty.session.user.user_id == $entry.user_id) }
            {if $smarty.session.user.user_id == $entry.user_id.1}
            <input class="ButtonGreen" type="button" value="{t}Edit my entry{/t}" />
            {/if}
            {if isset($smarty.session.user.rights.permission_edit_gb) AND isset($smarty.session.user.rights.permission_access)}
            <input class="ButtonGreen" type="button" value="{t}Edit or add comment{/t}" />
            {/if}
        {/if}   *}
        </div>

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

    {/foreach}
    <div class="options-bottom">
        <input class="ButtonGreen" type="button" value="{t}Add a guestbook entry{/t}" onclick='Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});' />
    </div>
    {pagination}
</div>
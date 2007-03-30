{* Debugoutout of Arrays:
{if $smarty.const.DEBUG eq "1"} Debug of Newsarchiv {html_alt_table loop=$newsarchiv}   {/if}
{$newscategories|@var_dump}
{$paginate|@var_dump}
*}

<style type="text/css">
    {literal}
    .selected { color:green; }
    {/literal}
    </style>


<table border="0" cellpadding="0" cellspacing="0" style="width:99%">
    <tr class="tr_header">
        <td colspan="3">News - Liste - New News - Settings</td>
    </tr>
    <tr class="tr_header_small">
        <td>
            <img src="symbols/crystal_clear/16/contents.png" style="height:16px;width:16px" alt="" /> Gesamt: 13
        </td>
        <td>
            {* display pagination info *}
            {paginate_prev text="&lt;&lt;"} {paginate_middle format="page"}  {paginate_next text="&gt;&gt;"}
        </td>
    </tr>
    <tr>
         <td colspan="2">Kategorie-Auswahl:

            <form method="post" name="news_list" action="/index.php?mod=news&amp;sub=admin&amp;action=show">
            <select name="cat_id" class="form">
                <option value="0">----</option>

                {foreach item=cats from=$newscategories}
                <option value="{$cats.cat_id}">{$cats.name}</option>
                {/foreach}

            </select>

             <input type="submit" name="submit" value="Anzeigen" class="form"/></form>
        </td>
    </tr>
</table>
<br/>

<table border="0" cellspacing="1" cellpadding="3" style="width:99%">
    <tr>
        <th>{columnsort html="Datum"}</th>
        <th>{columnsort selected_class="selected"
                        html='Title'}</th>
        <th>{columnsort html='Kategorie'}</th>
        <th>{columnsort html='Verfasser'}</th>
        <th>Draft</th>
        <th>Action</th>
    </tr>

    {foreach item=news from=$newsarchiv}
    <tr>
            <td>{$news.news_added}</td>
            <td>{$news.news_title}</td>
            <td>{$news.cat_name}</td>
            <td><a href='index.php?mod=users&id={$news.user_id}'>{$news.nick}</a></td>
            <td>published</td>
            <td>add edit</td>
    </tr>
    {/foreach}

</table>
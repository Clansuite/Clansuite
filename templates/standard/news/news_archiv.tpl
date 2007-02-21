{* Debugoutout of Arrays:  {$newsarchiv|@var_dump} {$newscategories|@var_dump} {$paginate|@var_dump}*}
  
<table border="1" cellpadding="0" cellspacing="1" style="width:99%">
    <tr>
        <td colspan="3">News - Liste</td>
    </tr>
    <tr>
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
            
            <form method="post" name="news_list" action="/index.php?mod=news&amp;action=archiv">
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

<table border="1" cellspacing="1" cellpadding="3" style="width:99%">
    <tr>
        <td>Datum</td>
        <td>Titel</td>
        <td>Kategorie</td>
        <td>Verfasser</td>
    </tr>
    
    {foreach item=news from=$newsarchiv}
    <tr>
        <td>{$news.news_added}</td>
        <td>{$news.news_title}</td>
        <td>{$news.cat_name}</td>
        <td><a href='index.php?mod=users&amp;id={$news.user_id}'>{$news.nick}</a></td>
    </tr>
    {/foreach}
    
</table>
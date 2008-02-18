{* Debugoutout of Arrays:  {$newsarchiv|@var_dump} {$newscategories|@var_dump} {$paginate|@var_dump}*}
<h1>Newsarchiv</h1>

<table>
    <tr>
		<th>
			<form method="post" action="index.php?mod=news&amp;action=archiv">
				<fieldset style="padding:5px">
					<label for="category">Kategorie-Auswahl:
					 <select name="cat_id" class="input_text">
                        <option value="0">-- {t}all{/t} --</option>
        
                        {foreach item=cats from=$newscategories}
                            <option value="{$cats.cat_id}" {if isset($smarty.post.cat_id) && $smarty.post.cat_id == $cats.cat_id} selected='selected'{/if}>{$cats.name|escape:html}</option>
                        {/foreach}        
                    </select>						
					</label>
					<input type="submit" name="submit" value="Anzeigen" class="button" />
				</fieldset>
			</form>
		</th>		
	</tr>
</table>
<br />
{include file="tools/paginate.tpl"}
<table>
	<tr>
		<th>Datum</th>
		<th>Titel</th>
		<th>Kategorie</th>
		<th>Verfasser</th>
	</tr>
    {foreach item=news from=$newsarchiv}
	<tr>
		<td>{t}{$news.news_added|date_format:"%A"}{/t}, {t}{$news.news_added|date_format:"%B"}{/t}{$news.news_added|date_format:" %e, %Y"}</td>
		<td>{$news.news_title}</td>
		<td>{$news.cat_name}</td>
		<td><a href='index.php?mod=users&amp;id={$news.user_id}'>{$news.nick}</a></td>
	</tr>
    {/foreach}
</table>

{include file="tools/paginate.tpl"}
{* Debugoutout of Arrays:  {$newsarchiv|@var_dump} {$newscategories|@var_dump} {$paginate|@var_dump}*}
<h1>News - Liste</h1>
<table>
	<tr>
		<th>Gesamt: 13</th>
		<th>
			<form method="post" action="index.php?mod=news&amp;action=archiv">
				<fieldset style="padding:5px">
					<label for="category">Kategorie-Auswahl:
						<select name="cat_id" id="category">
							<option value="0">----</option>
{foreach item=cats from=$newscategories}
							<option value="{$cats.cat_id}">{$cats.name}</option>
{/foreach}
						</select>
					</label>
					<input type="submit" name="submit" value="Anzeigen" class="button" />
				</fieldset>
			</form>
		</th>
		<th>
			{paginate_prev text="&lt;&lt;"} {paginate_middle format="page"}  {paginate_next text="&gt;&gt;"}
		</th>
	</tr>
</table>
<br />
<table>
	<tr>
		<th>Datum</th>
		<th>Titel</th>
		<th>Kategorie</th>
		<th>Verfasser</th>
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
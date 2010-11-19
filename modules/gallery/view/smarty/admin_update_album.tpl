
   {$smarty.session|var_dump}
   <hr>
   {$album|var_dump}
   <hr>
   {*{$pagination_links|var_dump}*}



<form action="index.php?mod=gallery&amp;sub=admin&amp;action=update_album" method="post" accept-charset="UTF-8">
<table border="0" cellspacing="1" cellpadding="3" style="width:99%">
	<tr>
		<td class="td_header_small"  colspan="2">{t}Update Album{/t}</td>
    </tr>
    <tr>
		<td class="cell2" width="15%">{t}Album Name{/t}</td>
    	<td class="cell1" style="padding: 3px">
    		<small>{t}The name of your album{/t}</small><br />
    		<input class="input_text" type="text" value="{$album.name}" name="album_name" size="50" maxlength="255" />
    	</td>
    </tr>
    <tr>
		<td class="cell2" width="15%">{t}Album Description{/t}</td>
    	<td class="cell1" style="padding: 3px">
    		<small>{t}The description of your album{/t}</small><br />
    		<input class="input_text" type="text" value="{$album.description}" name="album_description" size="75" maxlength="255" />
    	</td>
    </tr>
    <tr>
		<td class="cell2" width="15%">{t}Album Position{/t}</td>
    	<td class="cell1" style="padding: 3px">
    		<small>{t}The numerical order of your album{/t}</small><br />
    		<input class="input_text" type="text" value="{$album.position}" name="album_position" size="3" maxlength="3" />
    	</td>
    </tr>
    <tr>
		<td class="cell2" width="15%">{t}Album Picture{/t}</td>
    	<td class="cell1" style="padding: 3px">
    		<small>{t}The picture for this album{/t}</small><br />
    		<input class="input_text" type="file" value="{$album.thumb}" name="album_picture" readonly="readonly" size="50" maxlength="255" />
    	</td>
    </tr>
    <tr>
    	<td colspan="2">
    		<div style="text-align:center">
    		<input type="submit" class="ButtonGreen" value="{t}Save{/t}" name="submit" />
			</div>
		</td>
	</tr>
</table>
</form>
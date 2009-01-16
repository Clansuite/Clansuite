
   {$smarty.session|@var_dump}
   <hr>
   {$album|@var_dump}
   <hr>
   {$pagination_links|@var_dump}

<table border="0" cellspacing="1" cellpadding="3" style="width:99%">

    <caption class="td_header">Gallery</caption>

    <tr class="tr_row2">
         <td colspan="6" align="left">{include file='subnavi.tpl'}</td>
    </tr>
    <tr class="tr_header">
    	<td>Picture</td>
    	<td>Name</td>
    	<td>Description</td>
    	<td>Position</td>
    	<td>Update</td>
    	<td>Delete</td>
    </tr>
    {section name=index loop=$album}   
    <tr>
    	<td>{$album[index].thumb}</td>
    	<td>{$album[index].name}</td>
    	<td>{$album[index].description}</td>
    	<td>{$album[index].position}</td>
    	<td>    		
    		<div style="text-align:center">
    			<input type="submit" class="ButtonYellow" value="{t}Update{/t}" name="update" />
    			<input type="hidden" name="id" value="{$album[index].id}" />
			</div>
		</td>
		<td>    		
    		<div style="text-align:center">
    			<input type="submit" class="ButtonRed" value="{t}Delete{/t}" name="delete" />
    			<input type="hidden" name="id" value="{$album[index].id}" />
			</div>
		</td>
    </tr>
    {sectionelse}
    <tr>
    	<td colspan="6">No Entries</td>
    </tr>
    {/section}
</table>
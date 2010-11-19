
   {$smarty.session|var_dump}
   <hr>
   {$album|var_dump}
   <hr>
   {$pagination_links|var_dump}

{modulenavigation}
<div class="ModuleHeading">{t}Gallery - Administration{/t}</div>
<div class="ModuleHeadingSmall">{t}You can manage your Galleries.{/t}</div>
<table border="0" cellspacing="1" cellpadding="3" style="width:99%">

    <caption class="td_header">Gallery</caption>

    <tr class="tr_row2">
         <td align="left">{include file='subnavi.tpl'}</td>
    </tr>
</table>


<table border="0" cellspacing="1" cellpadding="3" style="width:99%">
	<tr class="tr_header">
    	<th>Picture</th>
    	<th>Name</th>
    	<th>Description</th>
    	<th>Position</th>
    	<th colspan="2">Action</th>
    </tr>
    
    {section name=index loop=$album}   
    
    <tr>
    	<td>{$album[index].thumb}</td>
    	<td>{$album[index].name}</td>
    	<td>{$album[index].description}</td>
    	<td>{$album[index].position}</td>
    	<td>    		
    	
    		<div style="text-align:center">
    		<form action="index.php?mod=gallery&amp;sub=admin&amp;action=update_album" method="post" accept-charset="UTF-8">
    		<input type="submit" class="ButtonYellow" value="{t}Update{/t}" name="" />
    		<input type="hidden" name="id" value="{$album[index].id}" />
			</form>
			</div>
		</td>
		<td>    		
    		<div style="text-align:center">
    		<form action="index.php?mod=gallery&amp;sub=admin&amp;action=delete_album" method="post" accept-charset="UTF-8">
    		<input type="submit" class="ButtonRed" value="{t}Delete{/t}" name="" />
    		<input type="hidden" name="id" value="{$album[index].id}" />
			</form>
			</div>
		</td>
    </tr>
    {sectionelse}
    <tr>
    	<td colspan="6">No Entries</td>
    </tr>
    {/section}
</table>
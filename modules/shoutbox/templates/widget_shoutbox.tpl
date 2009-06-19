{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   
*} {$shoutbox_widget|@var_dump}

<!-- Start Widget Shoutbox from Module Shoutbox //-->

<div class="shoutbox_widget" id="widget_shoutbox" width="100%">

    <h2 class="td_header">{t}Next Matches{/t}</h2>

 	<table class="shoutbox">
		<tr>
		{*
		{foreach item=shout from=$shoutbox}
    		<td></td>
    		<td></td>
    		<td></td>
    		<td></td>
        {/foreach}
        *}
  		</tr>
	</table>

</div>
<!-- End Widget Shoutbox from Module Shoutbox //-->
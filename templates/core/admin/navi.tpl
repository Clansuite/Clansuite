<!-- CATEGORIES -->
<table border="0" cellspacing="5" cellpadding="5">
<tr class="categories">
	<td>
		<a href="/index.php?mod=admin" onMouseover="document.getElementById('sublinks').innerHTML = '<td>Additional Informations</td>'">Home</a>
	</td>
	<td>
		<a href="/index.php?mod=admin&sub=admin_configs" onMouseover="document.getElementById('sublinks').innerHTML = document.getElementById('configs_sublinks').innerHTML">
			General Configurations
		</a>
	</td>
	<td>
		<a href="/index.php?mod=admin&sub=admin_gb" onMouseover="document.getElementById('sublinks').innerHTML = document.getElementById('gb_sublinks').innerHTML">
			Guestbook
		</a>
	</td>
</tr>
</table>

<!-- SUBLINKS -->
<table border="0" cellpadding="5" cellspacing="5">
<tr class="sublinks" id="sublinks">
<td>Additional Informations</td>
</tr>

<!-- CONFIGS -->
<tr style="display:none" id="configs_sublinks">
	<td>
		<a href="/index.php?mod=admin&sub=admin_configs&action=edit" class="sub_link">Edit Configuration</a>
	</td>
	<td>
		<a href="/index.php?mod=admin&sub=admin_configs&action=upload" class="sub_link">Upload Configuration</a>
	</td>
</tr>

<!-- GUESTBOOK -->
<tr style="display:none" id="gb_sublinks">
	<td>
		<a href="/index.php?mod=admin&sub=admin_gb&action=edit" class="sub_link">Edit Guestbook</a>
	</td>
	<td>
		<a href="/index.php?mod=admin&sub=admin_gb&action=flush" class="sub_link">Flush Guestbook</a>
	</td>
</tr>

</table>

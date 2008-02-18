<form action="index.php?mod=account&amp;action=logout" method="post" accept-charset="UTF-8">
	<p>
		{t}Do you really want to logout?{/t}
	</p>
	<div class="form_bottom">
		<input type="hidden" name="confirm" value="1" />
		<input type="submit" name="submit" value="{t}Confirm{/t}" class="button" />
		<input type="button" value="{t}Abort{/t}" onclick="self.location.href='index.php'" class="button" />
	</div>
</form>
<form action="index.php?mod=account&amp;action=logout" method="post" accept-charset="UTF-8">
	<p>
		{translate}Do you really want to logout?{/translate}
	</p>
	<div class="form_bottom">
		<input type="hidden" name="confirm" value="1" />
		<input type="submit" name="submit" value="{translate}Confirm{/translate}" class="button" />
		<input type="button" value="{translate}Abort{/translate}" onclick="self.location.href='index.php'" class="button" />
	</div>
</form>
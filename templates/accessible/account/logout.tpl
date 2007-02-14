<form action="index.php?mod=account&amp;action=logout" method="post">
	<p>
		{translate}Do you really want to logout?{/translate}
	</p>
	<div class="form_bottom">
		<input type="hidden" name="confirm" value="1" />
		<input class="ButtonRed" type="submit" name="submit" value="{translate}Confirm{/translate}" />
		<input class="ButtonGreen" type="button" value="{translate}Abort{/translate}" onclick="self.location.href='index.php'" />
	</div>
</form>
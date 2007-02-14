<form action="index.php?mod=account&action=logout" method="post">
    <table style="width: 100%">
        <tr>
            <td colspan="2" align="center">
                <p>
                    {translate}Do you really want to logout?{/translate}
                </p>
                <p>
                    <input type="hidden" name="confirm" value="1" />
                    <input class="ButtonRed" type="submit" name="submit" value="{translate}Confirm{/translate}" />
                    <input class="ButtonGreen" type="button" value="{translate}Abort{/translate}" onClick="self.location.href='index.php'" />
                </p>
            </td>
        </tr>
    </table>
</form>
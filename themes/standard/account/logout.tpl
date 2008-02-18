<form action="index.php?mod=account&action=logout" method="post">
    <table style="width: 100%">
        <tr>
            <td colspan="2" align="center">
                <p>
                    {t}Do you really want to logout?{/t}
                </p>
                <p>
                    <input type="hidden" name="confirm" value="1" />
                    <input class="ButtonRed" type="submit" name="submit" value="{t}Confirm{/t}" />
                    <input class="ButtonGreen" type="button" value="{t}Abort{/t}" onclick="self.location.href='index.php'" />
                </p>
            </td>
        </tr>
    </table>
</form>
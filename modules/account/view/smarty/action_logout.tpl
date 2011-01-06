<form action="{$www_root}index.php?mod=account&action=logout" method="post">
    <table style="width: 100%">
        <tr>
            <td colspan="2" align="center">
                <p>
                    {t}Do you really want to logout?{/t}
                </p>
                <p>
                    <input type="hidden" name="confirm" value="1" />
                    <input type="submit" class="ButtonRed" name="submit" value="{t}Confirm{/t}" />
                    <input type="button" class="ButtonGreen" value="{t}Abort{/t}" onclick="self.location.href='{$www_root}index.php'" />
                </p>
            </td>
        </tr>
    </table>
</form>
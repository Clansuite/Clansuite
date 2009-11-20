<h2>{t}Logout{/t}</h2>

    <form action="index.php?mod=account&action=logout" method="post">
    <table style="width: 100%">
        <tr>
            <td colspan="2" align="center">
                {t}Do you really want to logout?{/t}
                <p>
                <input type="hidden" name="confirm" value="1">
                <input class="ButtonGrey" type="submit" name="submit" value="{t}Confirm{/t}">
                </p>
            </td>
        </tr>
    </table>
    </form>
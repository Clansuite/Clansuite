<h2>{translate}Logout{/translate}</h2>

    <form action="index.php?mod=account&action=logout" method="post">
    <table style="width: 100%">
        <tr>
            <td colspan="2" align="center">
                {translate}Do you really want to logout?{/translate}
                <p>
                <input type="hidden" name="confirm" value="1">
                <input class="input_submit" type="submit" name="submit" value="{translate}Confirm{/translate}">
                </p>
            </td>
        </tr>
    </table>
    </form>
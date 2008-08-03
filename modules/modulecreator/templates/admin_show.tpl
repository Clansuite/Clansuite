<div text-align="center">
    <form action="index.php?mod=modulecreator&sub=admin&action=create" method="POST">
        <table>
            <tr>
                <td>{t}Methodenname{/t}</td>
                <td><input type="text" value=""></input></td>
            </tr>
            <tr>
                <td>{t}Module Style{/t}</td>
                <td>
                    <select name="mc[type]" id="type" onchange="javascript:alert(this.options[this.selectedIndex].value)">
                        <option value="clean">{t}Clean module without any methods{/t}</option>
                        <option value="crud">{t}CRUD (Create, Read, Update, Delete) module{/t}</option>
                        <option value="user">{t}User defined class methods{/t}</option>
                    </select>
                </td>
            </tr>
            <tr style="display: none">
                <td>User Functions (speperate by commata)</td>
                <td><textarea name="m[user_methods]"></textarea></td>
            </tr>
        </table>
    </form>
</div>
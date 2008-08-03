{literal} 
<script type="text/javascript">
    window.addEvent('domready', function() {

        var allChecks = $$('.check_below');
        allChecks.each( function(check, i) {
            if( $(check.id + '_display') )
            {
                var checkSlide = new Fx.Slide(check.id + '_display', {
                    duration: 500,
                    transition: Fx.Transitions.Pow.easeOut
                });
                checkSlide.hide();
                check.addEvent('click', function(){

                    if( check.checked )
                    {
                        checkSlide.slideIn();
                    }
                    else
                    {
                        checkSlide.slideOut();
                    }

                });
            }
            else
            {
            
            }
        });
    }, 'javascript');
</script>
{/literal}
<div text-align="center">
    <form action="index.php?mod=modulecreator&sub=admin&action=create" method="POST">
        <table>
            <tr>
                <td>{t}Modulename{/t}</td>
                <td><input type="text" value=""></input></td>
            </tr>
            <tr>
                <td>Create frontend module?</td>
                <td>
                    <input type="checkbox" name="m[frontend]" id="frontend_module" class="check_below" value="1" />
                    <div id="frontend_module_display">
                        <b>{t}Method Names{/t}</b>
                        <div id="frontend_methods">
                            <input class="input_text" type="text" value="action_" name="m[frontend_methods][]" />
                            <img src="{$www_root_themes_core}/images/icons/delete.png" />
                                <img src="{$www_root_themes_core}/images/icons/add.png" />
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Create backend module?</td>
                <td><input type="checkbox" name="m[backend]" id="backend_module" class="check_below" value="1" /></td>
            </tr>
            <tr>
                <td>Create widget?</td>
                <td><input type="checkbox" name="m[widget]" id="widget_module" class="check_below" value="1" /></td>
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
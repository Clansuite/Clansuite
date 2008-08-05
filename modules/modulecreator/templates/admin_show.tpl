{literal} 
<script type="text/javascript">
    window.addEvent('domready', function() {

        var allChecks = $$('.check_below');
        allChecks.each( function(check, i) {
            if( $(check.id + '_display') )
            {

                
                // Alle methods sliders adden
                var checkSlide = new Fx.Slide(check.id + '_display', {
                    duration: 500,
                    transition: Fx.Transitions.Pow.easeOut,
                    wait: false
                });
                checkSlide.hide();
                
                // checkboxen mit slider verknüpfen
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
                
                // Alle add buttons mit click event belegen
                var methodsWrapper = $( check.id + '_wrapper' );
                var inputField = $( check.id + '_methods_input' );
                
                var methodsAdd = $( check.id + '_methods_add' );
                
                methodsAdd.addEvent('click', function() {
                    var inputCopy = inputField.clone();
                    var delEl = inputCopy.getElement('img');
                    delEl.addEvent('click', function() {
                        inputCopy.dispose();
                        checkSlide.slideIn();
                    });
                    inputCopy.inject(methodsWrapper);
                    checkSlide.slideIn();
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
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td class="cell2">{t}Modulename{/t}</td>
                <td class="cell1"><input type="text" value=""></input></td>
            </tr>
            <tr>
                <td class="cell2">Create frontend module?</td>
                <td class="cell1">
                    <input type="checkbox" name="m[frontend]" id="frontend_module" class="check_below" value="1" />
                    <div id="frontend_module_display">
                        <div style="padding: 10px">
                            <b>{t}Method Names{/t}</b>&nbsp;<img style="vertical-align: bottom;cursor: pointer;"  src="{$www_root_themes_core}/images/icons/add.png" id="frontend_module_methods_add" />
                            <div id="frontend_module_wrapper">
                                
                                <div id="frontend_module_methods_input">
                                    <input class="input_text" type="text" value="action_" name="m[frontend_methods][]" /><img src="{$www_root_themes_core}/images/icons/delete.png" id="frontend_module_methods_delete" style="vertical-align: bottom; cursor: pointer;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="cell2">Create backend module?</td>
                <td class="cell1"><input type="checkbox" name="m[backend]" id="backend_module" class="check_below" value="1" /></td>
            </tr>
            <tr>
                <td class="cell2">Create widget?</td>
                <td class="cell1"><input type="checkbox" name="m[widget]" id="widget_module" class="check_below" value="1" /></td>
            </tr>
            <tr>
                <td class="cell2">{t}Module Style{/t}</td>
                <td class="cell1">
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
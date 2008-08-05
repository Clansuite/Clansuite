{literal} 
<script type="text/javascript">
    window.addEvent('domready', function() {

        var addInputEvent = function()
        {
            var allInputs = $('modulcreator').getElements('input[pattern^=^]');
            allInputs.each( function(mInput, i) {
                mInput.addEvent('keyup', function() {
                    
                    //alert('hi');
                    mInput.style.border = '1px solid grey';
                    if( mInput.value.toString().test(mInput.get('pattern') ) )//"^[a-zA-Z_0-9]+$") )
                    {
                        mInput.style.border = '1px solid #20a429';
                    }
                    else
                    {
                        mInput.style.border = '1px solid #ff0000';
                    }
                });
            });
        }
        addInputEvent();
        
        
        // Checkbox methods
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
                    inputCopy.getElement('input').style.border = '1px solid #000000';
                    checkSlide.slideIn();
                    addInputEvent();
                });
                
            }
            else
            {
            
            }
        });
    }, 'javascript');
</script>
{/literal}
<div id="modulcreator">
    <form action="index.php?mod=modulecreator&sub=admin&action=create" method="POST">
        <table cellspacing="0" cellpadding="0" border="0" align="center">
            <tr>
                <td class="cell2">{t}Modulename{/t}</td>
                <td class="cell1"><input class="input_text" type="text" value="" pattern="^[a-zA-Z0-9]+$"></input></td>
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
                                    <input class="input_text" type="text" value="action_" name="m[frontend_methods][]" pattern="^[a-zA-Z0-9_]+$" /><img src="{$www_root_themes_core}/images/icons/delete.png" id="frontend_module_methods_delete" style="vertical-align: bottom; cursor: pointer;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="cell2">Create backend module?</td>
                <td class="cell1">
                    <input type="checkbox" name="m[backend]" id="backend_module" class="check_below" value="1" />
                    <div id="backend_module_display">
                        <div style="padding: 10px">
                            <b>{t}Method Names{/t}</b>&nbsp;<img style="vertical-align: bottom;cursor: pointer;"  src="{$www_root_themes_core}/images/icons/add.png" id="backend_module_methods_add" />
                            <div id="backend_module_wrapper">
                                
                                <div id="backend_module_methods_input">
                                    <input class="input_text" type="text" value="action_" name="m[backend_methods][]" pattern="^[a-zA-Z0-9_]+$" /><img src="{$www_root_themes_core}/images/icons/delete.png" id="backend_module_methods_delete" style="vertical-align: bottom; cursor: pointer;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="cell2">Create widget?</td>
                <td class="cell1">
                    <input type="checkbox" name="m[widget]" id="widget_module" class="check_below" value="1" />
                    <div id="widget_module_display">
                        <div style="padding: 10px">
                            <b>{t}Method Names{/t}</b>&nbsp;<img style="vertical-align: bottom;cursor: pointer;"  src="{$www_root_themes_core}/images/icons/add.png" id="widget_module_methods_add" />
                            <div id="widget_module_wrapper">
                                
                                <div id="widget_module_methods_input">
                                    <input class="input_text" type="text" value="action_" name="m[widget_methods][]" pattern="^[a-zA-Z0-9_]+$" /><img src="{$www_root_themes_core}/images/icons/delete.png" id="widget_module_methods_delete" style="vertical-align: bottom; cursor: pointer;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="cell1" colspan="2" align="center"><input class="ButtonGreen" type="submit" value="{t}Create module{/t}" name="submit" /></td>
            </tr>
        </table>
    </form>
</div>
{literal} 
<script type="text/javascript">
    window.addEvent('domready', function() {

        // Tooltips
        var myTips = new Tips($$('.input_text option[title!=]'), {
            className: 'tooltip'
        });
        
        // Tooltips
        var myTips2 = new Tips($$('.input_text[title!=]'), {
            className: 'tooltip'
        });
    
        // On Doubleclick take the whole list
        var addSelectEvent = function() {
            var allSelects = $('modulcreator').getElements('select');
            allSelects.each( function(theSelect, i) {
                theSelect.addEvent('dblclick', function() {
                    var optL = theSelect.options.length;
                    for( var y = 0; y < optL; y++ )
                    {
                        theSelect.options[y].selected = true;
                    }                   
                });
            });
        }
        addSelectEvent();
                
        // Clientseitige Inputvalidierung
        var addInputEvent = function()
        {
            var allInputs = $('modulcreator').getElements('input[pattern^=^]');
            allInputs.each( function(mInput, i) {
                mInput.addEvent('keyup', function() {
                                        mInput.style.border = '1px solid grey';
                    if( mInput.value.toString().test(mInput.get('pattern') ) )//"^[a-zA-Z_0-9]+$") )
                    {
                        if( mInput.get('name') == 'module_name' )
                        {
                            mInput.value = mInput.value.toLowerCase();
                            if( mInput.value.length > 0 )
                            {
                                $('the_link').innerHTML = "index.php?mod=<b>" + mInput.value + "</b>";
                            }
                            else
                            {
                                $('the_link').innerHTML = "";
                            }
                        }
                        mInput.style.border = '1px solid #20a429';
                    }
                    else
                    {
                        if( mInput.get('name') == 'module_name' )
                        {
                            mInput.value = mInput.value.toLowerCase();
                            $('the_link').innerHTML = "";
                        }
                        mInput.style.border = '1px solid #ff0000';
                    }
                    

                    
                });
            });
        }
        addInputEvent();
        
        var x = 1;
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
                    
                    var allInputs = inputCopy.getElements('input').extend(inputCopy.getElements('select'));
                    allInputs.each( function(theInput, i) {
                        theInput.set('name', theInput.get('name').replace('[0]', '['+x+']'));
                    });
                    x++;
                    
                    inputCopy.getElement('input').style.border = '1px solid #000000';
                    checkSlide.slideIn();
                    addInputEvent();
                    addSelectEvent();
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
        <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
            <tr>
                <td class="cell2">{t}Modulename{/t}</td>
                <td class="cell1"><input name="module_name" class="input_text" type="text" value="" pattern="^[a-zA-Z0-9]+$" />&nbsp;&nbsp;<span id="the_link"></span></td>
            </tr>
            <tr>
                <td class="cell2">{t}Author{/t}</td>
                <td class="cell1"><input name="author" class="input_text" type="text" value="" pattern="^[a-zA-Z0-9_\s]+$" /></td>
            </tr>
            <tr>
                <td class="cell2">Create frontend module?</td>
                <td class="cell1" style="padding: 0">
                    <div style="padding: 5px;"><input type="checkbox" name="m[frontend]" id="frontend_module" class="check_below" value="1" /></div>
                    <div id="frontend_module_display">
                        <div>
                            <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
                                <thead>
                                    <tr>
                                        <td class="td_header_small">
                                            <b>{t}Method names{/t}</b>
                                        </td>
                                        <td class="td_header_small">
                                            {t}Scope{/t}
                                        </td>
                                        <td class="td_header_small">
                                            {t}Snippets{/t}
                                        </td>
                                        <td class="td_header_small">
                                            {t}Doctrine{/t}
                                        </td>
                                        <td class="td_header_small">
                                            {t}Output?{/t}
                                        </td>
                                        <td class="td_header_small">
                                            {t}Template?{/t}
                                        </td>
                                        <td class="td_header_small" align="left">
                                            <img style="cursor: pointer;"  src="{$www_root_themes_core}/images/icons/add.png" id="frontend_module_methods_add" />                                    
                                        </td>
                                    </tr>
                                </thead>
                                <tbody id="frontend_module_wrapper">
                                    <tr id="frontend_module_methods_input">
                                        <td height="20" class="cell2">                                        
                                            <input class="input_text" type="text" value="action_" name="m[frontend_methods][0]" pattern="^[a-zA-Z0-9_]+$" />
                                        </td>
                                        <td class="cell2">
                                            <select name="m[frontend_scopes][0]" class="input_text">
                                                <option value="public" selected="selected">Public</option>
                                                <option value="private">Private</option>
                                                <option value="protected">Protected</option>
                                            </select>
                                        </td>
                                        <td class="cell1">
                                            <select multiple="multiple" size="5" name="m[frontend_snippets][0]" class="input_text">
                                                <option value="config" title="$config = $this->injector->instantiate('Clansuite_Config');">Config Injector $config</option>
                                                <option value="request" title="$request = $this->injector->instantiate('httprequest');">Request Injector $request</option>
                                                <option value="user">User Injector $user</option>
                                                <option value="security" title="$security = $this->injector->instantiate('Clansuite_Security');">Security Injector $security</option>
                                                <option value="mailer">Mailer Class $mailer</option>
                                            </select>
                                        </td>
                                        <td class="cell2">
                                            <select multiple="multiple" size="5" name="m[frontend_doctrines][0]" class="input_text">
                                                <option value="select">SELECT Statement - single row</option>
                                                <option value="create">CREATE Statement - single row</option>
                                                <option value="update">UPDATE Statement - single row</option>
                                                <option value="delete">DELETE Statement - single row</option>
                                                <option value="pager">PAGER - Pagination</option>
                                            </select>
                                        </td>
                                        <td class="cell1">                                        
                                            <input class="input_text" type="checkbox" value="1" name="m[frontend_outputs][0]" checked="checked" title="" />
                                        </td>
                                        <td class="cell2">                                        
                                            <input class="input_text" type="checkbox" value="1" name="m[frontend_tpls][0]" checked="checked" title="Generates a template file that has the same name as the method itself." />
                                        </td>
                                        <td class="cell1" align="left" width="99%">
                                            <img src="{$www_root_themes_core}/images/icons/delete.png" id="frontend_module_methods_delete" style="margin-top: 2px; cursor: pointer;" />
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="cell2">
                                            <b>Create widget methods?</b>
                                        </td>
                                        <td class="cell1" colspan="10">
                                            <input type="checkbox" name="m[widget]" id="widget_module" class="check_below" value="1" />
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                    </div>
                    
                    <div id="widget_module_display">
                        <div style="padding: 10px">
                            <b>{t}Method Names{/t}</b>&nbsp;<img style="vertical-align: bottom;cursor: pointer;"  src="{$www_root_themes_core}/images/icons/add.png" id="widget_module_methods_add" />
                            <table>
                                <tbody id="widget_module_wrapper">
                                    <tr id="widget_module_methods_input">
                                        <td height="20">
                                        
                                            <input class="input_text" type="text" value="widget_" name="m[widget_methods][]" pattern="^[a-zA-Z0-9_]+$" />
                                        </td>
                                        <td valign="bottom">
                                            <img src="{$www_root_themes_core}/images/icons/delete.png" id="widget_module_methods_delete" style="margin-top: 4px; cursor: pointer;" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
                            <table>
                                <tbody id="backend_module_wrapper">
                                    <tr id="backend_module_methods_input">
                                        <td height="20">
                                        
                                            <input class="input_text" type="text" value="action_admin_" name="m[backend_methods][]" pattern="^[a-zA-Z0-9_]+$" />
                                        </td>
                                        <td valign="bottom">
                                            <img src="{$www_root_themes_core}/images/icons/delete.png" id="backend_module_methods_delete" style="margin-top: 4px; cursor: pointer;" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
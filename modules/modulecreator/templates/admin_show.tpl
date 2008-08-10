{literal} 
<script type="text/javascript">
    window.addEvent('domready', function() {
        // Existing modules
        var existing_modules = {/literal}{$existing_modules_js}{literal};
    
        // Tooltips
        var myTips = new Tips($$('.input_text option[title!=]'), {
            className: 'tooltip'
        });
        
        // Tooltips for the input fields
        var myTips2 = new Tips($$('.input_text[title!=]'), {
            className: 'tooltip'
        });
        
        // Tooltips for the help Buttons
        // Tooltips
        var myTips3 = new Tips($$('img[title!=]'), {
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
                    if( mInput.value.toString().test(mInput.get('pattern')) && !existing_modules.contains(mInput.value) )//"^[a-zA-Z_0-9]+$") )
                    {
                        if( mInput.get('name') == 'm[module_name]' )
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
                        $('create_form').removeEvents('submit');
                        $('create_form').addEvent( 'submit', function() {
                            return true;
                        });
                    }
                    else
                    {
                        if( mInput.get('name') == 'm[module_name]' )
                        {
                            mInput.value = mInput.value.toLowerCase();
                            if( existing_modules.contains(mInput.value) )
                            {
                                $('the_link').innerHTML = "Module does already exist!";
                            }
                            else
                            {
                                $('the_link').innerHTML = "No special chars allowed!";
                            }
                        }
                        mInput.style.border = '1px solid #ff0000';
 
                        $('create_form').removeEvents('submit');
                        $('create_form').addEvent( 'submit', function() {
                            alert('There are still errors. Please correct');
                            return false;
                        });
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
                
                // checkboxen mit slider verkn�pfen
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
                var inputField = $( check.id + '_input' );
                
                var methodsAdd = $( check.id + '_add' );
                
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
    <form action="index.php?mod=modulecreator&sub=admin&action=preview" method="POST" id="create_form">
        <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
            <tr>
                <td class="cell2">{t}Modulename{/t}</td>
                <td class="cell1"><input name="m[module_name]" class="input_text" type="text" value="" pattern="^[a-zA-Z0-9]+$" />&nbsp;&nbsp;<span id="the_link"></span></td>
            </tr>
            <tr>
                <td class="cell2">{t}Metadata{/t}</td>
                <td class="cell1">
                    <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
                        <tr>
                            <td class="cell2">{t}Author{/t}</td>
                            <td class="cell1"><input name="m[meta][author]" class="input_text" type="text" value="" pattern="^[a-zA-Z0-9_\s]+$" /></td>
                        </tr>
                        <tr>
                            <td class="cell2">{t}License{/t}</td>
                            <td class="cell1"><input name="m[meta][license]" class="input_text" type="text" value="" pattern="^[a-zA-Z0-9_\s]+$" /></td>
                        </tr>
                        <tr>
                            <td class="cell2">{t}Title{/t}</td>
                            <td class="cell1"><input name="m[meta][title]" class="input_text" type="text" value="" pattern="^[a-zA-Z0-9_\s]+$" /></td>
                        </tr>
                        <tr>
                            <td class="cell2">{t}Homepage{/t}</td>
                            <td class="cell1"><input name="m[meta][homepage]" class="input_text" type="text" value="http://" pattern="{literal}^http://[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-]+\.?[a-zA-Z]+${/literal}" /></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="cell2">Create frontend for the module?</td>
                <td class="cell1">
                    <div style="padding-bottom: 5px;"><input type="checkbox" name="m[frontend][checked]" id="frontend_module" class="check_below" value="1" /></div>
                    <div id="frontend_module_display">
                        <div>
                            <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
                                <thead>
                                    <tr>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Method names{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Method names for the current module." /></div>
                                        </td>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Scope{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="The scope is the visibility of the function to other classes. When you are unsure what to choose, so take public." /></div>
                                        </td>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Snippets{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Snippets are small ClanSuite code pieces, that always occur when creating modules." /></div>
                                        </td>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Doctrine{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Doctrine is a Database Abstraction Layer upon PDO. Some small examples can be added by this." /></div>
                                        </td>
                                        <td class="td_header_small" width="72">
                                            <div style="float: left">{t}Output?{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Should the method output the view (e.g. Smarty Output, XML, ...)" /></div>
                                        </td>
                                        <td class="td_header_small" width="80">
                                            <div style="float: left">{t}Template?{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="When you create a method you normally do this by adding a specific template for the method. The standard name convention is to use the same name as the method itself." /></div>
                                        </td>
                                        <td class="td_header_small" align="left">
                                            <img style="cursor: pointer;"  src="{$www_root_themes_core}/images/icons/add.png" id="frontend_module_add" />                                    
                                        </td>
                                    </tr>
                                </thead>
                                <tbody id="frontend_module_wrapper">
                                    <tr id="frontend_module_input">
                                        <td height="20" class="cell2">                                        
                                            <input class="input_text" size="25" type="text" value="action_" name="m[frontend][frontend_methods][0]" pattern="^[a-zA-Z0-9_]+$" />
                                        </td>
                                        <td class="cell2">
                                            <select name="m[frontend][frontend_scopes][0]" class="input_text">
                                                <option value="public" selected="selected">Public</option>
                                                <option value="private">Private</option>
                                                <option value="protected">Protected</option>
                                            </select>
                                        </td>
                                        <td class="cell1">
                                            <select multiple="multiple" size="5" name="m[frontend][frontend_snippets][0][]" class="input_text">

                                                <option value="request" title="$request = $this->injector->instantiate('httprequest');">Request Instance $request</option>
                                                <option value="user">User Instance $user</option>
                                                <option value="security" title="$security = $this->injector->instantiate('Clansuite_Security');">Security Instance $security</option>
                                                <option value="mailer">Mailer Instance $mailer</option>
                                            </select>
                                        </td>
                                        <td class="cell2">
                                            <select multiple="multiple" size="5" name="m[frontend][frontend_doctrines][0][]" class="input_text">
                                                <option value="select">SELECT Statement - single row</option>
                                                <option value="create">CREATE Statement - single row</option>
                                                <option value="update">UPDATE Statement - single row</option>
                                                <option value="delete">DELETE Statement - single row</option>
                                                <option value="pager">PAGER - Pagination</option>
                                            </select>
                                        </td>
                                        <td class="cell1">                                        
                                            <input class="input_text" type="checkbox" value="1" name="m[frontend][frontend_outputs][0]" checked="checked" title="$this->prepareOutput();" />
                                        </td>
                                        <td class="cell2">                                        
                                            <input class="input_text" type="checkbox" value="1" name="m[frontend][frontend_tpls][0]" checked="checked" title="Generates a template file that has the same name as the method itself." />
                                        </td>
                                        <td class="cell1" align="left">
                                            <img src="{$www_root_themes_core}/images/icons/delete.png" id="frontend_module_delete" style="margin-top: 2px; cursor: pointer;" />
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="cell2">
                                            <div style="float: left"><b>{t}Create widget methods?{/t}</b></div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Widgets are methods that can be called from a Smarty template file. For example:<br />{literal}{load_module name='news' action='widget_news' items='2'}{/literal}" /></div>
                                        </td>
                                        <td class="cell1" colspan="10">
                                            <input type="checkbox" name="m[widget][checked]" id="widget_module" class="check_below" value="1" />
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                    </div>
                    
                    <div id="widget_module_display">
                        <div>
                            <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
                                <thead>
                                    <tr>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Method names{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Method names for the current module." /></div>
                                        </td>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Scope{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="The scope is the visibility of the function to other classes. When you are unsure what to choose, so take public." /></div>
                                        </td>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Snippets{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Snippets are small ClanSuite code pieces, that always occur when creating modules." /></div>
                                        </td>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Doctrine{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Doctrine is a Database Abstraction Layer upon PDO. Some small examples can be added by this." /></div>
                                        </td>
                                        <td class="td_header_small" width="72">
                                            <div style="float: left">{t}Output?{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Should the method output the view (e.g. Smarty Output, XML, ...)" /></div>
                                        </td>
                                        <td class="td_header_small" width="80">
                                            <div style="float: left">{t}Template?{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="When you create a method you normally do this by adding a specific template for the method. The standard name convention is to use the same name as the method itself." /></div>
                                        </td>
                                        <td class="td_header_small" align="left">
                                            <img style="cursor: pointer;"  src="{$www_root_themes_core}/images/icons/add.png" id="widget_module_add" />                                    
                                        </td>
                                    </tr>
                                </thead>
                                <tbody id="widget_module_wrapper">
                                    <tr id="widget_module_input">
                                        <td height="20" class="cell2">                                        
                                            <input class="input_text" size="25" type="text" value="widget_" name="m[widget][widget_methods][0]" pattern="^[a-zA-Z0-9_]+$" />
                                        </td>
                                        <td class="cell2">
                                            <select name="m[widget][widget_scopes][0]" class="input_text">
                                                <option value="public" selected="selected">Public</option>
                                                <option value="private">Private</option>
                                                <option value="protected">Protected</option>
                                            </select>
                                        </td>
                                        <td class="cell1">
                                            <select multiple="multiple" size="5" name="m[widget][widget_snippets][0][]" class="input_text">

                                                <option value="request" title="$request = $this->injector->instantiate('httprequest');">Request Instance $request</option>
                                                <option value="user">User Instance $user</option>
                                                <option value="security" title="$security = $this->injector->instantiate('Clansuite_Security');">Security Instance $security</option>
                                                <option value="mailer">Mailer Instance $mailer</option>
                                            </select>
                                        </td>
                                        <td class="cell2">
                                            <select multiple="multiple" size="5" name="m[widget][widget_doctrines][0][]" class="input_text">
                                                <option value="select">SELECT Statement - single row</option>
                                                <option value="create">CREATE Statement - single row</option>
                                                <option value="update">UPDATE Statement - single row</option>
                                                <option value="delete">DELETE Statement - single row</option>
                                                <option value="pager">PAGER - Pagination</option>
                                            </select>
                                        </td>
                                        <td class="cell1">                                        
                                            <input class="input_text" type="checkbox" value="1" name="m[widget][widget_outputs][0]" checked="checked" title="$this->prepareOutput();" />
                                        </td>
                                        <td class="cell2">                                        
                                            <input class="input_text" type="checkbox" value="1" name="m[widget][widget_tpls][0]" checked="checked" title="Generates a template file that has the same name as the method itself." />
                                        </td>
                                        <td class="cell1" align="left">
                                            <img src="{$www_root_themes_core}/images/icons/delete.png" id="widget_module_delete" style="margin-top: 2px; cursor: pointer;" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="cell2">Create backend for the module?</td>
                <td class="cell1">
                    <div style="padding-bottom: 5px;"><input type="checkbox" name="m[backend][checked]" id="backend_module" class="check_below" value="1" /></div>
                    <div id="backend_module_display">
                        <div>
                            <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
                                <thead>
                                    <tr>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Method names{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Method names for the current module." /></div>
                                        </td>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Scope{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="The scope is the visibility of the function to other classes. When you are unsure what to choose, so take public." /></div>
                                        </td>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Snippets{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Snippets are small ClanSuite code pieces, that always occur when creating modules." /></div>
                                        </td>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Doctrine{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Doctrine is a Database Abstraction Layer upon PDO. Some small examples can be added by this." /></div>
                                        </td>
                                        <td class="td_header_small" width="72">
                                            <div style="float: left">{t}Output?{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Should the method output the view (e.g. Smarty Output, XML, ...)" /></div>
                                        </td>
                                        <td class="td_header_small" width="80">
                                            <div style="float: left">{t}Template?{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="When you create a method you normally do this by adding a specific template for the method. The standard name convention is to use the same name as the method itself." /></div>
                                        </td>
                                        <td class="td_header_small" align="left">
                                            <img style="cursor: pointer;"  src="{$www_root_themes_core}/images/icons/add.png" id="backend_module_add" />                                    
                                        </td>
                                    </tr>
                                </thead>
                                <tbody id="backend_module_wrapper">
                                    <tr id="backend_module_input">
                                        <td height="20" class="cell2">                                        
                                            <input class="input_text" size="25" type="text" value="action_admin_" name="m[backend][backend_methods][0]" pattern="^[a-zA-Z0-9_]+$" />
                                        </td>
                                        <td class="cell2">
                                            <select name="m[backend][backend_scopes][0]" class="input_text">
                                                <option value="public" selected="selected">Public</option>
                                                <option value="private">Private</option>
                                                <option value="protected">Protected</option>
                                            </select>
                                        </td>
                                        <td class="cell1">
                                            <select multiple="multiple" size="5" name="m[backend][backend_snippets][0][]" class="input_text">

                                                <option value="request" title="$request = $this->injector->instantiate('httprequest');">Request Instance $request</option>
                                                <option value="user">User Instance $user</option>
                                                <option value="security" title="$security = $this->injector->instantiate('Clansuite_Security');">Security Instance $security</option>
                                                <option value="mailer">Mailer Instance $mailer</option>
                                            </select>
                                        </td>
                                        <td class="cell2">
                                            <select multiple="multiple" size="5" name="m[backend][backend_doctrines][0][]" class="input_text">
                                                <option value="select">SELECT Statement - single row</option>
                                                <option value="create">CREATE Statement - single row</option>
                                                <option value="update">UPDATE Statement - single row</option>
                                                <option value="delete">DELETE Statement - single row</option>
                                                <option value="pager">PAGER - Pagination</option>
                                            </select>
                                        </td>
                                        <td class="cell1">                                        
                                            <input class="input_text" type="checkbox" value="1" name="m[backend][backend_outputs][0]" checked="checked" title="$this->prepareOutput();" />
                                        </td>
                                        <td class="cell2">                                        
                                            <input class="input_text" type="checkbox" value="1" name="m[backend][backend_tpls][0]" checked="checked" title="Generates a template file that has the same name as the method itself." />
                                        </td>
                                        <td class="cell1" align="left">
                                            <img src="{$www_root_themes_core}/images/icons/delete.png" id="backend_module_delete" style="margin-top: 2px; cursor: pointer;" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="cell2">Create config for the module?</td>
                <td class="cell1">
                    <div style="padding-bottom: 5px;"><input type="checkbox" name="m[config][checked]" id="config" class="check_below" value="1" /></div>
                    <div id="config_display">
                        <div>
                            <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
                                <thead>
                                    <tr>
                                        <td class="td_header_small">
                                            {t}Key{/t}
                                        </td>
                                        <td class="td_header_small">
                                            {t}Value{/t}
                                        </td>
                                        <td class="td_header_small" align="left">
                                            <img style="cursor: pointer;"  src="{$www_root_themes_core}/images/icons/add.png" id="config_add" />                                    
                                        </td>
                                    </tr>
                                </thead>
                                <tbody id="config_wrapper">
                                    <tr id="config_input">
                                        <td height="20" class="cell2">                                        
                                            <input class="input_text" type="text" value="" name="m[config][config_keys][0]" pattern="^[a-zA-Z0-9_]+$" />
                                        </td>
                                        <td height="20" class="cell1">                                        
                                            <input class="input_text" type="text" value="" name="m[config][config_values][0]" pattern="^[a-zA-Z0-9_]+$" />
                                        </td>
                                        <td class="cell2" align="left" width="99%">
                                            <img src="{$www_root_themes_core}/images/icons/delete.png" id="config_delete" style="margin-top: 2px; cursor: pointer;" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="cell1" colspan="2" align="center"><input id="preview_button" class="ButtonGreen" type="submit" value="{t}Preview the module{/t}" name="submit" /></td>
            </tr>
        </table>
    </form>
</div>
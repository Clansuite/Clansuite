{modulenavigation}
<div class="ModuleHeading">{t}Builder for Modules{/t}</div>
<div class="ModuleHeadingSmall">{t}With the Modulebuilder you can easily create your own Clansuite Module. The builder will guide you in several steps through the creation of your module.{/t}</div>

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
                    if( $('create_module_form') )
                    {
                        $('create_module_form').fade('out');
                    }
                    mInput.setStyle('border-size','1px');
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
                var inputField = $( check.id + '_input' );

                var methodsAdd = $( check.id + '_add' );

                methodsAdd.addEvent('click', function() {
                    var inputCopy = inputField.clone();
                    var delEl = inputCopy.getElement('img');
                    delEl.addEvent('click', function() {
                        inputCopy.dispose();
                        checkSlide.slideIn();
                    });
                    var selects = inputCopy.getElements('select');
                    selects.each( function(theSelect, i) {
                        if( theSelect.get('size') == 5 )
                        theSelect.set('multiple', 'multiple');
                    });
                    inputCopy.fade('hide');
                    inputCopy.inject(methodsWrapper);
                    inputCopy.fade('in');

                    var allInputs = inputCopy.getElements('input').extend(inputCopy.getElements('select'));
                    allInputs.each( function(theInput, i) {
                        theInput.set('name', theInput.get('name').replace('[0]', '['+x+']'));
                        theInput.setStyles( {
                            border: '1px solid #000'
                        });
                    });
                    x++;

                    //inputCopy.getElements('input').style.border = '1px solid #000000';
                    checkSlide.slideIn();
                    addInputEvent();
                    addSelectEvent();
                });



            }
            else
            {

            }
        });

        $('preview_button').addEvent('click', function(event) {
            event.stop();
            // AJAX Request
            myForm = $('create_form');
            myForm.set('send', {
                url: 'index.php?mod=modulemanager&sub=admin&action=preview',
                method: 'post',
                onSuccess: function(html) {
                    $('preview_ajax').innerHTML = html;
                    var scalers = $$('.scaler');
                    scalers.each( function(scaler,i) {
                        var fx = new Fx.Tween($(scaler.id + '_scale'));
                        scaler.addEvent( 'click', function() {
                            if( $(scaler.id + '_scale').getStyle('max-height') == '150px' )
                            {
                                $(scaler.id + '_scale').setStyle('max-height', '');
                                $(scaler).innerHTML = "{/literal}{t}Decrease view{/t}{literal}";
                            }
                            else
                            {
                                $(scaler.id + '_scale').setStyle('max-height', '150px');
                                $(scaler).innerHTML = "{/literal}{t}Increase view{/t}{literal}";
                            }
                        });
                    });
                    $('ajax_loader').fade('out');
                },
                onRequest: function() {
                    $('ajax_loader').fade('in');
                }
            });
            myForm.send(); //Sends the form.

        });
        $('ajax_loader').fade('hide');

        // CRUD - MODULE ACTION STRUCTURE
        var crudLink = $('sample_crud');
        crudLink.addEvent('click', function(event) {
            event.stop();
            allChecks.each( function(check, i) {
                check.checked = 1;
                check.fireEvent('click');
            });
            $('frontend_module_add').fireEvent('click');

            $('backend_module_add').fireEvent('click');
        });

        // BREAD - MODULE ACTION STRUCTURE
        var breadLink = $('sample_bread');
        crudLink.addEvent('click', function(event) {
            event.stop();
            allChecks.each( function(check, i) {
                check.checked = 1;
                check.fireEvent('click');
            });
            $('frontend_module_add').fireEvent('click');

            $('backend_module_add').fireEvent('click');
        });

        // ABCD - MODULE ACTION STRUCTURE
        var breadLink = $('sample_bread');
        crudLink.addEvent('click', function(event) {
            event.stop();
            allChecks.each( function(check, i) {
                check.checked = 1;
                check.fireEvent('click');
            });
            $('frontend_module_add').fireEvent('click');

            $('backend_module_add').fireEvent('click');
        });

    });
</script>
{/literal}


<div id="modulcreator">

    <form action="index.php?mod=modulecreator&sub=admin&action=preview" method="POST" id="create_form">

        <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
            <tr>
                {include file="action_admin_builder_moduledefaults.tpl"}
            </tr>
            <tr>
                {include file="action_admin_builder_developer.tpl"}
            </tr>
            {* @todo add multiple developers
            <tr>
                {include file="action_admin_builder_multipledevelopers.tpl"}
            </tr>
            *}
            <tr>
                {include file="action_admin_builder_methodnamestructure.tpl"}
            </tr>
            <tr>
                <td class="cell2">Create Frontend for the module?</td>
                <td class="cell1">
                    <div style="padding-bottom: 5px;"><input type="checkbox" name="m[frontend][checked]" id="frontend_module" class="check_below" value="1" /></div>
                    <div id="frontend_module_display">
                        <div>
                            <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
                                <thead>
                                    <tr>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Method Names{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="Method names for the current module." /></div>
                                        </td>
                                        <td class="td_header_small" width="90">
                                            <div style="float: left">{t}Method Visibility{/t}</div><div style="float: right;"><img src="{$www_root_themes_core}/images/icons/help.png" title="The scope is the visibility of the function to other classes. When you are unsure what to choose, so take public." /></div>
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
                <td class="cell2">Create Backend for the module?</td>
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
                {include file="action_admin_builder_createconfig.tpl"}
            </tr>

            <tr>
                {include file="action_admin_builder_createdocumentation.tpl"}
            </tr>

            <tr>
                {include file="action_admin_builder_createunittests.tpl"}
            </tr>

            <tr>
                {include file="action_admin_builder_dependencies.tpl"}
            </tr>

            <tr>
                {include file="action_admin_builder_locales.tpl"}
            </tr>

            <tr>
                <td class="cell1" colspan="2" align="center">
                    <input id="preview_button" class="ButtonGreen" type="submit" value="{t}Preview the module{/t}" name="submit" /><br />
                    <img src="{$www_root_themes_core}/images/ajax/2.gif" id="ajax_loader">
                </td>
            </tr>
        </table>
    </form>
</div>
<div id="preview_ajax"></div>
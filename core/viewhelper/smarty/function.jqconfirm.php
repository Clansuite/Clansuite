<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * This smarty function is part of "Clansuite - just an eSports CMS"
 * @link http://www.clansuite.com
 *
 * @author Jens-André Koch <jakoch@web.de>
 * @copyright Copyright (C) 2009 Jens-André Koch
 * @license GNU Public License (GPL) v2 or any later version
 * @version SVN $Id$
 *
 * Name:    jqconfirm
 * Type:    function
 * Purpose: This TAG inserts javascript and html code for a jquery modal confirmation dialog window.
 *
 * Example Usage:
 * {jqconfirm}
 *
 * @param array $params as described above
 * @param Smarty $smarty
 * @return string
 */

function smarty_function_jqconfirm($params, &$smarty)
{
    
echo <<<EOD
<script>
    $(function(){
        // jQuery UI Dialog
        $('#dialog').dialog({
            autoOpen: false,
            width: 400,
            modal: true,
            resizable: false,
            buttons: {
                "Submit Form": function() {
                    document.deleteForm.submit();
                },
                "Cancel": function() {
                    $(this).dialog("close");
                }
            }
        });

        // ok, lets trigger an submit action on the form named #deleteForm
        $('form#deleteForm').submit(function(){
              // fetch every checked! checkbox
              $('input[type=checkbox]:checked').each(function(i, selected){
                  // define vars for td texts
                  var id, td1text, td2text;
                  // determine id of selected (which is the value of the checked checkbox element)
                  id = $(selected).val();
                  // get text of tr.td(1)
                  td1text = $(selected).closest('tr').find('td:first').text();
                  // get text of tr.td(2)
                  td2text = $(selected).closest('tr').find('td:first').next().text();
                  // now build a text message with id, modulename, name and append it to the element p#dialog-text
                  $("p#dialog-text").append('<b>ID</b> ' + id + ' <b>Modulename</b> ' + td1text + ' <b>Name</b> ' + td2text + '<br />');
              });

            $('#dialog').dialog('open');
            return false;
        });
    });
</script>

<div id="dialog" title="Verify Form Selections">
    <p>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span>You have selected the following elements:
    </p>
    <p id="dialog-text"></p>
    <p>If this is correct, click Submit Form.</p>
    <p>To edit the selections again, click Cancel.<p>
</div>
EOD;
}
?>
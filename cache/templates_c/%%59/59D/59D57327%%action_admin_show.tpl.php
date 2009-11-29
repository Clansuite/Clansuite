<?php /* Smarty version 2.6.25-dev, created on 2009-11-16 22:24:23
         compiled from C:/xampp/htdocs/projects/Clansuite/modules/categories/templates/action_admin_show.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modulenavigation', 'C:/xampp/htdocs/projects/Clansuite/modules/categories/templates/action_admin_show.tpl', 59, false),array('function', 'columnsort', 'C:/xampp/htdocs/projects/Clansuite/modules/categories/templates/action_admin_show.tpl', 68, false),array('function', 'icon', 'C:/xampp/htdocs/projects/Clansuite/modules/categories/templates/action_admin_show.tpl', 87, false),array('block', 't', 'C:/xampp/htdocs/projects/Clansuite/modules/categories/templates/action_admin_show.tpl', 62, false),array('modifier', 'capitalize', 'C:/xampp/htdocs/projects/Clansuite/modules/categories/templates/action_admin_show.tpl', 84, false),)), $this); ?>

<?php echo '
<script>
    $(function(){
        // jQuery UI Dialog
        $(\'#dialog\').dialog({
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
        $(\'form#deleteForm\').submit(function(){
              // fetch every checked! checkbox
              $(\'input[type=checkbox]:checked\').each(function(i, selected){
                  // define vars for td texts
                  var id, td1text, td2text;
                  // determine id of selected (which is the value of the checked checkbox element)
                  id = $(selected).val();
                  // get text of tr.td(1)
                  td1text = $(selected).closest(\'tr\').find(\'td:first\').text();
                  // get text of tr.td(2)
                  td2text = $(selected).closest(\'tr\').find(\'td:first\').next().text();
                  // now build a text message with id, modulename, name and append it to the element p#dialog-text
                  $("p#dialog-text").append(\'<b>ID</b> \' + id + \' <b>Modulename</b> \' + td1text + \' <b>Name</b> \' + td2text + \'<br />\');
              });

            $(\'#dialog\').dialog(\'open\');
            return false;
        });
    });
</script>
'; ?>


<div id="dialog" title="Verify Form Selections">
    <p>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span>You have selected the following elements:
    </p>
    <p id="dialog-text"></p>
    <p>If this is correct, click Submit Form.</p>
    <p>To edit the selections again, click Cancel.<p>
</div>

<?php echo smarty_function_modulenavigation(array(), $this);?>

<!-- Module Heading -->
<div class="ModuleHeading">Categories</div>
<div class="ModuleHeadingSmall"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>You can create, edit and delete Categories.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>

<table cellspacing="0" cellpadding="0" border="0" align="center">

    <!-- Header of Table -->
    <tr class="tr_header">
        <th><?php echo smarty_function_columnsort(array('html' => '#'), $this);?>
</th>
        <th><?php echo smarty_function_columnsort(array('html' => 'Module'), $this);?>
</th>
        <th><?php echo smarty_function_columnsort(array('selected_class' => 'selected','html' => 'Name'), $this);?>
</th>
        <th>Description</th>
        <th>Image</th>
        <th>Icon</th>
        <th>Color</th>
        <th>Action</th>
        <th>Select</th>
    </tr>

    <!-- Open Form -->
    <form id="deleteForm" name="deleteForm" action="index.php?mod=categories&sub=admin&amp;action=delete" method="post" accept-charset="UTF-8">
    <?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
    <tr class="tr_row1">
        <td align="center"> <?php echo $this->_tpl_vars['category']['cat_id']; ?>
</td>
        <td align="center"> <?php echo ((is_array($_tmp=$this->_tpl_vars['category']['module'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</td>
        <td align="center"> <b><font color="<?php echo $this->_tpl_vars['category']['color']; ?>
"><?php echo $this->_tpl_vars['category']['name']; ?>
</font></b></td>
        <td align="center"> <?php echo $this->_tpl_vars['category']['description']; ?>
</td>
        <td align="center"> <?php echo smarty_function_icon(array('src' => ($this->_tpl_vars['category']['image'])), $this);?>
</td>
        <td align="center"> <?php echo smarty_function_icon(array('src' => ($this->_tpl_vars['category']['icon'])), $this);?>
</td>
        <td align="center"> <?php echo $this->_tpl_vars['category']['color']; ?>
<div style="width:5px; height:5px; border:1px solid #000000; background-color:<?php echo $this->_tpl_vars['category']['color']; ?>
;"></div></td>
        <td align="center">
            <a class="ui-button ui-button-check ui-widget ui-state-default ui-corner-all ui-button-size-small ui-button-orientation-l" href="index.php?mod=categories&amp;sub=admin&amp;action=edit&amp;id=<?php echo $this->_tpl_vars['category']['cat_id']; ?>
" tabindex="0">
            <span class="ui-button-icon">
                <span class="ui-icon ui-icon-pencil"></span>
            </span>
            <span class="ui-button-label" unselectable="on" style="-moz-user-select: none;">Edit</span>
            </a>
        </td>
        <td align="center" width="1%">
            <input type="hidden" name="ids[]" value="<?php echo $this->_tpl_vars['category']['cat_id']; ?>
" />
            <input id="delete" name="delete[]" type="checkbox" value="<?php echo $this->_tpl_vars['category']['cat_id']; ?>
" />
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>

    <!-- Form Buttons -->
    <tr class="tr_row1">
        <td height="20" colspan="9" align="right">
            <a class="ButtonGreen" href="index.php?mod=categories&amp;sub=admin&amp;action=create" /><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Create Category<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a>
            <input class="Button" name="reset" type="reset" value="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Reset<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" />
            <input class="ButtonRed" type="submit" name="delete_text" value="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Delete Selected Categories<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" />
        </td>
    </tr>

    </form>
    <!-- Close Form -->

</table>
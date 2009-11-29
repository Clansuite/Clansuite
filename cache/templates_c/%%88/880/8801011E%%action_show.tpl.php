<?php /* Smarty version 2.6.25-dev, created on 2009-11-25 19:21:54
         compiled from C:%5Cxampp%5Chtdocs%5Cprojects%5CClansuite%5Cthemes%5Cbackend%5Ccontrolcenter%5Caction_show.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'C:\\xampp\\htdocs\\projects\\Clansuite\\themes\\backend\\controlcenter\\action_show.tpl', 4, false),)), $this); ?>
<div align="center">
<!-- Control Center Heading -->
<h3>
    <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Welcome.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    <br />
    <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>This is the Control Center (CC) of Clansuite.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</h3>

<!-- Table for Shortscuts and News/Updates Feed -->
<table cellspacing="6" width="100%">
<tbody>
    <tr>
        <td valign="top">

                <!-- Shortcuts Template from the Module or the Theme (autodetected) -->
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "shortcuts.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

        </td>
    </tr>
</tbody>
</table>
</div>
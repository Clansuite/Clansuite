<?php /* Smarty version 2.6.25-dev, created on 2009-11-15 15:07:38
         compiled from C:/xampp/htdocs/projects/Clansuite/modules/cronjobs/templates/action_admin_show.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modulenavigation', 'C:/xampp/htdocs/projects/Clansuite/modules/cronjobs/templates/action_admin_show.tpl', 2, false),array('block', 't', 'C:/xampp/htdocs/projects/Clansuite/modules/cronjobs/templates/action_admin_show.tpl', 3, false),)), $this); ?>
<?php echo smarty_function_modulenavigation(array(), $this);?>

<div class="ModuleHeading"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Cronjobs - timed and repetitive Tasks<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>
<div class="ModuleHeadingSmall"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Administrate the timed tasks. You can add, delete, activate, deactivate tasks.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>

<table cellspacing="0" cellpadding="0" border="0" align="center">

    <tr class="tr_header">
        <td align="center">Status</td>
        <td align="center">Name of Task</td>
        <td align="center">Description of Task</td>
        <td align="center">Last Run</td>
        <td align="center">Next Run</td>
        <td align="center">Run Frequency</td>
        <td align="center">Action</td>
    </tr>

    <?php $_from = $this->_tpl_vars['cronjobs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cronjob']):
?>
    <tr class="tr_row1">
        <td align="center"><?php echo $this->_tpl_vars['cronjob']['status']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['cronjob']['name']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['cronjob']['description']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['cronjob']['lastrun']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['cronjob']['nextrun']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['cronjob']['runfrequency']; ?>
</td>
        <td align="center">
                            <input class="ButtonGreen"  type="button" value="Activate"/>
                            <input class="ButtonOrange" type="button" value="Modifiy"/>
                            <input class="ButtonRed"    type="button" value="Deactivate"/>
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>

</table>
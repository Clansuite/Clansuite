<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:55
         compiled from users%5Ctemplates%5Cwidget_lastregisteredusers.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'users\\templates\\widget_lastregisteredusers.tpl', 7, false),array('modifier', 'duration', 'users\\templates\\widget_lastregisteredusers.tpl', 16, false),)), $this); ?>

<!-- Start: last_registered_users widget @ Standard Theme // -->

<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
    <tr>
        <td class="td_header" colspan="2"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Last registered users<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
    </tr>
    <tr>
        <td class="cell1">
            <?php $_from = $this->_tpl_vars['last_registered_users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['lastuser']):
?>
                                <?php echo $this->_tpl_vars['lastuser']['nick']; ?>

                           
                                ( <?php echo ((is_array($_tmp=$this->_tpl_vars['lastuser']['joined'])) ? $this->_run_mod_handler('duration', true, $_tmp) : smarty_modifier_duration($_tmp)); ?>
 ago )              
                <br />
            <?php endforeach; endif; unset($_from); ?>
        </td>
    </tr>
    
</table>

<!-- End: last_registered_users widget // -->
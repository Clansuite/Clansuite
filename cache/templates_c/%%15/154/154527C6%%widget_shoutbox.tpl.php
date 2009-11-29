<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:54
         compiled from shoutbox%5Ctemplates%5Cwidget_shoutbox.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'shoutbox\\templates\\widget_shoutbox.tpl', 11, false),array('modifier', 'date_format', 'shoutbox\\templates\\widget_shoutbox.tpl', 18, false),)), $this); ?>
 

<!-- Start Widget Shoutbox from Module Shoutbox -->

<div class="shoutbox_widget" id="widget_shoutbox" width="100%">

    <h2 class="td_header"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Shoutbox<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>
<?php $_from = $this->_tpl_vars['shoutbox_widget']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['shoutbox']):
?>
<div class="shoutbox_row">
	<div class="shoutbox_head">
		<span class="shoutbox_name"><?php echo $this->_tpl_vars['shoutbox']['name']; ?>
</span>
		<span class="shoutbox_mail"><a href="mailto:<?php echo $this->_tpl_vars['shoutbox']['mail']; ?>
"><img src="../../../themes/core/images/icons/email_open_image.png" border="0" /></a></span>
	</div>
	<div class="shoutbox_time_row"><span class="shoutbox_time">am: <?php echo ((is_array($_tmp=$this->_tpl_vars['shoutbox']['time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%y, %H:%M:%S") : smarty_modifier_date_format($_tmp, "%d.%m.%y, %H:%M:%S")); ?>
 Uhr</span></div>
    <div class="shoutbox_msg_row"><span class="shoutbox_msg"><?php echo $this->_tpl_vars['shoutbox']['msg']; ?>
</span></div>
</div>
<?php endforeach; endif; unset($_from); ?>
</div>
<!-- End Widget Shoutbox from Module Shoutbox -->
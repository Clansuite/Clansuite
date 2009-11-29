<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:55
         compiled from users%5Ctemplates%5Cwidget_randomuser.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'users\\templates\\widget_randomuser.tpl', 7, false),array('function', 'gravatar', 'users\\templates\\widget_randomuser.tpl', 17, false),array('modifier', 'duration', 'users\\templates\\widget_randomuser.tpl', 21, false),)), $this); ?>

<!-- Start: widget_randomuser @ module users // -->

<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
	<tr>
		<td class="td_header" colspan="2"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Random User<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
	</tr>
	<tr>
		<td class="cell1">
				<?php echo $this->_tpl_vars['random_user']['user_id']; ?>

				<br />
				<?php echo $this->_tpl_vars['random_user']['nick']; ?>

				<br />
				<?php echo $this->_tpl_vars['random_user']['email']; ?>

				<br />
				<?php echo smarty_function_gravatar(array('email' => ($this->_tpl_vars['random_user']['email'])), $this);?>

				<br />
				<?php echo $this->_tpl_vars['random_user']['country']; ?>

				<br />
				<?php echo ((is_array($_tmp=$this->_tpl_vars['random_user']['joined'])) ? $this->_run_mod_handler('duration', true, $_tmp) : smarty_modifier_duration($_tmp)); ?>
 ago
				<br />
		</td>
	</tr>

</table>

<!-- End: random_user widget // -->
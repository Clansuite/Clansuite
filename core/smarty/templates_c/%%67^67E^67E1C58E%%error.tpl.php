<?php /* Smarty version 2.6.13, created on 2006-06-13 12:08:16
         compiled from error.tpl */ ?>

<!-- ==== Start of <?php echo 'error.tpl'; ?>
 ==== -->
<p>
<fieldset style="border-color: red; background: 
<?php if ($this->_tpl_vars['error_type'] == 1): ?>red
<?php elseif ($this->_tpl_vars['error_type'] == 2): ?>orange
<?php elseif ($this->_tpl_vars['error_type'] == 3): ?>turquoise
<?php endif; ?>
;">
	<legend>
		<strong style='border: 1px solid #000000; background: white; -moz-opacity:0.75; filter:alpha(opacity=75);'>&nbsp;<?php echo $this->_tpl_vars['error_head']; ?>
&nbsp;</strong>
	</legend>
	<label>
		<?php if (isset ( $this->_tpl_vars['code'] )): ?><strong>Standard Code:</strong> <i><?php echo $this->_tpl_vars['code']; ?>
</i><br><?php endif; ?>
		<strong>Error Message:</strong> <i><?php echo $this->_tpl_vars['debug_info']; ?>
</i><br>
		<?php if (isset ( $this->_tpl_vars['file'] )): ?><strong>File:</strong> <i><?php echo $this->_tpl_vars['file']; ?>
</i><br><?php endif; ?>
		<?php if (isset ( $this->_tpl_vars['line'] )): ?><strong>Line:</strong> <i><?php echo $this->_tpl_vars['line']; ?>
</i><br><?php endif; ?>
	</label>
</fieldset>
</p>

<!-- ==== End of <?php echo 'error.tpl'; ?>
 ==== -->
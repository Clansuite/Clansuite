<?php /* Smarty version 2.6.13, created on 2006-06-14 10:46:11
         compiled from D:/Homepage/clansuite.com/workplace/trunk/templates/core/debug.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'assign_debug_info', 'D:/Homepage/clansuite.com/workplace/trunk/templates/core/debug.tpl', 3, false),array('modifier', 'escape', 'D:/Homepage/clansuite.com/workplace/trunk/templates/core/debug.tpl', 14, false),array('modifier', 'string_format', 'D:/Homepage/clansuite.com/workplace/trunk/templates/core/debug.tpl', 14, false),array('modifier', 'debug_print_var', 'D:/Homepage/clansuite.com/workplace/trunk/templates/core/debug.tpl', 23, false),)), $this); ?>

<!-- ==== Start of <?php echo 'D:/Homepage/clansuite.com/workplace/trunk/templates/core/debug.tpl'; ?>
 ==== -->
<?php echo smarty_function_assign_debug_info(array(), $this);?>


<span id="container_1" style="<?php if ($this->_tpl_vars['debug_popup'] == 0): ?>display: block<?php else: ?>display: none<?php endif; ?>;">
	<script>
	function clip(id){if(document.getElementById("span_" + id).style.display == 'none'){document.getElementById("span_" + id).style.display = "block";}else{document.getElementById("span_" + id).style.display = "none";}}</script>
	<table border=1px width="100%" bgcolor="#CCCCCC">
	<tr bgcolor=#cccccc><th colspan=2>clansuite Debug Console</th></tr>

	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('1')">Included templates & config files (load time in seconds):</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_1"><table border=0 width=100%>
	<?php unset($this->_sections['templates']);
$this->_sections['templates']['name'] = 'templates';
$this->_sections['templates']['loop'] = is_array($_loop=$this->_tpl_vars['_debug_tpls']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['templates']['show'] = true;
$this->_sections['templates']['max'] = $this->_sections['templates']['loop'];
$this->_sections['templates']['step'] = 1;
$this->_sections['templates']['start'] = $this->_sections['templates']['step'] > 0 ? 0 : $this->_sections['templates']['loop']-1;
if ($this->_sections['templates']['show']) {
    $this->_sections['templates']['total'] = $this->_sections['templates']['loop'];
    if ($this->_sections['templates']['total'] == 0)
        $this->_sections['templates']['show'] = false;
} else
    $this->_sections['templates']['total'] = 0;
if ($this->_sections['templates']['show']):

            for ($this->_sections['templates']['index'] = $this->_sections['templates']['start'], $this->_sections['templates']['iteration'] = 1;
                 $this->_sections['templates']['iteration'] <= $this->_sections['templates']['total'];
                 $this->_sections['templates']['index'] += $this->_sections['templates']['step'], $this->_sections['templates']['iteration']++):
$this->_sections['templates']['rownum'] = $this->_sections['templates']['iteration'];
$this->_sections['templates']['index_prev'] = $this->_sections['templates']['index'] - $this->_sections['templates']['step'];
$this->_sections['templates']['index_next'] = $this->_sections['templates']['index'] + $this->_sections['templates']['step'];
$this->_sections['templates']['first']      = ($this->_sections['templates']['iteration'] == 1);
$this->_sections['templates']['last']       = ($this->_sections['templates']['iteration'] == $this->_sections['templates']['total']);
?>
		<tr bgcolor=<?php if (!(1 & $this->_sections['templates']['index'])): ?>#eeeeee<?php else: ?>#fafafa<?php endif; ?>><td colspan=2><?php unset($this->_sections['indent']);
$this->_sections['indent']['name'] = 'indent';
$this->_sections['indent']['loop'] = is_array($_loop=$this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['depth']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['indent']['show'] = true;
$this->_sections['indent']['max'] = $this->_sections['indent']['loop'];
$this->_sections['indent']['step'] = 1;
$this->_sections['indent']['start'] = $this->_sections['indent']['step'] > 0 ? 0 : $this->_sections['indent']['loop']-1;
if ($this->_sections['indent']['show']) {
    $this->_sections['indent']['total'] = $this->_sections['indent']['loop'];
    if ($this->_sections['indent']['total'] == 0)
        $this->_sections['indent']['show'] = false;
} else
    $this->_sections['indent']['total'] = 0;
if ($this->_sections['indent']['show']):

            for ($this->_sections['indent']['index'] = $this->_sections['indent']['start'], $this->_sections['indent']['iteration'] = 1;
                 $this->_sections['indent']['iteration'] <= $this->_sections['indent']['total'];
                 $this->_sections['indent']['index'] += $this->_sections['indent']['step'], $this->_sections['indent']['iteration']++):
$this->_sections['indent']['rownum'] = $this->_sections['indent']['iteration'];
$this->_sections['indent']['index_prev'] = $this->_sections['indent']['index'] - $this->_sections['indent']['step'];
$this->_sections['indent']['index_next'] = $this->_sections['indent']['index'] + $this->_sections['indent']['step'];
$this->_sections['indent']['first']      = ($this->_sections['indent']['iteration'] == 1);
$this->_sections['indent']['last']       = ($this->_sections['indent']['iteration'] == $this->_sections['indent']['total']);
?>&nbsp;&nbsp;&nbsp;<?php endfor; endif; ?><font color=<?php if ($this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['type'] == 'template'): ?>brown<?php elseif ($this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['type'] == 'insert'): ?>black<?php else: ?>green<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</font><?php if (isset ( $this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['exec_time'] )): ?> <font size=-1><i>(<?php echo ((is_array($_tmp=$this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['exec_time'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.5f") : smarty_modifier_string_format($_tmp, "%.5f")); ?>
)<?php if ($this->_sections['templates']['index'] == 0): ?> (total)<?php endif; ?></i></font><?php endif; ?></td></tr>
	<?php endfor; else: ?>
		<tr bgcolor=#eeeeee><td colspan=2><i>no templates included</i></td></tr>	
	<?php endif; ?>
	</table></span></td></tr>

	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('2')">$tpl->assign(): Assigned template variables:</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_2"><table border=0 width=100%> 
	<?php unset($this->_sections['vars']);
$this->_sections['vars']['name'] = 'vars';
$this->_sections['vars']['loop'] = is_array($_loop=$this->_tpl_vars['_debug_keys']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['vars']['show'] = true;
$this->_sections['vars']['max'] = $this->_sections['vars']['loop'];
$this->_sections['vars']['step'] = 1;
$this->_sections['vars']['start'] = $this->_sections['vars']['step'] > 0 ? 0 : $this->_sections['vars']['loop']-1;
if ($this->_sections['vars']['show']) {
    $this->_sections['vars']['total'] = $this->_sections['vars']['loop'];
    if ($this->_sections['vars']['total'] == 0)
        $this->_sections['vars']['show'] = false;
} else
    $this->_sections['vars']['total'] = 0;
if ($this->_sections['vars']['show']):

            for ($this->_sections['vars']['index'] = $this->_sections['vars']['start'], $this->_sections['vars']['iteration'] = 1;
                 $this->_sections['vars']['iteration'] <= $this->_sections['vars']['total'];
                 $this->_sections['vars']['index'] += $this->_sections['vars']['step'], $this->_sections['vars']['iteration']++):
$this->_sections['vars']['rownum'] = $this->_sections['vars']['iteration'];
$this->_sections['vars']['index_prev'] = $this->_sections['vars']['index'] - $this->_sections['vars']['step'];
$this->_sections['vars']['index_next'] = $this->_sections['vars']['index'] + $this->_sections['vars']['step'];
$this->_sections['vars']['first']      = ($this->_sections['vars']['iteration'] == 1);
$this->_sections['vars']['last']       = ($this->_sections['vars']['iteration'] == $this->_sections['vars']['total']);
?>
		<tr bgcolor=<?php if (!(1 & $this->_sections['vars']['index'])): ?>#eeeeee<?php else: ?>#fafafa<?php endif; ?>><td valign=top><font color=blue>{$<?php echo $this->_tpl_vars['_debug_keys'][$this->_sections['vars']['index']]; ?>
}</font></td><td nowrap><font color=green><?php echo smarty_modifier_debug_print_var($this->_tpl_vars['_debug_vals'][$this->_sections['vars']['index']]); ?>
</font></td></tr>
	<?php endfor; else: ?>
		<tr bgcolor=#eeeeee><td colspan=2><i>no template variables assigned</i></td></tr>	
	<?php endif; ?>
	</table></span></td></tr>

	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('3')">Assigned config file variables (outer template scope):</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_3"><table border=0 width=100%>	
	<?php unset($this->_sections['config_vars']);
$this->_sections['config_vars']['name'] = 'config_vars';
$this->_sections['config_vars']['loop'] = is_array($_loop=$this->_tpl_vars['_debug_config_keys']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['config_vars']['show'] = true;
$this->_sections['config_vars']['max'] = $this->_sections['config_vars']['loop'];
$this->_sections['config_vars']['step'] = 1;
$this->_sections['config_vars']['start'] = $this->_sections['config_vars']['step'] > 0 ? 0 : $this->_sections['config_vars']['loop']-1;
if ($this->_sections['config_vars']['show']) {
    $this->_sections['config_vars']['total'] = $this->_sections['config_vars']['loop'];
    if ($this->_sections['config_vars']['total'] == 0)
        $this->_sections['config_vars']['show'] = false;
} else
    $this->_sections['config_vars']['total'] = 0;
if ($this->_sections['config_vars']['show']):

            for ($this->_sections['config_vars']['index'] = $this->_sections['config_vars']['start'], $this->_sections['config_vars']['iteration'] = 1;
                 $this->_sections['config_vars']['iteration'] <= $this->_sections['config_vars']['total'];
                 $this->_sections['config_vars']['index'] += $this->_sections['config_vars']['step'], $this->_sections['config_vars']['iteration']++):
$this->_sections['config_vars']['rownum'] = $this->_sections['config_vars']['iteration'];
$this->_sections['config_vars']['index_prev'] = $this->_sections['config_vars']['index'] - $this->_sections['config_vars']['step'];
$this->_sections['config_vars']['index_next'] = $this->_sections['config_vars']['index'] + $this->_sections['config_vars']['step'];
$this->_sections['config_vars']['first']      = ($this->_sections['config_vars']['iteration'] == 1);
$this->_sections['config_vars']['last']       = ($this->_sections['config_vars']['iteration'] == $this->_sections['config_vars']['total']);
?>
		<tr bgcolor=<?php if (!(1 & $this->_sections['config_vars']['index'])): ?>#eeeeee<?php else: ?>#fafafa<?php endif; ?>><td valign=top width=10%><font color=maroon>{#<?php echo $this->_tpl_vars['_debug_config_keys'][$this->_sections['config_vars']['index']]; ?>
#}</font></td><td><font color=green><?php echo smarty_modifier_debug_print_var($this->_tpl_vars['_debug_config_vals'][$this->_sections['config_vars']['index']]); ?>
</font></td></tr>
	<?php endfor; else: ?>
		<tr bgcolor=#eeeeee><td colspan=2><i>no config vars assigned</i></td></tr>	
	<?php endif; ?>
	</table></span></td></tr>


	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('4')">$_REQUEST: Incoming Variables</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_4"><table border=0 width=100%>
		<?php $_from = $this->_tpl_vars['request']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['wert']):
?>
			<tr bgcolor=#eeeeee><td width=100 valign=top>
			<font color=blue><?php echo $this->_tpl_vars['schluessel']; ?>
</font></td><td><font color=green>
			<?php echo smarty_modifier_debug_print_var($this->_tpl_vars['wert']); ?>
</font>
			</td></tr>
		<?php endforeach; endif; unset($_from); ?>
	 </table></span></td></tr>
	 
	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('5')">$_SESSION: Session Variables</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_5"><table border=0 width=100%>
		<?php $_from = $this->_tpl_vars['session']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['wert']):
?>
			<tr bgcolor=#eeeeee><td width=100 valign=top>
			<font color=blue><?php echo $this->_tpl_vars['schluessel']; ?>
</font></td><td><font color=green>
			<?php echo smarty_modifier_debug_print_var($this->_tpl_vars['wert']); ?>
</font>
			</td></tr>
		<?php endforeach; endif; unset($_from); ?>
	 </table></span></td></tr>
	 
	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('6')">$_COOKIES: Session Variables</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_6"><table border=0 width=100%>
		<?php $_from = $this->_tpl_vars['cookies']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['wert']):
?>
			<tr bgcolor=#eeeeee><td width=100 valign=top>
			<font color=blue><?php echo $this->_tpl_vars['schluessel']; ?>
</font></td><td><font color=green>
			<?php echo smarty_modifier_debug_print_var($this->_tpl_vars['wert']); ?>
</font>
			</td></tr>
		<?php endforeach; endif; unset($_from); ?>
	 </table></span></td></tr>
	 
	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('7')">$cfg: Configuration (Password hidden)</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_7"><table border=0 width=100%> 
		<?php $_from = $this->_tpl_vars['config']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['wert']):
?>
			<tr bgcolor=#eeeeee><td width=100 valign=top>
				<?php if ($this->_tpl_vars['schluessel'] == 'db_password'): ?>
					<font color=blue><?php echo smarty_modifier_debug_print_var($this->_tpl_vars['schluessel']); ?>
</font></td><td><font color=green>
					***</font>
				<?php else: ?>
					<font color=blue><?php echo smarty_modifier_debug_print_var($this->_tpl_vars['schluessel']); ?>
</font></td><td><font color=green>
					<?php echo smarty_modifier_debug_print_var($this->_tpl_vars['wert']); ?>
</font>
				<?php endif; ?>
			</td></tr>
		<?php endforeach; endif; unset($_from); ?>
	</table></span></td></tr>
	
	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('8')">$error->error_log: Advanced Error Logging</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_8"><table border=0 width=100%>
		<?php $_from = $this->_tpl_vars['error_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['wert']):
?>
			<tr bgcolor=#eeeeee><td width=100 valign=top>
			<font color=blue><?php echo $this->_tpl_vars['schluessel']; ?>
</font></td><td><font color=green>
			<?php echo smarty_modifier_debug_print_var($this->_tpl_vars['wert']); ?>
</font>
			</td></tr>
		<?php endforeach; endif; unset($_from); ?>
	</table></span></td></tr>

	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('9')">$lang->loaded: Loaded Language Files</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_9"><table border=0 width=100%>
		<?php $_from = $this->_tpl_vars['lang_loaded']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['wert']):
?>
			<tr bgcolor=#eeeeee><td width=100 valign=top colspan=2>
			<font color=blue><?php echo smarty_modifier_debug_print_var($this->_tpl_vars['wert']); ?>
</font></td></tr>
		<?php endforeach; endif; unset($_from); ?>
	</table></span></td></tr>			

	<tr bgcolor=#cccccc><td colspan=2><b><a href="javascript:clip('10')">$modules->loaded: Loaded Modules</a></b></td></tr>
	<tr><td width=100% colspan=2><span style="display:none" id="span_10"><table border=0 width=100%>
		<?php $_from = $this->_tpl_vars['mods_loaded']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['wert']):
?>
			<tr bgcolor=#eeeeee><td width=100 valign=top colspan=2>
			<font color=blue><?php echo smarty_modifier_debug_print_var($this->_tpl_vars['wert']); ?>
</font></td></tr>
		<?php endforeach; endif; unset($_from); ?>
	</table></span></td></tr>
	
	</table>
</span>
<?php if ($this->_tpl_vars['debug_popup'] == 1): ?>
	<SCRIPT language=javascript>

	if ( self.name == '' )
	{
		var title = 'Console';
	}
	else
	{
		var title = 'Console_' + self.name;
	}
		var html = document.getElementById("container_1").innerHTML;
	_csphere_console = window.open("",title.value,"width=800,height=600,resizable,scrollbars=yes");
	_csphere_console.document.write(html);
	_csphere_console.document.close();
	</script>
<?php endif; ?>

<!-- ==== End of <?php echo 'D:/Homepage/clansuite.com/workplace/trunk/templates/core/debug.tpl'; ?>
 ==== -->
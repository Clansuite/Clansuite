<?php /* Smarty version 2.6.25-dev, created on 2009-11-17 07:08:55
         compiled from C:/xampp/htdocs/projects/Clansuite/modules/systeminfo/templates/action_admin_show_apc.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modulenavigation', 'C:/xampp/htdocs/projects/Clansuite/modules/systeminfo/templates/action_admin_show_apc.tpl', 1, false),array('function', 'openflashchart', 'C:/xampp/htdocs/projects/Clansuite/modules/systeminfo/templates/action_admin_show_apc.tpl', 60, false),array('block', 't', 'C:/xampp/htdocs/projects/Clansuite/modules/systeminfo/templates/action_admin_show_apc.tpl', 11, false),array('modifier', 'megabytes', 'C:/xampp/htdocs/projects/Clansuite/modules/systeminfo/templates/action_admin_show_apc.tpl', 20, false),array('modifier', 'dateformat', 'C:/xampp/htdocs/projects/Clansuite/modules/systeminfo/templates/action_admin_show_apc.tpl', 26, false),array('modifier', 'duration', 'C:/xampp/htdocs/projects/Clansuite/modules/systeminfo/templates/action_admin_show_apc.tpl', 27, false),array('modifier', 'replace', 'C:/xampp/htdocs/projects/Clansuite/modules/systeminfo/templates/action_admin_show_apc.tpl', 69, false),)), $this); ?>
<?php echo smarty_function_modulenavigation(array(), $this);?>


<?php if (empty ( $this->_tpl_vars['apc_sysinfos']['version'] )): ?>
<b>Alternative PHP Cache not loaded. Enable the PHP Extension 'extension=php_apc.dll' in php.ini.</b>
<?php else: ?>

<div class="ModuleHeading"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Statistics for "Alternative PHP Cache <?php echo $this->_tpl_vars['apc_sysinfos']['version']; ?>
"<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>
<div class="ModuleHeadingSmall"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Aktueller Zustand und statistische Informationen des AP-Cache.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>

<h2>General Cache Informations</h2>
<table>
    <tr><td width="30%">Alternative PHP Cache</td>  <td><?php echo $this->_tpl_vars['apc_sysinfos']['version']; ?>
</td></tr>
    <tr><td>PHP Version</td>         <td><?php echo $this->_tpl_vars['apc_sysinfos']['phpversion']; ?>
</td></tr>
    <tr><td>APC Host</td>            <td><?php echo $this->_supers['server']['SERVER_NAME']; ?>
 </td></tr>
    <tr><td>Server Software</td>     <td><?php echo $this->_supers['server']['SERVER_SOFTWARE']; ?>
</td></tr>
    <tr><td>Shared Memory</td>       <td><?php echo $this->_tpl_vars['apc_sysinfos']['sma_info']['num_seg']; ?>
 Segment(s) with <?php echo ((is_array($_tmp=$this->_tpl_vars['apc_sysinfos']['sma_info']['seg_size'])) ? $this->_run_mod_handler('megabytes', true, $_tmp) : smarty_modifier_megabytes($_tmp)); ?>

                                     (<?php echo $this->_tpl_vars['apc_sysinfos']['system_cache_info']['memory_type']; ?>
 memory, <?php echo $this->_tpl_vars['apc_sysinfos']['system_cache_info']['locking_type']; ?>
 locking)</td></tr>
</table>

<h2>System Cache Informations</h2>
<table>
    <tr><td>Start Time</td>          <td><?php echo ((is_array($_tmp=$this->_tpl_vars['apc_sysinfos']['system_cache_info']['start_time'])) ? $this->_run_mod_handler('dateformat', true, $_tmp) : smarty_modifier_dateformat($_tmp)); ?>
</td></tr>
	<tr><td>Uptime</td>              <td><?php echo ((is_array($_tmp=$this->_tpl_vars['apc_sysinfos']['system_cache_info']['start_time'])) ? $this->_run_mod_handler('duration', true, $_tmp) : smarty_modifier_duration($_tmp)); ?>
</td></tr>
	<tr><td>Time to Live</td>        <td><?php echo $this->_tpl_vars['apc_sysinfos']['system_cache_info']['ttl']; ?>
</td></tr>
	<tr><td>Cache Full Counter</td>  <td><?php echo $this->_tpl_vars['apc_sysinfos']['system_cache_info']['expunges']; ?>
</td></tr>
	<tr><td>File Upload Support</td> <td><?php echo $this->_tpl_vars['apc_sysinfos']['system_cache_info']['file_upload_progress']; ?>
</td></tr>
	<tr><td>Cache Size Files</td>    <td><?php echo $this->_tpl_vars['apc_sysinfos']['system_cache_info']['size_files']; ?>
</td></tr>

</table>

<h2>Stats</h2>
<table>
<thead>
    <th>Host</th><th>Version</th>
    <th>Hits</th><th>Misses</th><th>Hit Rate</th>
    <th>MemTotal</th><th>MemUsed</th><th>MemFree</th>
    <th>Files cached</th><th>Files deleted</th>
    <th>Action</th>
</thead>
<tbody>
<tr>
    <td>Host</td>
    <td>Version</td>
    <td><?php echo $this->_tpl_vars['apc_sysinfos']['system_cache_info']['num_hits']; ?>
</td>
    <td><?php echo $this->_tpl_vars['apc_sysinfos']['system_cache_info']['num_misses']; ?>
</td>
    <td><?php echo $this->_tpl_vars['apc_sysinfos']['system_cache_info']['hit_rate_percentage']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['apc_sysinfos']['sma_info']['mem_size'])) ? $this->_run_mod_handler('megabytes', true, $_tmp) : smarty_modifier_megabytes($_tmp)); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['apc_sysinfos']['sma_info']['mem_used'])) ? $this->_run_mod_handler('megabytes', true, $_tmp) : smarty_modifier_megabytes($_tmp)); ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['apc_sysinfos']['sma_info']['avail_mem'])) ? $this->_run_mod_handler('megabytes', true, $_tmp) : smarty_modifier_megabytes($_tmp)); ?>
 <?php echo $this->_tpl_vars['apc_sysinfos']['sma_info']['mem_avail_percentage']; ?>
</td>
    <td><?php echo $this->_tpl_vars['apc_sysinfos']['system_cache_info']['files_cached']; ?>
</td>
    <td><?php echo $this->_tpl_vars['apc_sysinfos']['system_cache_info']['files_deleted']; ?>
</td>
</tr>
</tbody>
</table>

<?php echo smarty_function_openflashchart(array('width' => '220','heigth' => '110','url' => "index.php?mod=systeminfo&sub=admin&action=return_ofc_hitrates"), $this);?>


<h2>Runtime Settings</h2>

<table>
<tr><td>Name</td><td>global_value</td><td>local_value</td><td>access</td><td>accessname</td></tr>

<?php $_from = $this->_tpl_vars['apc_sysinfos']['settings']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['outer']['iteration']++;
?>
    <tr><td><b><?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'apc.', '') : smarty_modifier_replace($_tmp, 'apc.', '')); ?>
</b></td>
    <?php $_from = $this->_tpl_vars['value']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>
        <td><?php echo $this->_tpl_vars['value']; ?>
</td>
    <?php endforeach; endif; unset($_from); ?>
    </tr>
<?php endforeach; endif; unset($_from); ?>
</table>

<?php endif; ?>
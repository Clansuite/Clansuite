<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:55
         compiled from server_stats.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gzipcheck', 'server_stats.tpl', 3, false),array('function', 'serverload', 'server_stats.tpl', 7, false),array('function', 'memusage', 'server_stats.tpl', 9, false),)), $this); ?>
<a href="javascript:clip('proc_infos')">Document Processing Info</a>
<span id="proc_infos" style="display: none;">
<strong>Document</strong> [ Time:  | Gzip: <?php echo smarty_function_gzipcheck(array(), $this);?>
 ]
<br />
<strong>Database</strong> [ Time:  | Queries: <?php echo $this->_tpl_vars['db_counter']; ?>
 ]
<br />
<strong>Serverload</strong> <?php echo smarty_function_serverload(array(), $this);?>

<br />
<strong>Memory</strong> [ <?php echo smarty_function_memusage(array(), $this);?>
 ]
</span>
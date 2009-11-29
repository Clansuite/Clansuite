<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:54
         compiled from breadcrumbs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'breadcrumbs', 'breadcrumbs.tpl', 3, false),)), $this); ?>
<div id="breadcrumbs">

    <?php echo smarty_function_breadcrumbs(array('trail' => $this->_tpl_vars['trail'],'separator' => " &raquo; ",'length' => 45), $this);?>


</div>
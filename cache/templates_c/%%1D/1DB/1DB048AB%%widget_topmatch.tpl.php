<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:54
         compiled from matches%5Ctemplates%5Cwidget_topmatch.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'matches\\templates\\widget_topmatch.tpl', 11, false),)), $this); ?>

<!-- Start Widget Topmatch from Module Matches -->

<div class="news_widget" id="widget_topmatch" width="100%">

    <h2 class="td_header"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Topmatch<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>

    
</div>

<!-- End Widget Topmatch -->
<?php /* Smarty version 2.6.13, created on 2006-06-08 05:34:25
         compiled from index/show.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'translate', 'index/show.tpl', 4, false),)), $this); ?>
<?php $this->_cache_serials['D:/Homepage/clansuite.com/workplace/trunk/core/smarty/templates_c/\%%E5^E5D^E5DC63F6%%show.tpl.inc'] = 'c376b988b81e8d8a827009f1b94f101a'; ?>
<!-- ==== Start of <?php echo 'index/show.tpl'; ?>
 ==== -->
<b><?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c376b988b81e8d8a827009f1b94f101a#0}'; };$this->_tag_stack[] = array('translate', array()); $_block_repeat=true;language::smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Hello<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo language::smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including) { echo '{/nocache:c376b988b81e8d8a827009f1b94f101a#0}'; };?></b><br />
<b><?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c376b988b81e8d8a827009f1b94f101a#1}'; };$this->_tag_stack[] = array('translate', array()); $_block_repeat=true;language::smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Welcome<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo language::smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including) { echo '{/nocache:c376b988b81e8d8a827009f1b94f101a#1}'; };?></b><br />
<i><?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c376b988b81e8d8a827009f1b94f101a#2}'; };$this->_tag_stack[] = array('translate', array('u' => 'Benutzernamen_variable')); $_block_repeat=true;language::smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>How are you, %u ?<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo language::smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including) { echo '{/nocache:c376b988b81e8d8a827009f1b94f101a#2}'; };?></i>

 
<br />
In der LanguageDatei existiert eine Übersetzung für "Hello":
<?php  print(language::t("Hello"));  ?> 

<br />
In der LanguageDatei existiert eine Übersetzung für "You are redirected":
<?php  print(language::t("You are redirected"));  ?>

<!-- ==== End of <?php echo 'index/show.tpl'; ?>
 ==== -->
<?php /* Smarty version 2.6.25-dev, created on 2009-11-16 22:27:00
         compiled from C:/xampp/htdocs/projects/Clansuite/modules/categories/templates/action_admin_create.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modulenavigation', 'C:/xampp/htdocs/projects/Clansuite/modules/categories/templates/action_admin_create.tpl', 8, false),array('block', 't', 'C:/xampp/htdocs/projects/Clansuite/modules/categories/templates/action_admin_create.tpl', 11, false),)), $this); ?>
<!-- Start of Template - D:\xampplite\htdocs\work\clansuite\trunk\modules\categories\templates\action_admin_create.tpl -->

<?php echo smarty_function_modulenavigation(array(), $this);?>

<!-- Module Heading -->
<div class="ModuleHeading">Categories</div>
<div class="ModuleHeadingSmall"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>You can create, edit and delete Categories.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>

<?php echo $this->_tpl_vars['form']; ?>


<!-- End of Template - D:\xampplite\htdocs\work\clansuite\trunk\modules\categories\templates\action_admin_create.tpl -->
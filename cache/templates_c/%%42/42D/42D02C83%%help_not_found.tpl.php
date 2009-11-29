<?php /* Smarty version 2.6.25-dev, created on 2009-11-25 19:21:54
         compiled from C:%5Cxampp%5Chtdocs%5Cprojects%5CClansuite%5Cthemes%5Ccore/templates/help_not_found.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'C:\\xampp\\htdocs\\projects\\Clansuite\\themes\\core/templates/help_not_found.tpl', 28, false),)), $this); ?>
<!-- Start Template help_not_found.tpl -->

<?php echo '
<style type="text/css">
/* this defines the look of the red error box, for example: when an template is missing */
.error_help {
    background:#FFDDDD url('; ?>
<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
<?php echo '/images/icons/error.png) no-repeat scroll 15px 12px;
    border:1px solid #FFBBBB;
    color:#BB0000;
    font-weight:bold;
    margin:0.5em 15px 1em -20px;
    padding:15px 20px 15px 50px;
}

/* this defines the look of box, providing the link to the editor, if an template is missing */
.create_help {
    background:#DDFFDD url('; ?>
<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
<?php echo '/images/icons/page_edit.png) no-repeat scroll 15px 12px;
    border:1px solid #BBFFBB;
    color:#00BB00;
    font-weight:bold;
    margin:0.5em 15px 1em -20px;
    padding:15px 20px 15px 50px;
}
</style>
'; ?>


<div class="error_help">
    <strong><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>A helptext for this module is <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <u> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>not existing<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> </u> !</strong>
</div>

<?php if (@DEBUG && @DEVELOPMENT): ?>
<div class="create_help">
        <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>You can create a helptext in the<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        <a href="<?php echo $this->_tpl_vars['www_root']; ?>
/index.php?mod=templatemanager&amp;sub=admin&amp;action=editor&amp;file=<?php echo $this->_tpl_vars['template_of_module']; ?>
/templates/help.tpl&amp;tplmod=<?php echo $this->_tpl_vars['template_of_module']; ?>
">Templateeditor</a>
        <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?> now.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</div>
<?php endif; ?>
<!-- End Template help_not_found.tpl -->
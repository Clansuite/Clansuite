<?php /* Smarty version 2.6.25-dev, created on 2009-11-25 19:21:51
         compiled from pagination-generic.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'pagination-generic.tpl', 15, false),)), $this); ?>
<!-- Start Pagination -->
<div id="pagination">
        <!-- Pagination Icon -->
        <img src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/icons/page_edit.png" alt="" />

        <?php $this->assign('resultsInPage', $this->_tpl_vars['pager']->getResultsInPage()); ?>

        <?php if ($this->_tpl_vars['pager']->haveToPaginate() > 0): ?>

            <?php $this->assign('numResults', $this->_tpl_vars['pager']->getNumResults()); ?>
            <?php $this->assign('firstIndice', $this->_tpl_vars['pager']->getFirstIndice()); ?>
            <?php $this->assign('lastIndice', $this->_tpl_vars['pager']->getLastIndice()); ?>

                        <?php echo $this->_tpl_vars['pager_layout']->display('',true); ?>
 - <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Page<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <?php echo $this->_tpl_vars['pager']->getPage(); ?>
/<?php echo $this->_tpl_vars['pager']->getLastPage(); ?>
.

                        <span><?php $this->_tag_stack[] = array('t', array('1' => ($this->_tpl_vars['firstIndice']),'2' => ($this->_tpl_vars['lastIndice']),'3' => ($this->_tpl_vars['numResults']),'4' => ($this->_tpl_vars['resultsInPage']))); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Displaying Items %1 to %2 of %3 total (with %4 per page).<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>

        <?php elseif ($this->_tpl_vars['pager']->getResultsInPage() == 1): ?>

          <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Displaying 1 Item.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

        <?php elseif ($this->_tpl_vars['pager']->getNumResults() > 1): ?>

          <?php $this->_tag_stack[] = array('t', array('1' => ($this->_tpl_vars['resultsInPage']))); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Items 1 to %1 displayed.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
 
        <?php else: ?>

          
        <?php endif; ?>
</div>
<!-- End Pagination  -->
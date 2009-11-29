<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:54
         compiled from news%5Ctemplates%5Cwidget_archive.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'news\\templates\\widget_archive.tpl', 7, false),)), $this); ?>

<!-- Start News Archiv Widget from Module News -->

<div class="news_widget" id="widget_newsarchiv" width="100%">

    <h2 class="td_header"> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>News Archive<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>
    
    <div class="cell1">

        <?php $_from = $this->_tpl_vars['widget_archive']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['year'] => $this->_tpl_vars['year_archiv']):
?>
    <a href="<?php echo $this->_supers['server']['PHP_SELF']; ?>
?mod=news&action=archive&date=<?php echo $this->_tpl_vars['year']; ?>
"><?php echo $this->_tpl_vars['year']; ?>
</a>
    
                <?php $_from = $this->_tpl_vars['year_archiv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['month'] => $this->_tpl_vars['month_archiv']):
?>
        
            <br/>
            <a href="<?php echo $this->_supers['server']['PHP_SELF']; ?>
?mod=news&action=archive&date=<?php echo $this->_tpl_vars['year']; ?>
-<?php echo $this->_tpl_vars['month']; ?>
"><?php echo $this->_tpl_vars['month']; ?>
</a>
    
                        <?php $_from = $this->_tpl_vars['month_archiv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['parent'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['parent']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['entry']):
        $this->_foreach['parent']['iteration']++;
?>
                <?php if (($this->_foreach['parent']['iteration'] == $this->_foreach['parent']['total'])): ?>
                     (<?php echo $this->_foreach['parent']['total']; ?>
)
                <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>
        <?php endforeach; endif; unset($_from); ?>
        <br />
    <?php endforeach; endif; unset($_from); ?>
    
    </div>

</div>

<!-- End News Archiv Widget from Theme Newscore -->
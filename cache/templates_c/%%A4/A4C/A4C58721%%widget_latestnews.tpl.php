<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:54
         compiled from news%5Ctemplates%5Cwidget_latestnews.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'news\\templates\\widget_latestnews.tpl', 8, false),array('modifier', 'date_format', 'news\\templates\\widget_latestnews.tpl', 18, false),)), $this); ?>

<!-- Start News Widget from Module News -->

<table class="latestnews_widget" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="td_header" colspan="2">
            <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Recent news<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </td>
    </tr>
    <tr>
        <td class="td_header_small">Titel</td>
        <td class="td_header_small" width="70">Datum</td>
    </tr>
    <?php $_from = $this->_tpl_vars['widget_latestnews']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['news_item']):
?>
    <tr>
        <td class="cell1" ><a href="index.php?mod=news&action=showone&id=<?php echo $this->_tpl_vars['news_item']['news_id']; ?>
"><?php echo $this->_tpl_vars['news_item']['news_title']; ?>
</a></td>
        <td class="cell2" width="70"><?php echo ((is_array($_tmp=$this->_tpl_vars['news_item']['created_at'])) ? $this->_run_mod_handler('date_format', true, $_tmp) : smarty_modifier_date_format($_tmp)); ?>
</td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
</table>

<!-- End News Widget -->
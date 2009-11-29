<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:55
         compiled from quotes%5Ctemplates%5Cwidget_quotes.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'quotes\\templates\\widget_quotes.tpl', 8, false),)), $this); ?>

<!-- Start: Quotes Widget from Module Quotes  -->

<table class="quotes_widget_info" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="td_header" colspan="2">
            <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Quotes<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </td>
    </tr>
    <tr>
        <td class="cell1"><i>"<?php echo $this->_tpl_vars['quote']['quote_body']; ?>
"</i></td>
    </tr>
    <tr>
        <td class="cell1">by <?php echo $this->_tpl_vars['quote']['quote_author']; ?>
 <br/>  <?php echo $this->_tpl_vars['quote']['quote_source']; ?>
</td>
    </tr>
</table>

<!-- End: Quotes Widget Module Template -->
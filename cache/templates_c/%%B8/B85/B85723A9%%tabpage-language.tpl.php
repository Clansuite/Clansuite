<?php /* Smarty version 2.6.25-dev, created on 2009-11-15 15:00:29
         compiled from tabpage-language.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'tabpage-language.tpl', 11, false),)), $this); ?>
<table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

    
    <tr>
        <td class="td_header_small"  colspan="2">
            <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Default Language / Charset<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Standard Language<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </td>
        <td class="cell1" style="padding: 3px">
            <small><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Language Selection is based on browser-detection, but you can specify a default language as fallback.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></small><br />
            <input class="input_text" type="text" value="<?php if (isset ( $this->_tpl_vars['config']['language']['language'] )): ?><?php echo $this->_tpl_vars['config']['language']['language']; ?>
<?php endif; ?>" name="config[language][language]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Charset<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="<?php if (isset ( $this->_tpl_vars['config']['language']['outputcharset'] )): ?><?php echo $this->_tpl_vars['config']['language']['outputcharset']; ?>
<?php endif; ?>" name="config[language][outputcharset]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Timezone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="<?php if (isset ( $this->_tpl_vars['config']['language']['timezone'] )): ?><?php echo $this->_tpl_vars['config']['language']['timezone']; ?>
<?php endif; ?>" name="config[language][timezone]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>GMT Offset<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="<?php if (isset ( $this->_tpl_vars['config']['language']['gmtoffset'] )): ?><?php echo $this->_tpl_vars['config']['language']['gmtoffset']; ?>
<?php endif; ?>" name="config[language][gmtoffset]" />
        </td>
    </tr>
</table>
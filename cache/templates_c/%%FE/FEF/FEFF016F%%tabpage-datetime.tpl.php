<?php /* Smarty version 2.6.25-dev, created on 2009-11-15 15:00:30
         compiled from tabpage-datetime.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'tabpage-datetime.tpl', 11, false),array('modifier', 'dateformat', 'tabpage-datetime.tpl', 21, false),array('function', 'html_options', 'tabpage-datetime.tpl', 30, false),)), $this); ?>
<table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

    
    <tr>
        <td class="td_header_small"  colspan="2">
            <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Date & Timezone Settings<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Dateformat<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </td>
        <td class="cell1" style="padding: 3px">
            <small><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>You may provide the format in which dates are displayed. Example: d-m-Y. For an How-To read: http://us2.php.net/manual/en/function.date.php<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></small><br />
            <input class="input_text" type="text" value="<?php if (isset ( $this->_tpl_vars['config']['locale']['dateformat'] )): ?><?php echo $this->_tpl_vars['config']['locale']['dateformat']; ?>
<?php endif; ?>" name="config[locale][dateformat]" />
            Example: <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('dateformat', true, $_tmp) : smarty_modifier_dateformat($_tmp)); ?>

        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Default Timezone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </td>
        <td class="cell1" style="padding: 3px">
            <small><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Select the default timzone:<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></small><br />
            <?php echo smarty_function_html_options(array('name' => 'config[locale][timezone]','options' => $this->_tpl_vars['timezones'],'selected' => "´".($this->_tpl_vars['config']).".locale.timezone´",'separator' => '<br />'), $this);?>

        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Daylight Savings Time (Summertime)<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </td>
        <td class="cell1" style="padding: 3px">
            <small><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>If you enable this, the Daylight Savings Time (or Summertime) corrects the time for the above <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Default Timezone<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>. The clock is advanced an hour, so that afternoons have more daylight and mornings have less:<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></small><br />
            <input type="radio" value="1" name="config[locale][daylight_saving]" <?php if (isset ( $this->_tpl_vars['config']['locale']['daylight_saving'] ) && $this->_tpl_vars['config']['locale']['daylight_saving'] == 1): ?>checked="checked"<?php endif; ?> /> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>yes [Summertime = Default Timezone + 1 hour]<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
            <input type="radio" value="0" name="config[locale][daylight_saving]" <?php if (isset ( $this->_tpl_vars['config']['locale']['daylight_saving'] ) && $this->_tpl_vars['config']['locale']['daylight_saving'] == 0): ?>checked="checked"<?php endif; ?> /> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>no [Normal Time = Default Timezone]<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </td>
    </tr>
</table>
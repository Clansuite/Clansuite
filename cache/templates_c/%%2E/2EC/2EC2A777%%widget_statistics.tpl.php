<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:55
         compiled from statistics%5Ctemplates%5Cwidget_statistics.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'statistics\\templates\\widget_statistics.tpl', 5, false),)), $this); ?>

<table cellpadding="3" cellspacing="0" border="0" width="100%" align="center">
    <tr>
        <td class="td_header" colspan="2"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Statistics<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
    </tr>
    <tr>
        <td class="cell1">
            Online:
        </td>
        <td class="cell2">
            <?php echo $this->_tpl_vars['stats']['online']; ?>

        </td>
    </tr>
    <tr>
        <td class="cell1">
             - Users:
        </td>
        <td class="cell2">
                          <?php echo $this->_tpl_vars['stats']['authed_users']; ?>

        </td>
    </tr>
    <tr>
        <td class="cell1">
             - Guests:
        </td>
        <td class="cell2">
             <?php echo $this->_tpl_vars['stats']['guest_users']; ?>

        </td>
    </tr>
    <tr>
        <td class="cell1">
             - Max Online:
        </td>
        <td class="cell2">
             <?php echo $this->_tpl_vars['stats']['max_visitor']; ?>

        </td>
    </tr>
    <tr>
        <td class="cell1">
              Today:
        </td>
        <td class="cell2">
              <?php echo $this->_tpl_vars['stats']['today_impressions']; ?>

        </td>
    </tr>
    <tr>
        <td class="cell1">
              Yesterday:
        </td>
        <td class="cell2">
              <?php echo $this->_tpl_vars['stats']['yesterday_impressions']; ?>

        </td>
    </tr>
    <tr>
        <td class="cell1">
              Month:
        </td>
        <td class="cell2">
              <?php echo $this->_tpl_vars['stats']['month_impressions']; ?>

        </td>
    </tr>
    <tr>
        <td class="cell1">
              Total Impressions:
        </td>
        <td class="cell2">
              <?php echo $this->_tpl_vars['stats']['all_impressions']; ?>

        </td>
    </tr>
</table>
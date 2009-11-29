<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:54
         compiled from menu%5Ctemplates%5Cwidget_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'move_to', 'menu\\templates\\widget_menu.tpl', 1, false),array('block', 't', 'menu\\templates\\widget_menu.tpl', 8, false),)), $this); ?>
<?php $this->_tag_stack[] = array('move_to', array('target' => 'pre_head_close')); $_block_repeat=true;smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <script src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/javascript/XulMenu.js" type="text/javascript"></script>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<table id="Frontend-Menu-1" cellspacing="0" cellpadding="0" class="XulMenu" width="100%">
<tr>
    <td class="td_header">
        <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Menu<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    </td>
</tr>
<tr>
    <td>
        <a class="button" href="javascript:void(0)">Public<img class="arrow" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/arrow1.gif" width="4" height="7" alt="" /></a>

        <div class="section">
            <a class="item" href="javascript:void(0)"><img class="pic" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/symbols/modules.png" border="0" width="16" height="16" alt="" />Modules<img class="arrow" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/arrow1.gif" width="4" height="7" alt="" /></a>
              <div class="section">
                  <a class="item" href="index.php"><img class="pic" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/crystal_clear/16/agt_home.png" border="0" width="16" height="16" alt=""/>Main</a>
                  <a class="item" href="index.php?mod=news"><img class="pic" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/symbols/news.png" border="0" width="16" height="16" alt=""/>News</a>
                  <a class="item" href="index.php?mod=news&amp;action=archive"><img class="pic" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/symbols/news.png" border="0" width="16" height="16" alt=""/>Newsarchiv</a>
                  <a class="item" href="index.php?mod=guestbook"><img class="pic" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/symbols/news.png" border="0" width="16" height="16" alt=""/>Guestbook</a>
                  <a class="item" href="index.php?mod=board"><img class="pic" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/symbols/news.png" border="0" width="16" height="16" alt=""/>Board</a>
                  <a class="item" href="index.php?mod=serverlist"><img class="pic" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/symbols/news.png" border="0" width="16" height="16" alt=""/>Serverlist</a>
                  <a class="item" href="index.php?mod=users">Users</a>
                  <a class="item" href="index.php?mod=staticpages&amp;page=credits"><img class="pic" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/icons/information.png" border="0" width="16" height="16" alt=""/>Credits</a>
                  <a class="item" href="index.php?mod=staticpages&amp;action=overview"><img class="pic" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/symbols/news.png" border="0" width="16" height="16" alt="" />Static Pages Overview</a>
               </div>

            <a class="item" href="index.php?mod=users"><img class="pic" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/icons/user_suit.png" border="0" width="16" height="16" alt=""/>Users<img class="arrow" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/arrow1.gif" width="4" height="7" alt="" /></a>
              <div class="section">
                  <a class="item" href="index.php?mod=account"><img class="pic" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/symbols/news.png" border="0" width="16" height="16" alt=""/>Login</a>
                  <a class="item" href="index.php?mod=account"><img class="pic" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/tango/16/System-log-out.png" border="0" width="16" height="16" alt=""/>Logout</a>
              </div>
        </div>

        <a class="button" href="index.php?mod=controlcenter">Control Center (CC)</a>

    </td>
</tr>
</table>

<!-- XUL Menu Init -->
<script type="application/javascript">
//<![CDATA[
    var menu1 = new XulMenu("Frontend-Menu-1");
    menu1.type = "vertical";
    menu1.position.level1.top = 0;
    menu1.arrow1 = "<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/arrow1.gif";
    menu1.arrow2 = "<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/arrow2.gif";
    menu1.init();
//]]>
</script>
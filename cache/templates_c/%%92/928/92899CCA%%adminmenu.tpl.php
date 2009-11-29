<?php /* Smarty version 2.6.25-dev, created on 2009-11-25 19:21:54
         compiled from menu/templates/adminmenu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'move_to', 'menu/templates/adminmenu.tpl', 1, false),array('block', 't', 'menu/templates/adminmenu.tpl', 48, false),array('function', 'load_module', 'menu/templates/adminmenu.tpl', 24, false),)), $this); ?>
<?php $this->_tag_stack[] = array('move_to', array('target' => 'pre_head_close')); $_block_repeat=true;smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['www_root']; ?>
/modules/menu/css/menu.css" />
<script type="text/javascript" src="<?php echo $this->_tpl_vars['www_root']; ?>
/modules/menu/javascript/XulMenu.js"></script>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<!-- Start: Adminmenu (Modules->Menu)-->

<script type="text/javscript">
/* preload images */
var arrow1 = new Image(4, 7);
arrow1.src =  "<?php echo $this->_tpl_vars['www_root']; ?>
/modules/menu/images/arrow1.gif";
var arrow2 = new Image(4, 7);
arrow2.src =  "<?php echo $this->_tpl_vars['www_root']; ?>
/modules/menu/images/arrow2.gif";
</script>

<div class="menugradient">

    <div class="bar">

        <!-- XULMenu Table - Important is the id tag, it's the selector used by the JS. -->
        <table id="Adminmenu" cellspacing="0" cellpadding="0" class="XulMenu" width="100%">
            <tr>
                <!-- module-include: admin menueditor get_html_div -->
                <?php echo smarty_function_load_module(array('name' => 'menu','sub' => 'admin','action' => 'get_html_div'), $this);?>

            </tr>
        </table>

        <script type="text/javascript">
            var Adminmenu = new XulMenu("Adminmenu");
            Adminmenu.arrow1 = "<?php echo $this->_tpl_vars['www_root']; ?>
/modules/menu/images/arrow1.gif";
            Adminmenu.arrow2 = "<?php echo $this->_tpl_vars['www_root']; ?>
/modules/menu/images/arrow2.gif";
            Adminmenu.init();
        </script>

    </div>

    <div class="adminmenu-rightside">

        <a class="itembtn" href="index.php?mod=account&amp;sub=admin&amp;action=usercenter">
            <img style="position:relative; top: 4px" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/icons/user_suit.png" border="0" alt="user-image" width="16" height="16" />
            &nbsp;<?php echo $this->_supers['session']['user']['nick']; ?>

        </a>

        &nbsp;

        <a href="index.php" class="itembtn">
            <img style="position:relative; top: 4px" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/icons/layout_header.png" border="0" alt="logout-image" width="16" height="16" />
            &nbsp;<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Show Frontpage<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </a>

        &nbsp;

        <a href="index.php?mod=account&amp;action=logout" class="itembtn">
            <img style="position:relative; top: 4px" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/tango/16/System-log-out.png" border="0" alt="logout-image" width="16" height="16" />
            &nbsp;<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Logout<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
        </a>

      </div>

</div>
<!-- End: AdminMenu (Modules->Menu) -->
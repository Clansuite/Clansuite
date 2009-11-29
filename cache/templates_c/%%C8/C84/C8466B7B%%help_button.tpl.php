<?php /* Smarty version 2.6.25-dev, created on 2009-11-25 19:21:54
         compiled from help/help_button.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'help/help_button.tpl', 21, false),)), $this); ?>
<?php echo '

    <!-- (jQuery) Javascript for the Help Toggle -->

    <script type="text/javascript">

        $(document).ready( function(){
            $("#help").hide();
            $("#help-toggler").click( function(){
                $("#help").slideToggle("normal");
            });
        });

    </script>

'; ?>


<!-- Help Icon -->
<div id="help-toggler">
    <img style="margin-bottom: -3px;" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/icons/help.png" alt="Help Toggle" />
    <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Help<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</div>

<!-- Help Text -->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help/help.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
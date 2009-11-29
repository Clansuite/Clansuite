<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:54
         compiled from account%5Ctemplates%5Cwidget_login.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'move_to', 'account\\templates\\widget_login.tpl', 3, false),array('block', 't', 'account\\templates\\widget_login.tpl', 31, false),)), $this); ?>

<?php $this->_tag_stack[] = array('move_to', array('target' => 'pre_head_close')); $_block_repeat=true;smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<script src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/javascript/webtoolkit.sha1.js" type="application/javascript"></script>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php echo '
    <script>
    function hashLoginPassword(theForm)
    {
        theForm.password.value = SHA1(theForm.password.value);
    }
    </script>
'; ?>



    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
             <td class="td_header" colspan="2">
                <form action="index.php?mod=account&action=login"
                      method="post"
                      id="block_login_form"
                      accept-charset="UTF-8"
                      onsubmit="hashLoginPassword(this);"
                />
             <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Login<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
             </td>
        </tr>
        <?php if (isset ( $this->_tpl_vars['config']['login']['login_method'] ) && $this->_tpl_vars['config']['login']['login_method'] == 'email'): ?>
        <tr>
            <td class="cell1"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Email:<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
            <td class="cell2"><input class="input_text" type="text" name="email"
                                     onblur="if(this.value=='')this.value=this.defaultValue;"
                                     onfocus="if(this.value==this.defaultValue)this.value='';"
                                     value="email" /></td>
        </tr>
        <?php endif; ?>
        <?php if (isset ( $this->_tpl_vars['config']['login']['login_method'] ) && $this->_tpl_vars['config']['login']['login_method'] == 'nick'): ?>
        <tr>
            <td class="cell1"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Nickname:<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
            <td class="cell2"><input class="input_text" type="text" name="nickname"
                                     onblur="if(this.value=='')this.value=this.defaultValue;"
                                     onfocus="if(this.value==this.defaultValue)this.value='';"
                                     value="nickname" /></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td class="cell1"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Password:<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
            <td class="cell2"><input class="input_text" type="password" name="password" id="block_password"
                                     onblur="if(this.value=='')this.value=this.defaultValue;"
                                     onfocus="if(this.value==this.defaultValue)this.value='';"
                                     value="******" autocomplete="off" /></td>
        </tr>
        <tr>
            <td colspan="2" class="cell1">
                <input type="checkbox" name="remember_me" value="1" <?php if (isset ( $this->_supers['post']['remember_me'] )): ?> checked="checked" <?php endif; ?> />
                <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Remember me ( Cookie )<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center" class="cell1">
                <input type="submit" name="submit" id="login_button" value="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Login<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" class="ButtonGreen" />
            </td>
        </tr>
        <tr>
            <td colspan="2" class="cell1">
                <a href="index.php?mod=account&action=register"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Not yet registered ?<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a> <br />
                <a href="index.php?mod=account&action=forgot_password"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Forgot password ?<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a> <br />
                <a href="index.php?mod=account&action=activation_email"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Did not get an activation email ?<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a>
                </form>
            </td>
        </tr>
    </table>
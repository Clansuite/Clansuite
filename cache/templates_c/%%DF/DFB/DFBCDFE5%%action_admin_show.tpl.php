<?php /* Smarty version 2.6.25-dev, created on 2009-11-22 00:33:46
         compiled from C:/xampp/htdocs/projects/Clansuite/modules/users/templates/action_admin_show.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'move_to', 'C:/xampp/htdocs/projects/Clansuite/modules/users/templates/action_admin_show.tpl', 4, false),array('block', 't', 'C:/xampp/htdocs/projects/Clansuite/modules/users/templates/action_admin_show.tpl', 17, false),array('function', 'modulenavigation', 'C:/xampp/htdocs/projects/Clansuite/modules/users/templates/action_admin_show.tpl', 16, false),array('function', 'pagination', 'C:/xampp/htdocs/projects/Clansuite/modules/users/templates/action_admin_show.tpl', 27, false),array('function', 'columnsort', 'C:/xampp/htdocs/projects/Clansuite/modules/users/templates/action_admin_show.tpl', 34, false),array('modifier', 'date_format', 'C:/xampp/htdocs/projects/Clansuite/modules/users/templates/action_admin_show.tpl', 49, false),)), $this); ?>

<?php $this->_tag_stack[] = array('move_to', array('target' => 'pre_head_close')); $_block_repeat=true;smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <script type="text/javascript" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/javascript/clip.js"></script>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


<?php echo smarty_function_modulenavigation(array(), $this);?>

<div class="ModuleHeading"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Users - Administration<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>
<div class="ModuleHeadingSmall"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>You can create Users, edit and delete them.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>

<form action="index.php?mod=controlcenter&sub=users&amp;action=delete" method="post" accept-charset="UTF-8">

    <table cellpadding="0" cellspacing="0" border="0" align="center">

            <tr class="tr_row1">
                <td height="20" colspan="8" align="right">

                    <?php echo smarty_function_pagination(array('type' => 'alphabet'), $this);?>

                    <?php echo smarty_function_pagination(array(), $this);?>


                </td>
            </tr>

            <tr class="tr_header">
                <td width="1%" align="center">  <?php echo smarty_function_columnsort(array('html' => '#'), $this);?>
           </td>
                <td align="center">             <?php echo smarty_function_columnsort(array('html' => 'Nick'), $this);?>
         </td>
                <td align="center">             <?php echo smarty_function_columnsort(array('html' => 'Email'), $this);?>
        </td>
                <td align="center">             <?php echo smarty_function_columnsort(array('html' => 'Last Visit'), $this);?>
   </td>
                <td align="center">             <?php echo smarty_function_columnsort(array('html' => 'Joined'), $this);?>
       </td>
                <td align="center">             <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Action<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>                    </td>
                <td align="center">             <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Select<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>                    </td>
            </tr>

            <?php $_from = $this->_tpl_vars['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['schluessel'] => $this->_tpl_vars['wert']):
?>

                <tr class="tr_row1">
                    <td align="center">         <?php echo $this->_tpl_vars['wert']['user_id']; ?>
                          </td>
                    <td>                        <?php echo $this->_tpl_vars['wert']['nick']; ?>
                             </td>
                    <td>                        <?php echo $this->_tpl_vars['wert']['email']; ?>
                            </td>
                    <td>                        <?php echo ((is_array($_tmp=$this->_tpl_vars['wert']['timestamp'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
 </td>
                    <td>                        <?php echo ((is_array($_tmp=$this->_tpl_vars['wert']['joined'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y") : smarty_modifier_date_format($_tmp, "%d.%m.%Y")); ?>
    </td>
                    <td align="center">

                       <input class="ButtonOrange" type="button" value="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Edit Profile<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" />

                       
                    </td>
                    <td align="center" width="1%">
                        <input type="hidden" name="ids[]" value="<?php echo $this->_tpl_vars['wert']['user_id']['0']; ?>
" />
                        <input name="delete[]" type="checkbox" value="<?php echo $this->_tpl_vars['wert']['user_id']['0']; ?>
" />
                    </td>
                </tr>

            <?php endforeach; endif; unset($_from); ?>

            <tr class="tr_row1">
               <td height="20" colspan="8" align="right">

                    <input class="ButtonGreen" type="button" value="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Create new user<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" onclick='<?php echo 'Dialog.info({url: "index.php?mod=controlcenter&amp;sub=users&amp;action=create", options: {method: "get"}}, {className: "alphacube", width:370, height: 250});'; ?>
' />
                    <input class="Button" name="reset" type="reset" value="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Reset<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" />
                    <input class="ButtonRed" type="submit" name="delete_text" value="<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Delete Selected Users<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" />

                </td>
            </tr>

            <tr class="tr_row1">
                <td height="20" colspan="8" align="right">

                    <?php echo smarty_function_pagination(array('type' => 'alphabet'), $this);?>

                    <?php echo smarty_function_pagination(array(), $this);?>


                </td>
            </tr>
    </table>

</form>
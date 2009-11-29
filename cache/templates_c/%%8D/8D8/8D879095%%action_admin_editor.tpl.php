<?php /* Smarty version 2.6.25-dev, created on 2009-11-15 15:09:08
         compiled from C:/xampp/htdocs/projects/Clansuite/modules/templatemanager/templates/action_admin_editor.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'move_to', 'C:/xampp/htdocs/projects/Clansuite/modules/templatemanager/templates/action_admin_editor.tpl', 3, false),array('block', 't', 'C:/xampp/htdocs/projects/Clansuite/modules/templatemanager/templates/action_admin_editor.tpl', 25, false),)), $this); ?>

<?php $this->_tag_stack[] = array('move_to', array('target' => 'pre_head_close')); $_block_repeat=true;smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['www_root']; ?>
/libraries/codemirror/js/codemirror.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['www_root']; ?>
/libraries/codemirror/js/mirrorframe.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/javascript/codemirror_config.js"></script>

<?php echo '
<!-- Line Numbers for CodeMirror : crappy solution, because it depends on the correct line heigth -->
<style type="text/css">
      .CodeMirror-line-numbers {
        width: 2.2em;
        color: #aaa;
        background-color: #fff;
        text-align: right;
        padding-right: .3em;
        font-size: 10pt;
        font-family: monospace;
        padding-top: .4em;
      }
    </style>
'; ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<div class="ModuleHeading"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Templatemanager - Editor<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>
<div class="ModuleHeadingSmall"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>You can create and edit your templates here.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>

<?php if (isset ( $this->_tpl_vars['templateeditor_newfile'] ) && ( $this->_tpl_vars['templateeditor_newfile'] ) == 1): ?>
    <div class="ModuleHeadingSmall"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>You are about to create: <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <font color="red"> <?php echo $this->_tpl_vars['templateeditor_filename']; ?>
 </font></div>
<?php else: ?>
    <div class="ModuleHeadingSmall"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>You are editing: <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <font color="red"> <?php echo $this->_tpl_vars['templateeditor_filename']; ?>
 </font></div>
<?php endif; ?>

<table width="100%">
<tr>
    <td width="75%">

        <form action="index.php?mod=templatemanager&sub=admin&action=save" method="post">

                        <textarea rows="10" cols="80" id="codecontent" name="templateeditor_textarea"><?php echo $this->_tpl_vars['templateeditor_textarea']; ?>
</textarea>

            <br />

            <div align="right">

                                <input type="hidden" name="templateeditor_filename" value="<?php echo $this->_tpl_vars['templateeditor_filename']; ?>
" />

                                <input type="hidden" name="templateeditor_modulename" value="<?php echo $this->_tpl_vars['templateeditor_modulename']; ?>
" />

                                <input class="ButtonGreen" type="submit" value="Save" />

                                <input class="Button" type="reset" value="Reset" />
            </div>

        </form>

    </td>
    <td>
            Options

            <br />

            Create for
            a) module/templates
            or
            b) theme

            <br />

            Variables and Plugins in Use (In Order Of Use):

            <br />

            Useable Placeholders:Options
    </td>
</tr>
</table>
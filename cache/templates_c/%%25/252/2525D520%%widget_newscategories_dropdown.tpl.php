<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:54
         compiled from news%5Ctemplates%5Cwidget_newscategories_dropdown.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'news\\templates\\widget_newscategories_dropdown.tpl', 7, false),)), $this); ?>

<!-- Start Start Widget NewsCategoriesDropDown from Module News -->

<div class="news_widget" id="widget_newscategoriesdropdown" width="100%">

    <h2 class="td_header"> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>News Categories<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>
    
    <div class="cell1">
    
        <form>
          <label>
              <select name="newscatsdropdown" id="newscatsdropdown" size=1
                      onchange="top.location.href=this.options[this.selectedIndex].value;">

                            <option>- <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Choose<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> -</option>

                            <option value="<?php echo $this->_tpl_vars['www_root']; ?>
/index.php?mod=news&action=show">- <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>All<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> -</option>

              <?php $_from = $this->_tpl_vars['widget_newscategories_dropdown']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['newscategory']):
?>

                <option value="<?php echo $this->_tpl_vars['www_root']; ?>
/index.php?mod=news&action=show&cat=<?php echo $this->_tpl_vars['newscategory']['cat_id']; ?>
">
                    <?php echo $this->_tpl_vars['newscategory']['CsCategories']['name']; ?>
 (<?php echo $this->_tpl_vars['newscategory']['sum_news']; ?>
)
                </option>
              <?php endforeach; endif; unset($_from); ?>

              </select>
            </label>
        </form>
      
      </div>
            
</div>

<!-- End Start Widget NewsCategoriesDropDown from Module News -->
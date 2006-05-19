<?php /* Smarty version 2.6.13, created on 2006-05-19 18:50:47
         compiled from news.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "file:../header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- BEGIN news -->
<h1>News</h1>

<?php $_from = $this->_tpl_vars['news']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['news']):
?>

<div class="news">
  <a name="news-<?php echo $this->_tpl_vars['news']['news_id']; ?>
"></a>
  <div class="title"><?php echo $this->_tpl_vars['news']['news_title']; ?>
 - <?php echo $this->_tpl_vars['cat_name']; ?>
 </div>
  <div class="image"><?php if (isset ( $this->_tpl_vars['news']['cat_image_url'] )): ?> <img src="<?php  print BASE_URL;   echo $this->_tpl_vars['news']['cat_image_url']; ?>
" alt="<?php echo $this->_tpl_vars['news']['cat_image_url']; ?>
"/> <?php endif; ?></div>
  <div class="submitted"><?php echo $this->_tpl_vars['modullanguage']['submittedby']; ?>
 <a href="users/view.php?user_id=<?php echo $this->_tpl_vars['news']['user_id']; ?>
"><?php echo $this->_tpl_vars['nick']; ?>
</a> <?php echo $this->_tpl_vars['corelanguage']['on']; ?>
 <?php echo $this->_tpl_vars['news']['news_added']; ?>
</div>
  <div class="body"><?php echo $this->_tpl_vars['news']['news_body']; ?>
</div>
  <div class="comments">
      <strong>&raquo;</strong>
      <a href="news_comments.php?news_id=<?php echo $this->_tpl_vars['news']['news_id']; ?>
"><?php echo $this->_tpl_vars['news']['nr_news_comments']; ?>
 <?php echo $this->_tpl_vars['corelanguage']['comments']; ?>
</a>
      <!-- IF lastcomment_by -->
	<div><?php echo $this->_tpl_vars['modullanguage']['lastcomment']; ?>
: <span><?php echo $this->_tpl_vars['news']['lastcomment_by']; ?>
</span></div>
      <!-- ENDIF lastcomment_by -->
  </div>
</div>

<?php endforeach; endif; unset($_from); ?>
<!-- END news -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "file:../footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
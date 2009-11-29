<?php /* Smarty version 2.6.25-dev, created on 2009-11-22 00:33:47
         compiled from pagination-alphabet.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'array', 'pagination-alphabet.tpl', 3, false),array('function', 'currentmodule', 'pagination-alphabet.tpl', 7, false),)), $this); ?>
<div class="alphabet_navigation">

    <?php echo smarty_function_array(array('name' => 'alphabet','values' => "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z",'explode' => 'true','delimiter' => ","), $this);?>


    <?php $_from = $this->_tpl_vars['alphabet']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['character']):
?>

        <a href="<?php echo $this->_tpl_vars['www_root']; ?>
?mod=<?php echo smarty_function_currentmodule(array(), $this);?>
&sub=admin&defaultCol=1&defaultSort=asc&searchletter=<?php echo $this->_tpl_vars['character']; ?>
"><?php echo $this->_tpl_vars['character']; ?>
</a>
         &nbsp;

    <?php endforeach; endif; unset($_from); ?>

</div>
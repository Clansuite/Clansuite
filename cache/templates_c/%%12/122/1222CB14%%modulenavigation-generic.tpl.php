<?php /* Smarty version 2.6.25-dev, created on 2009-11-25 19:21:51
         compiled from modulenavigation-generic.tpl */ ?>


<?php echo '
<style type="text/css">
    #tabsB {
      float:right;
      /*width:100%;*/
      /*background:#F4F4F4;*/
      font-size:93%;
      line-height:normal;
      margin-top: -10px;
      }
    #tabsB ul {
      margin:0;
      padding:10px 10px 0 50px;
      list-style:none;
      }
    #tabsB li {
      display:inline;
      margin:0;
      padding:0;
      }
    #tabsB a {
      float:left;
      background:url("'; ?>
<?php echo $this->_tpl_vars['www_root_themes']; ?>
<?php echo '/core/css/tabs/tableftB.gif") no-repeat left top;
      margin:0;
      padding:0 0 0 4px;
      text-decoration:none;
      }
    #tabsB a span {
      float:left;
      display:block;
      background:url("'; ?>
<?php echo $this->_tpl_vars['www_root_themes']; ?>
<?php echo '/core/css/tabs/tabrightB.gif") no-repeat right top;
      padding:5px 15px 4px 6px;
      color:#666;
      }
    /* Commented Backslash Hack hides rule from IE5-Mac \\*/
    #tabsB a span {float:none;}
    /* End IE5-Mac hack */
    #tabsB a:hover span {
      color:#000;
      }
    #tabsB a:hover {
      background-position:0% -42px;
      }
    #tabsB a:hover span {
      background-position:100% -42px;
      }
</style>
'; ?>


<div id="tabsB">
    <ul>
        <?php $_from = $this->_tpl_vars['modulenavigation']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['navkey'] => $this->_tpl_vars['navitem']):
?>

        <li>
            <a href="<?php echo $this->_tpl_vars['navitem']['url']; ?>
" title="Systeminfo"> <span><?php echo $this->_tpl_vars['navitem']['name']; ?>
</span> </a>
        </li>

        <?php endforeach; endif; unset($_from); ?>
    </ul>
</div>
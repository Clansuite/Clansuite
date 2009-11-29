<?php /* Smarty version 2.6.25-dev, created on 2009-11-25 19:21:54
         compiled from help/help.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'help', 'help/help.tpl', 13, false),array('block', 't', 'help/help.tpl', 22, false),)), $this); ?>
<!-- Module Help - help.tpl -->
<div id="help_wrapper">

    <div id="help" class="admin_help">

    <ul start="1" type="I">
    <h3>Help Overview</h3>

    <!-- 1) Help for this specific module section displayed. -->
    <li>
        Help for this Module
        <p>
            <?php echo smarty_function_help(array(), $this);?>

        </p>
    </li>

    <!-- 2) Help in general -->
    <li>
        Help & Support in general
        <p>
           <ul start="1" type="1">
               <li><a target="_blank" href="http://www.clansuite.com/documentation/user/manual/de"> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Documentation: User-Manual<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> </a></li>
               <li><a target="_blank" href="http://forum.clansuite.com/index.php/board,4.0.html"> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Clansuite Forum: Support & Troubleshooting<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> </a></li>
               <li><a target="_blank" href="teamspeak://clansuite.com:8000?channel=clansuite%20Admins?subchannel=clansuite%20Support"> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Get Clansuite Support via Teamspeak<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> </a></li>
           </ul>
           <br />
        </p>
    </li>

    <!-- 3) Help Wiki -->
    <li>
        Get Live Support
        <p>
           <!-- http://www.LiveZilla.net Chat Button Link Code -->
           <div style="padding-left: 20px;">
               <a href="javascript:void(window.open('http://www.clansuite.com/livezilla/livezilla.php?reset=true','','width=600,height=600,left=0,top=0,resizable=yes,menubar=no,location=yes,status=yes,scrollbars=yes'))">
               <img src="http://www.clansuite.com/livezilla/image.php?id=04" width="160" height="40" border="0" alt="Clansuite Live Support">
               </a>
           <noscript>
               <div>
               <a href="http://www.clansuite.com/livezilla/livezilla.php?reset=true" target="_blank">Start Live Help Chat</a>
               </div>
           </noscript>
           </div>
           <!-- http://www.LiveZilla.net Chat Button Link Code -->

           <?php if (isset ( $this->_tpl_vars['helptracking'] ) && ( $this->_tpl_vars['helptracking'] == true )): ?>
               <div style="padding-left: 40px; margin-top:2px;"><a href="http://www.clansuite.com/livezilla/livezilla.php" target="_blank" title="Clansuite Live Support" style="font-size:10px;color:#bfbfbf;text-decoration:none;font-family:verdana,arial,tahoma;">Help Tracking enabled.</a></div>
               <!-- http://www.LiveZilla.net Tracking Code -->
               <div id="livezilla_tracking" style="display:none"></div>
               <script type="text/javascript">var script = document.createElement("script");script.type="text/javascript";var src = "http://www.clansuite.com/livezilla/server.php?request=track&output=jcrpt&reset=true&nse="+Math.random();setTimeout("script.src=src;document.getElementById('livezilla_tracking').appendChild(script)",1);</script>
               <!-- http://www.LiveZilla.net Tracking Code -->
           <?php endif; ?>

           
        </p>
     </li>

    </ul>

    </div>

</div>
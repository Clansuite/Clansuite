<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:54
         compiled from matches%5Ctemplates%5Cwidget_nextmatches.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'move_to', 'matches\\templates\\widget_nextmatches.tpl', 7, false),array('block', 't', 'matches\\templates\\widget_nextmatches.tpl', 23, false),)), $this); ?>
 
<!-- Start Widget Nextmatches from Module Matches -->

<div class="news_widget" id="widget_nextmatches" width="100%">

<?php $this->_tag_stack[] = array('move_to', array('target' => 'pre_head_close')); $_block_repeat=true;smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

<link rel="stylesheet" href="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/css/jquery-easyslider.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/javascript/jquery/jquery.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/javascript/jquery/jquery.easySlider1.5.js"></script>

<?php echo '
     <script type="text/javascript">
     var $j = jQuery.noConflict();
         $j(document).ready(function(){
            $j("#nextmatches_slider").easySlider();
        });
    </script>
'; ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

    <h2 class="td_header"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Next Matches<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>


<!-- Start Nextmatches-Slider from Module Matches -->
<div id="nextmatches_slider">

	<ul>
		        <li>
            <table>
                <tr>
                    <td>1111$match.team1name<br /><img class="logo_left" src="$match.team1logo" alt="" /></td>
                    <td><span class="team_divider"> vs </span><br />$match.matchtime</td>
                    <td>$match.team1name<br /><img class="logo_right" src="$match.team1logo" alt="" /></td>
                </tr>
            </table>
        </li>

        <li>
            <table>
                <tr>
                    <td>2222$match.team1name<br /><img class="logo_left" src="$match.team1logo" alt="" /></td>
                    <td><span class="team_divider"> vs </span><br />$match.matchtime</td>
                    <td>$match.team1name<br /><img class="logo_right" src="$match.team1logo" alt="" /></td>
                </tr>
            </table>
        </li>
			</ul>

</div>
<!-- End Nextmatches-Slider from Module Matches -->
</div>

<!-- End Widget Nextmatches -->
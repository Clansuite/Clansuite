<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:54
         compiled from gallery%5Ctemplates%5Cwidget_gallery.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'move_to', 'gallery\\templates\\widget_gallery.tpl', 1, false),)), $this); ?>
<?php $this->_tag_stack[] = array('move_to', array('target' => 'pre_head_close')); $_block_repeat=true;smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/javascript/mootools/mootools.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/javascript/mootools/mootools-more.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/css/mooflow/MooFlow.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/css/milkbox/milkbox.css" />
<script type="text/javascript" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/javascript/mootools/MooFlow.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/javascript/mootools/milkbox.js"></script>

<?php echo '
<script type="text/javascript">
    var myMooFlowPage = {
        start: function(){
            var mf = new MooFlow($(\'MooFlow\'), {
                startIndex: 5,
                useCaption: false,
                useMouseWheel: true,
                useKeyInput: true,
                bgColor: \'transparent\',
                useViewer: true,
                onClickView: function(obj){
                    Milkbox.showThisImage(obj.href, obj.title);
                }
            });
        }
    };
    window.addEvent(\'domready\', myMooFlowPage.start);
</script>
'; ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_move_to($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<div class="td_header">Gallery</div>
<div class="cell1">
<div id="MooFlowWrapper" style="margin-top: 10px; visibility: hidden;">
    <div id="MooFlow">
        <a href="uploads/images/gallery/at_symbol.jpg"><img src="uploads/images/gallery/at_symbol.jpg" title="@2" alt="Ein @-Symbol" /></a>
        <a href="uploads/images/gallery/stimme_von_oben.jpg"><img src="uploads/images/gallery/stimme_von_oben.jpg" title="Stimme von oben" alt="Lautsprecherturm" /></a>
        <a href="uploads/images/gallery/farbraum.jpg"><img src="uploads/images/gallery/farbraum.jpg" title="Farbraum Farbf�cher" alt="A deginer must have." /></a>
        <a href="uploads/images/gallery/tropfen.jpg"><img src="uploads/images/gallery/tropfen.jpg" title="Lichtflu�" alt="Tropfen" /></a>
        <a href="uploads/images/gallery/kunst.jpg"><img src="uploads/images/gallery/kunst.jpg" title="Tja..." alt="...aber ratet mal was das is.." /></a>
        <a href="uploads/images/gallery/platsch.jpg"><img src="uploads/images/gallery/platsch.jpg" title="Platsch" alt="Platsch - Der Wassertropfen" /></a>
        <a href="uploads/images/gallery/raetsel_1.jpg"><img src="uploads/images/gallery/raetsel_1.jpg" title="R�tsel" alt="Micro" /></a>
        <a href="uploads/images/gallery/raetsel_2.jpg"><img src="uploads/images/gallery/raetsel_2.jpg" title="Was ist das?" alt="Rutsche?" /></a>
    </div>
</div>
</div>
{move_to target="pre_head_close"}
<script type="text/javascript" src="{$www_root_themes_core}/javascript/mootools/mootools.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}/javascript/mootools/mootools-more.js"></script>
<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/css/mooflow/MooFlow.css" />
<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/css/milkbox/milkbox.css" />
<script type="text/javascript" src="{$www_root_themes_core}/javascript/mootools/MooFlow.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}/javascript/mootools/milkbox.js"></script>

{literal}
<script type="text/javascript">
    var myMooFlowPage = {
        start: function(){
            var mf = new MooFlow($('MooFlow'), {
                startIndex: 5,
                useCaption: false,
                useMouseWheel: true,
                useKeyInput: true,
                bgColor: 'transparent',
                useViewer: true,
                onClickView: function(obj){
                    Milkbox.showThisImage(obj.href, obj.title);
                }
            });
        }
    };
    window.addEvent('domready', myMooFlowPage.start);
</script>
{/literal}
{/move_to}

<div class="td_header">Gallery</div>
<div class="cell1">
<div id="MooFlowWrapper" style="margin-top: 10px; visibility: hidden;">
    <div id="MooFlow">
        <a href="uploads/images/gallery/at_symbol.jpg"><img src="uploads/images/gallery/at_symbol.jpg" title="@2" alt="Ein @-Symbol" /></a>
        <a href="uploads/images/gallery/stimme_von_oben.jpg"><img src="uploads/images/gallery/stimme_von_oben.jpg" title="Stimme von oben" alt="Lautsprecherturm" /></a>
        <a href="uploads/images/gallery/farbraum.jpg"><img src="uploads/images/gallery/farbraum.jpg" title="Farbraum Farbfächer" alt="A deginer must have." /></a>
        <a href="uploads/images/gallery/tropfen.jpg"><img src="uploads/images/gallery/tropfen.jpg" title="Lichtfluß" alt="Tropfen" /></a>
        <a href="uploads/images/gallery/kunst.jpg"><img src="uploads/images/gallery/kunst.jpg" title="Tja..." alt="...aber ratet mal was das is.." /></a>
        <a href="uploads/images/gallery/platsch.jpg"><img src="uploads/images/gallery/platsch.jpg" title="Platsch" alt="Platsch - Der Wassertropfen" /></a>
        <a href="uploads/images/gallery/raetsel_1.jpg"><img src="uploads/images/gallery/raetsel_1.jpg" title="Rätsel" alt="Micro" /></a>
        <a href="uploads/images/gallery/raetsel_2.jpg"><img src="uploads/images/gallery/raetsel_2.jpg" title="Was ist das?" alt="Rutsche?" /></a>
    </div>
</div>
</div>
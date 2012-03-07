{move_to target="pre_head_close"}
<script type="text/javascript" charset="utf-8">
<!--
function openCenteredPopup(url, windowname)
{
    // url is either a string parameter
    if (typeof(url) == 'string') {
       href=url; }
    else { // or this (the calling element), therefore we catch its href attribute
       href=url.href;
    }

    var width  = 900;
    var height = 450;

    // center it
    var left   = (screen.width  - width)/2;
    var top    = (screen.height - height)/2;

    // some parameters for the new window
    var params = 'width='+width+', height='+height;
    params += ', top='+top+', left='+left;
    params += ', directories=no';
    params += ', location=no';
    params += ', menubar=no';
    params += ', resizable=no';
    params += ', scrollbars=yes';
    params += ', status=no';
    params += ', toolbar=no';

    // create popup
    newwindow = window.open(url,windowname, params);

    // set autofocus new window
    if (window.focus) {
        newwindow.focus();
        return false;
    }
}
// -->
</script>
{/move_to}

<div class="table table-border">
	<div class="table-header table-border-bottom"><img src="{$www_root_theme}images/icons/develop.png" />{t}Developer Toolbox{/t}</div>
	<div class="table-content-menu">
		<ul>
			<li>
				<a href="index.php?mod=toolbox&action=cssbuilder" onclick="openCenteredPopup(this, 'CssBuilder'); return false;">
				<img src="{$www_root_theme}images/icons/css.png" alt="CSS Builder"/>CSS Builder
				</a>
			</li>
		</ul>
	</div>
</div>
<div class="tablespacer10">&nbsp;</div>

{move_to target="pre_head_close"}
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.js"></script>
<link rel="stylesheet" href="{$www_root_themes_core}css/prettyPhoto.css" type="text/css" media="screen"/>
<script src="{$www_root_themes_core}javascript/jquery/jquery.prettyPhoto.js" type="text/javascript"></script>

<script type="text/javascript">
// <![CDATA[
    $(document).ready(function(){
        $("a[rel^='prettyPhoto']").prettyPhoto({
            animationSpeed: 'normal', /* fast/slow/normal */
            opacity: 0.80, /* Value between 0 and 1 */
            showTitle: true /* true/false */
        });
    });
// ]]>
</script>
{/move_to}

<img src="{$www_root_theme}images/blind.gif" border="0" height="10" width="1" alt="testunit" /><br />

<center>

<table class="tables" cellpadding="0" cellspacing="0" border="0" summary="testunit" align="center" style="width:800px;height:400px;border:1px solid #000;">
    <tr valign="top"><td colspan="2" valign="middle" align="center" class="arial12white" bgcolor="#FF0000"><b>Test: Ajax Flash-Video mit prettyPhoto</b></td></tr>
    <tr valign="top"><td colspan="2" valign="top" bgcolor="#000000"><img src="{$www_root_theme}images/blind.gif" border="0" height="1" width="1" alt="testunit" /></td></tr>
    <tr valign="top"><td colspan="2" valign="top"><img src="{$www_root_theme}images/blind.gif" border="0" height="40" width="1" alt="testunit" /></td></tr>
    <tr valign="top">
        <td>
            <h4>Youtube Video</h4>
            <ul class="gallery clearfix">
                <li>
                    <a href="http://www.youtube.com/watch?v=D8AorGmLk5Y"
                       title="The Black Keys - Tighten Up" rel="prettyPhoto">
                    <img src="{$www_root_theme}images/prettyphoto/thumbnails/flash-logo.png"
                         width="60" alt="Flash 10 Demo Logo" />
                     </a>
                </li>            
            </ul>
        </td>
    </tr>
</table>

<b>>></b>&nbsp;<a href="index.php?mod=testunit" class="arial12black">back</a>&nbsp;<b><<</b><br/>
</center>
<img src="{$www_root_theme}images/blind.gif" border="0" height="10" width="1" alt="testunit" /><br />

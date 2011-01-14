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
        });
    });
// ]]>
</script>
{/move_to}


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
                    <img src="{$www_root_uploads}images/thumbs/flash-logo.png"
                         width="60" alt="Youtube!"/>
                     </a>
                </li>
            </ul>

            <h4>Vimeo video</h4>
            <ul class="gallery clearfix">
                <li><a href="http://vimeo.com/12155835&width=700" rel="prettyPhoto" title="Vimeo video">
                        <img src="{$www_root_uploads}images/thumbs/flash-logo.png" width="60" alt="VIMEO!" /></a></li>
            </ul>

            <h4>Gallery</h4>
            <ul class="gallery clearfix">
                <li>
                    <a href="{$www_root_uploads}images/gallery/1.jpg" rel="prettyPhoto[gallery1]"
                    title="You can add caption to pictures. You can add caption to pictures. You can add caption to pictures.">
                    <img src="{$www_root_uploads}images/thumbs/t_1.jpg" width="60" height="60" alt="Red round shape" /></a>
                </li>
                <li>
                    <a href="{$www_root_uploads}images/gallery/2.jpg" rel="prettyPhoto[gallery1]">
                    <img src="{$www_root_uploads}images/thumbs/t_2.jpg" width="60" height="60" alt="Nice building" /></a>
                </li>
                <li>
                    <a href="{$www_root_uploads}images/gallery/3.jpg" rel="prettyPhoto[gallery1]">
                    <img src="{$www_root_uploads}images/thumbs/t_3.jpg" width="60" height="60" alt="Fire!" /></a>
                </li>
                <li>
                    <a href="{$www_root_uploads}images/gallery/4.jpg" rel="prettyPhoto[gallery1]">
                    <img src="{$www_root_uploads}images/thumbs/t_4.jpg" width="60" height="60" alt="Rock climbing" /></a>
                </li>
                <li>
                    <a href="{$www_root_uploads}images/gallery/5.jpg" rel="prettyPhoto[gallery1]">
                    <img src="{$www_root_uploads}images/thumbs/t_5.jpg" width="60" height="60" alt="Fly kite, fly!" /></a>
                </li>
            </ul>
        </td>
    </tr>
</table>

Navigation: {breadcrumbs}

</center>
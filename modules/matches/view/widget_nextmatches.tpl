 {*  {$widget_nextmatches|@var_dump} *}

<!-- Start Widget Nextmatches from Module Matches -->

<div class="news_widget" id="widget_nextmatches" width="100%">

{move_to target="pre_head_close"}

<link rel="stylesheet" href="{$www_root_themes_core}css/jquery-easyslider.css" type="text/css" media="screen" />
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.easySlider1.5.js"></script>


     <script type="text/javascript">
     var $j = jQuery.noConflict();
         $j(document).ready(function(){
            $j("#nextmatches_slider").easySlider();
        });
    </script>

{/move_to}

    <h2 class="td_header">{t}Next Matches{/t}</h2>


<!-- Start Nextmatches-Slider from Module Matches -->
<div id="nextmatches_slider">

	<ul>
		{* {foreach item=match from=$widget_nextmatches} *}
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
		{* {/foreach} *}
	</ul>

</div>
<!-- End Nextmatches-Slider from Module Matches -->
</div>

<!-- End Widget Nextmatches -->
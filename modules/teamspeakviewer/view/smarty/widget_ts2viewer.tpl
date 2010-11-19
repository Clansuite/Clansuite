{* {$serverinfo|var_dump} *}

<!-- [Start] widget_ts2viewer @ module teamspeakviewer -->

<div class="td_header">Teamspeak2 Viewer</div>

<div class="cell1">

 {* {if $serverinfo.request_ok == true} *}

    <script type="text/javascript"
            src="http://www.tsviewer.com/ts_viewer_pur.php?ID={$serverinfo.server_id}&bg=transparent&type=a8820f&type_size=10&type_family=1&info=0&channels=1&users=1&js=1&type_s_weight=normal&type_s_style=normal&type_s_variant=normal&type_s_decoration=none&type_s_weight_h=normal&type_s_style_h=normal&type_s_variant_h=normal&type_s_decoration_h=none&type_i_weight=normal&type_i_style=normal&type_i_variant=normal&type_i_decoration=none&type_i_weight_h=normal&type_i_style_h=normal&type_i_variant_h=normal&type_i_decoration_h=none&type_c_color=294c73&type_c_weight=bold&type_c_style=normal&type_c_variant=normal&type_c_decoration=none&type_c_weight_h=normal&type_c_style_h=normal&type_c_variant_h=normal&type_c_decoration_h=none&type_u_color=b07119&type_u_weight=bold&type_u_style=italic&type_u_variant=normal&type_u_decoration=none&type_u_weight_h=normal&type_u_style_h=normal&type_u_variant_h=normal&type_u_decoration_h=none&skin=tsv_mini&cflags=0">
    </script>

    <noscript>
        Enable JavaScript to see TeamSpeak Viewer or click
        <a href="http://www.tsviewer.com/index.php?page=ts_viewer&ID={$serverinfo.server_id}">here</a>.
    </noscript>

 {* {else}

    <span style="color: red; font-weight: bold;">offline</span>

 {/if} *}

</div>
<!-- [-End-] widget_ts2viewer @ module teamspeakviewer -->
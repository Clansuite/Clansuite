{literal}

    <!-- (jQuery) Javascript for the Help Toggle -->

    <script type="text/javascript">

        $(document).ready( function(){
            $("#help").hide();
            $("#help-toggler").click( function(){
                $("#help").slideToggle("normal");
            });
        });

    </script>

{/literal}

<!-- Help Icon -->
<div id="help-toggler" style="float: right; font-family: tahoma,verdana,arial,sans-serif; font-size: 11px; cursor: pointer;">
    <img style="margin-bottom: -3px;" src="{$www_root_themes_core}/images/icons/help.png" alt="Help Toggle" />
    {t}Help{/t}
</div>

<!-- Theme Standard: Help Text = none -->
<!-- Include: Help Text from Module -->
{include file="help.tpl"}

<!-- (jQuery) Javascript for the Help Toggle -->
<script type="text/javascript">
    $(document).ready( function(){
        $("#help").hide();
        $("#help-toggler").click( function(){
            $("#help").slideToggle("normal");
        });
    });
</script>

<!-- Help Icon -->
<div id="help-toggler" style="float: right; font-family: tahoma,verdana,arial,sans-serif; font-size: 11px; cursor: pointer;">
    <img style="margin-bottom: -3px;" src="{$www_root_themes_core}/images/icons/help.png" alt="Help Toggle" title="Seitenleiste mit Hilfe einblenden" />
    {t}Help{/t}
</div>

<!-- Display the Helptext from Core -->
{include file="core/templates/help.tpl"}
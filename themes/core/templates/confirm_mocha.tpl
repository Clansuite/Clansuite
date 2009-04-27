{move_to}
<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/css/mocha/mocha.css" />
{/move_to}

{if isset($confirmTpl)}
    <div id="{$confirmClass}_container" style="display:none">{$confirmTpl}</div>
{/if}

{literal}
<script type="text/javascript">
window.addEvent('domready', function() {
    var confirmMocha{/literal}{$confirmClass}{literal} = function() {
        new MochaUI.Window({
            title: '{/literal}{$confirmTitle}{literal}',
            loadMethod: 'html',
            {/literal}
            {if isset($confirmTpl)}
            content: $('{$confirmClass}_container').innerHTML,
            {else}
            content: '{$confirmHTML}',
            {/if}
            {literal}
            minimizable: false,
            toolbar: false
        });
    }

    $$('.{/literal}{$confirmClass}{literal}').each( function(cEl) {
        cEl.addEvent('click', function(e) {
            new Event(e).stop();
            $$('.confirmValue').each( function(el) {
                el.value = cEl.get('{/literal}{$confirmGrabValueFrom}{literal}');
            });
            confirmMocha{/literal}{$confirmClass}{literal}();
        });
    });
});
</script>
{/literal}
{literal}
<script type="text/javascript">
window.addEvent('domready', function() {
    var confirmMocha{/literal}{$confirmClass}{literal} = function() {        
        new MochaUI.Window({
            title: '{/literal}{$confirmTitle}{literal}',
            loadMethod: 'html',
            content: '{/literal}{$confirmHTML}{literal}',
            minimizable: false,
            toolbar: false
        });    
    }

    $$('.{/literal}{$confirmClass}{literal}').addEvent('click', function(e) {
        new Event(e).stop();
        confirmMocha{/literal}{$confirmClass}{literal}();
    });
});
</script>
{/literal}
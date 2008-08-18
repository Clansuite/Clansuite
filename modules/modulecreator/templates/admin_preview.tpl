{literal} 
<script type="text/javascript">
    window.addEvent('domready', function() {
        var scalers = $$('.scaler');
        scalers.each( function(scaler,i) {
            var fx = new Fx.Tween($(scaler.id + '_scale'));
            scaler.addEvent( 'click', function() {
                if( $(scaler.id + '_scale').getStyle('max-height') == '150px' )
                {
                    $(scaler.id + '_scale').setStyle('max-height', '');
                    $(scaler).innerHTML = "{/literal}{t}Decrease view{/t}{literal}";
                }
                else
                {
                    $(scaler.id + '_scale').setStyle('max-height', '150px');
                    $(scaler).innerHTML = "{/literal}{t}Increase view{/t}{literal}";
                }
            });
        });

    }, 'javascript');
</script>
{/literal}
<div>
    <div style="text-align:center">
        <form action="index.php?mod=modulecreator&sub=admin&action=create" method="POST">
            <input type="hidden" value="{$mod.data}" name="mod_data" />
            <input type="submit" name="create" value="Create the module" class="ButtonGreen" />
        </form>
        <form action="index.php?mod=modulecreator&sub=admin&action=show" method="POST">
            <input type="hidden" value="{$mod.data}" name="mod_data" />
            <input type="submit" name="edit" value="Edit the module again" class="ButtonOrange" />
        </form>
    </div>
<div>

<!-- FRONTEND + WIDGETS -->
{if isset($frontend)}
<div class="td_header_small">
    Frontend <span style="color: #cc0000;">/modules/{$mod.module_name}/{$mod.module_name}.module.php</span> - <a href="javascript:void(0);" class="scaler" id="frontend">{t}Increase view{/t}</a>
</div>
<div style="max-height: 150px; overflow: auto; background-color: #fff;" class="cell2" id="frontend_scale">
    {$frontend}
</div>
{/if}

<!-- BACKEND -->
{if isset($backend)}
<div class="td_header_small">Backend <span style="color: #cc0000;">/modules/{$mod.module_name}/{$mod.module_name}.admin.php</span> - <a href="javascript:void(0);" class="scaler" id="backend">{t}Increase view{/t}</a></div>
<div style="max-height: 150px; overflow: auto; background-color: #fff;" class="cell2" id="backend_scale">
    {$backend}
</div>
{/if}

<!-- CONFIG -->
{if isset($config)}
<div class="td_header_small">Configuration <span style="color: #cc0000;">/modules/{$mod.module_name}/{$mod.module_name}.config.php</span> - <a href="javascript:void(0);" class="scaler" id="config">{t}Increase view{/t}</a></div>
<div style="max-height: 150px; overflow: auto; background-color: #fff;" class="cell2" id="config_scale">
    {$config}
</div>
{/if}

<!-- TEMPLATES -->
<div class="td_header_small">Templates <span style="color: #cc0000;">/modules/{$mod.module_name}/templates/</span> - <a href="javascript:void(0);" class="scaler" id="templates">{t}Increase view{/t}</a></div>
<div style="max-height: 150px; overflow: auto; background-color: #fff;" class="cell2" id="templates_scale">
{foreach from=$mod.frontend.frontend_methods item=item key=key}
    {if isset($mod.frontend.frontend_tpls.$key) AND isset($mod.frontend.checked)}
    /modules/{$mod.module_name}/templates/{$item}.tpl<br />
    {/if}
{/foreach}
{foreach from=$mod.backend.backend_methods item=item key=key}
    {if isset($mod.backend.backend_tpls.$key) AND isset($mod.backend.checked)}
    /modules/{$mod.module_name}/templates/{$item}.tpl<br />
    {/if}
{/foreach}
{foreach from=$mod.widget.widget_methods item=item key=key}
    {if isset($mod.widget.widget_tpls.$key) AND isset($mod.widget.checked)}
    /modules/{$mod.module_name}/templates/{$item}.tpl<br />
    {/if}
{/foreach}
</div>
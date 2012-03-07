<div class="ModuleHeading">{t}Preview of the new Module{/t}</div>
<div class="ModuleHeadingSmall">{t}This preview shows the modulefiles and their content. If you like what you see, click "Create Module".{/t}</div>

<div>
    <div style="text-align:center" id="create_module_form">
        <form action="index.php?mod=modulemanager&sub=admin&action=create" method="POST">
            <input type="hidden" value="{$mod.data}" name="mod_data" />
            <input type="submit" name="create" value="Create the module" class="ButtonGreen"/>
        </form>
    </div>
<div>

<!-- FRONTEND + WIDGETS -->
{if isset($frontend)}
<div class="td_header_small">
    Frontend <span style="color: #cc0000;">/modules/{$mod.modulename}/{$mod.modulename}.module.php</span> - <a href="javascript:void(0);" class="scaler" id="frontend">{t}Increase view{/t}</a>
</div>
<div style="max-height: 150px; overflow: auto; background-color: #fff;" class="cell2" id="frontend_scale">
    {$frontend}
</div>
{/if}

<!-- BACKEND -->
{if isset($backend)}
<div class="td_header_small">Backend <span style="color: #cc0000;">/modules/{$mod.modulename}/{$mod.modulename}.admin.php</span> - <a href="javascript:void(0);" class="scaler" id="backend">{t}Increase view{/t}</a></div>
<div style="max-height: 150px; overflow: auto; background-color: #fff;" class="cell2" id="backend_scale">
    {$backend}
</div>
{/if}

<!-- CONFIG -->
{if isset($config)}
<div class="td_header_small">Configuration <span style="color: #cc0000;">/modules/{$mod.modulename}/{$mod.modulename}.config.php</span> - <a href="javascript:void(0);" class="scaler" id="config">{t}Increase view{/t}</a></div>
<div style="max-height: 150px; overflow: auto; background-color: #fff;" class="cell2" id="config_scale">
    {$config}
</div>
{/if}

<!-- TEMPLATES -->
<div class="td_header_small">Templates <span style="color: #cc0000;">/modules/{$mod.modulename}/view/</span> - <a href="javascript:void(0);" class="scaler" id="templates">{t}Increase view{/t}</a></div>
<div style="max-height: 150px; overflow: auto; background-color: #fff;" class="cell2" id="templates_scale">
{foreach from=$mod.frontend.frontend_methods item=item key=key}
    {if isset($mod.frontend.frontend_tpls.$key) AND isset($mod.frontend.checked)}
    /modules/{$mod.modulename}/view/{$item}.tpl<br />
    {/if}
{/foreach}
{foreach from=$mod.backend.backend_methods item=item key=key}
    {if isset($mod.backend.backend_tpls.$key) AND isset($mod.backend.checked)}
    /modules/{$mod.modulename}/view/{$item}.tpl<br />
    {/if}
{/foreach}
{foreach from=$mod.widget.widget_methods item=item key=key}
    {if isset($mod.widget.widget_tpls.$key) AND isset($mod.widget.checked)}
    /modules/{$mod.modulename}/view/{$item}.tpl<br />
    {/if}
{/foreach}
</div>
{foreach from=$m.widget.widget_methods item=item key=key}

    /**
     * The {$item} method for the {$mod.module_name|capitalize} module (widget!)
     * @param void
     * @return void 
     */
    {$mod.widget.widget_scopes.$key} function {$item}(&$smarty){literal}
    {{/literal}
        {if isset($mod.widget.widget_snippets.$key) }

{foreach from=$mod.widget.widget_snippets.$key item=item2 key=key2}
{include file="snippets_$item2.tpl"}               
{/foreach}
        {/if}
        {if $mod.widget.widget_outputs.$key == '1'}echo $smarty->fetch('{$mod.module_name}/{$mod.module_name}_widget.tpl');{/if}{literal}
    }{/literal}
{/foreach}
{foreach from=$mod.frontend.frontend_methods item=item key=key}

    /**
     * The {$item} method for the {$mod.module_name|capitalize} module
     * @param void
     * @return void 
     */
    {$mod.frontend.frontend_scopes.$key} function {$item}(){literal}
    {{/literal}
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('{$item|replace:'action_':''}'), '/index.php?mod={$mod.module_name}&amp;action={$item|replace:'action_':''}');

        {if isset($mod.frontend.frontend_snippets.$key) }

{foreach from=$mod.frontend.frontend_snippets.$key item=item2 key=key2}
{include file="snippets_$item2.tpl"}               
{/foreach}
        {/if}

        {if $mod.frontend.frontend_outputs.$key == '1'}# Prepare the Output
        $this->prepareOutput();{/if}{literal}
    }{/literal}
{/foreach}
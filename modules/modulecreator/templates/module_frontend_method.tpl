{foreach from=$m.frontend.frontend_methods item=item key=key}
    /**
     * The {$item} method for the {$m.module_name|capitalize} module
     * @param void
     * @return void 
     */
    {$m.frontend.frontend_scopes.$key} function {$item}(){literal}
    {{/literal}
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('{$item|replace:'action_':''}'), '/index.php?mod={$m.module_name}&amp;action={$item|replace:'action_':''}');

        # Prepare the Output
        $this->prepareOutput();{literal}
    }{/literal}
{/foreach}
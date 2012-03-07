{foreach from=$mod.backend.backend_methods item=item key=key}

    /**
     * The {$item} method for the {$mod.module_name|capitalize} module
     * @param void
     * @return void 
     */
    {$mod.backend.backend_scopes.$key} function {$item}()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('{$item|replace:'action_admin_':''|replace:'_':' '|capitalize}'), '/index.php?mod={$mod.modulename}&amp;action={$item|replace:'action_admin_':''}');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
        {if isset($mod.backend.backend_snippets.$key) }

{foreach from=$mod.backend.backend_snippets.$key item=item2 key=key2}
{include file="snippets_$item2.tpl"}               
{/foreach}
        {/if}
        
        {if $mod.backend.backend_outputs.$key == '1'}# Prepare the Output
        $this->prepareOutput();{/if}
    }
{/foreach}
{foreach from=$m.widget.widget_methods item=item key=key}
    /**
     * The {$item} method for the {$m.module_name|capitalize} module (widget!)
     * @param void
     * @return void 
     */
    {$m.widget.widget_scopes.$key} function {$item}(&$smarty){literal}
    {{/literal}
        echo $smarty->fetch('{$m.module_name}/{$m.module_name}_widget.tpl');{literal}
    }{/literal}
{/foreach}
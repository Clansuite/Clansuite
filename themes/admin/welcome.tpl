<div align="center">

    <h3>
    {t}Welcome.{/t}
    <br />
    {t}This is the Control Center (CC) of Clansuite.{/t}
    </h3>
    
    
    {include file="admin/shortcuts.tpl"}
    
    
    {$updater|@var_dump}
    
    {if isset($updater.enabled) && $updater.enabled == true}
        {include file="admin/updater/updater.tpl"}
    {/if}

</div>
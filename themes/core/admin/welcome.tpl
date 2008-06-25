<div align="center">

    <h3>
    {t}Welcome.{/t}
    <br />
    {t}This is the Control Center (CC) of Clansuite.{/t}
    </h3>

    {if isset($updater.enabled)}
        {include file="admin/updater/updater.tpl"}
    {/if}

    {include file="admin/shortcuts.tpl"}

</div>
{* DEBUG OUTPUT of assigned Arrays: {$themes|@var_dump} *}

<div class="ModuleHeading">{t}Thememanager{/t}</div>
<div class="ModuleHeadingSmall">{t}Themes ver�ndern das Aussehen Ihrer Clansuite Webseite. Sie k�nnen hier das Standard-Theme �ndern, ein Theme f�r alle Mitglieder festlegen oder neue Themes installieren.{/t}</div>

<table width="100%">
    <tbody>

    {foreach item=theme from=$themes}

    <tr>

        <td width="30%">
            <img src="{$theme.preview_thumbnail}" width="" heigth="">
            {* Fullsize {$theme.preview_image} *}
        </td>

        <!-- Todo Move Css -->
        <td  style="
    background:#FFFFFF none repeat scroll 0 0;
    border:1px solid #919B9C;
    padding:10px;
    width:300px;">
            <b>{$theme.name} v{$theme.theme_version}</b>
            <br />
            Autor: {$theme.author}
            <br />
            Clansuite Version required: {$theme.required_clansuite_version}
            <br />
            Creation Date: {if isset($theme.date)}{$theme.date}{/if}
            <br />
            Render Engine: {if isset($theme.renderengine)}{$theme.renderengine}{/if}
            {*
            {$theme.fullpath}
            {$theme.dirname}
            *}
            <br />
        </td>
        <td>
        {if !$theme.activated} <input class="ButtonGreen" type="Button" value="Select" /> {/if}
        
        {* {if $admin} <input class="ButtonGreen" type="Button" value="Select as Default Theme" /> {/if} *}
        
        <input class="ButtonOrange" type="Button" value="Edit" />
        
        <input class="ButtonRed" type="Button" value="Delete" />
        </td>
    </tr>

    {/foreach}

    </tbody>
</table>
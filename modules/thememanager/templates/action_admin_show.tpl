{* DEBUG OUTPUT of assigned Arrays:
{$themes|@var_dump}
*}

<div class="ModuleHeading">{t}Thememanager{/t}</div>
<div class="ModuleHeadingSmall">{t}Themes verändern das Aussehen Ihrer Clansuite Webseite. Sie können hier das Standard-Theme ändern, ein Theme für alle Mitglieder festlegen oder neue Themes installieren.{/t}</div>

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

        {* Select Theme *}
        {if $theme.activated == false}
            <form action="http://www.irgendwo.de" method="GET">
                <input class="ButtonGreen" type="submit" value="Select" />
            </form>
        {else}
            This Theme is active!
            <br />
        {/if}

        <form action="http://www.irgendwo.de" method="GET">
             {* {if $admin} <input class="ButtonGreen" type="submit"  value="Select as Default Theme" /> {/if} *}
        </form>

        {if empty($theme.layoutpath) == false }
            <a href="http://www.clansuite-dev.com/index.php?mod=templatemanager&sub=admin&action=editor&file={$theme.layoutpath}"
               class="ButtonOrange">{t}Edit{/t}</a>
        {/if}

        <form action="http://www.irgendwo.de" method="GET">
            <input class="ButtonRed" type="submit" value="Delete" />
        </form>

        </td>
    </tr>

    {/foreach}

    </tbody>
</table>
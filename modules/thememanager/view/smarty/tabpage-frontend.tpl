{* {$themes|var_dump} *}

<table width="100%">
    <tbody>

{foreach $themes as $theme}

{if $theme.backendtheme == false}
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
            <br /><br />
            Autor: {$theme.author}
            <br />
            Clansuite Version required: {$theme.required_clansuite_version}
            <br />
            Creation Date: {if isset($theme.date)}{$theme.date}{/if}
            <br />
            Render Engine: {if isset($theme.renderengine)}{$theme.renderengine|ucfirst}{/if}
            {*
            {$theme.fullpath}
            {$theme.dirname}
            *}
            <br />
        </td>
        <td>

        {* Select as Fallback Theme *}
        {if $theme.globally_active == false}
            <a href="{$www_root}index.php?mod=thememanager&sub=admin&action=setfrontendthemeglobal&theme={$theme.dirname}"
               class="ButtonGreen">{t}Select as Default frontend theme{/t}</a>
        {else}
            This Theme is the globally active theme!
        {/if}

        <br /><br />

        {* Select as Individual Theme *}
        {if $theme.user_active == false}
            <a href="{$www_root}index.php?mod=thememanager&sub=admin&action=setfrontendthemeglobal&theme={$theme.dirname}"
               class="ButtonGreen">{t}Select as Your frontend theme{/t}</a>
        {else}
            This Theme is your frontend theme!
        {/if}

        <br /><br />

        {* Call Templatemanager to edit the Main Layout Template *}
        {if isset($theme['layout']['@attributes']['mainfile'])}
            <a href="{$www_root}index.php?mod=templatemanager&sub=admin&action=editor&file={$theme['layout']['@attributes']['mainfile']}"
               class="ButtonOrange">{t}Edit Main Template{/t}</a>
        {/if}

        {* Delete the Theme *}
        <a href="{$www_root}index.php?mod=thememanager&sub=admin&action=delete&theme={$theme.dirname}"
           class="ButtonRed">{t}Delete{/t}</a>

        </td>
    </tr>
{/if}
{/foreach}

    </tbody>
</table>
{move_to target="head"}
<script type="text/javascript" src="{$www_root_themes_core}/javascript/mootools/mootools.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}/javascript/mootools/mootools-more.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}/javascript/mootools/mocha/mocha.js"></script>

<!--[if IE]>
<script type="text/javascript" src="{$www_root_themes_core}/javascript/mootools/mocha/excanvas-compressed.js"></script>
<![endif]-->

<script type="text/javascript" src="{$www_root_themes_core}/javascript/mootools/mocha/mocha-init.js"></script>
{/move_to}

{modulenavigation}
<div class="ModuleHeading">{t}Modulemanager{/t}</div>
<div class="ModuleHeadingSmall">{t}You can edit, activate and deactivate your Modules.{/t}</div>

{confirm class="delete"
         htmlTemplate="confirm_mocha_ok.tpl" 
         grabValueFrom="title"
         link="index.php?mod=modulemanager&action=delete"
         confirmMessage="Do you want to delete the module?"}

<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <th class="td_header_small">{t}Modulename{/t}</th>
        <th class="td_header_small">{t}Information{/t}</th>
        <th class="td_header_small">{t}Actions{/t}</th>
    </tr>
    {foreach from=$modules item=module}
    <tr>
        <td class="cell1" width="150" align="center">{$module.name|capitalize}</td>
        <td class="cell2"></td>
        <td class="cell1">
            <a href="" type="button" class="delete" title="{$module.name}" />Delete</a>
        </td>
    </tr>
    {/foreach}
</table> 
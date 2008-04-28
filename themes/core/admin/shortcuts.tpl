{* Center Layer Divs Start*}
<div style="text-align:center"><div style="margin:auto;text-align:left;">

{* JQuery used for Vertical Tabs *}
<div id="vertical-tabs">
    <fieldset>
        <legend><img alt="Modules Shortcut Icon" src="{$www_root_themes_core}/images/symbols/modules.png" />Module</legend>
        {* Table for Administration Symbols/Shortcuts *}
        <center>
        <table cellspacing="15" cellpadding="5" {* border="1" *}>
            {foreach key=row item=image from=$shortcuts}
                <tr class="tr_row2">
                {foreach key=col item=data from=$image}
                    <td align="center" style="width:70px; padding: 14px;">
                        <a href="{$data.href}">
                            <img alt="Shortcut Icon" src="{$www_root_themes_core}/images/symbols/{$data.file_name}" />
                            <br />
                            <span style="margin-top: 10px; display: block">{t}{$data.title}{/t}</span>
                        </a>
                    </td>
                {/foreach}
                </tr>
                <tr>
                    <td align="center" colspan="4">
                        <hr />
                    </td>
                </tr>
            {/foreach}
        </table>
        </center>
    </fieldset>
    <fieldset>
        <legend><img alt="Admin Shortcut Icon" src="{$www_root_themes_core}/images/symbols/settings.png" />Administration</legend>
        Ipsum
    </fieldset>
    <fieldset>
        <legend><img alt="Settings Shortcut Icon" src="{$www_root_themes_core}/images/symbols/system.png" />System</legend>
        Ipsum
    </fieldset>
    <fieldset>
        <legend><img alt="User Shortcut Icon" src="{$www_root_themes_core}/images/symbols/groups.png" />Benutzer</legend>
        Ipsum
    </fieldset>
    <fieldset>
        <legend><img alt="Layout Shortcut Icon" src="{$www_root_themes_core}/images/symbols/templates.png" />Layout</legend>
        Ipsum
    </fieldset>
    <div style="clear:both"></div>
</div>

{* Center Layer Divs End*}
</div></div>

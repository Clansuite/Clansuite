{* Center Layer Divs Start*}
<div style="text-align:center"><div style="margin:auto;text-align:left;">

{* JQuery used for Vertical Tabs *}
<div id="vertical-tabs" >
    <fieldset>
        <legend>Module</legend>
        {* Table for Administration Symbols/Shortcuts *}

        <center>
        <table cellspacing="20" cellpadding="10" {* border="1" *} >
        
            {foreach key=row item=image from=$shortcuts}
                <tr>
        
                {foreach key=col item=data from=$image}
        
                    <td align="center">
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
        </center>
        </table>

    </fieldset>
    <fieldset>
        <legend>Administration</legend>
        Ipsum
    </fieldset>
    <fieldset>
        <legend>System <img alt="Shortcut Icon" src="{$www_root_themes_core}/images/symbols/settings.png" /></legend>
        Ipsum
    </fieldset>
    <fieldset>
        <legend>Nutzer</legend>
        Ipsum
    </fieldset>
    <fieldset>
        <legend>Layout</legend>
        Ipsum
    </fieldset>
    <div style="clear:both"></div>
</div>

{* Center Layer Divs End*}
</div></div>
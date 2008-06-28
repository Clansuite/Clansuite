{* Center Layer Divs Start*}
<div style="text-align:center"><div style="margin:auto;text-align:left;">
{literal}
<script type="text/javascript">
jQuery(function() {
	jQuery('#vertical-tabs > ul').tabs({ fx: { height: 'toggle', opacity: 'toggle' } });
});
</script>
{/literal}
{* jQuery used for Vertical Tabs *}
<div id="vertical-tabs">
    <ul>
        <li><a href="#fragment-1"><span><img alt="Modules Shortcut Icon" src="{$www_root_themes_core}/images/symbols/modules.png" /><br />Module</span></a></li>
        <li><a href="#fragment-2"><span><img alt="Admin Shortcut Icon" src="{$www_root_themes_core}/images/symbols/settings.png" /><br />Administration</span></a></li>
        <li><a href="#fragment-3"><span><img alt="Settings Shortcut Icon" src="{$www_root_themes_core}/images/symbols/system.png" /><br />System</span></a></li>
        <li><a href="#fragment-4"><span><img alt="User Shortcut Icon" src="{$www_root_themes_core}/images/symbols/groups.png" /><br />Benutzer</span></a></li>
        <li><a href="#fragment-5"><span><img alt="Layout Shortcut Icon" src="{$www_root_themes_core}/images/symbols/templates.png" /><br />Layout</span></a></li>
    </ul>
    <div id="fragment-1">
        {* Table for Administration Symbols/Shortcuts *}
        <table cellspacing="15" cellpadding="5" style="margin:0 auto">
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
                    <td colspan="4">
                        <hr />
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
    <div id="fragment-2">
        Ipsum
    </div>
    <div id="fragment-3">
        Ipsum
    </div>
    <div id="fragment-4">
        Ipsum
    </div>
    <div id="fragment-5">
        Ipsum
    </div>
    <div style="clear:both"></div>
</div>

{* Center Layer Divs End*}
</div></div>

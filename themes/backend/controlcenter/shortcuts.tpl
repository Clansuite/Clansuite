<!-- Tab Table for the Shortcuts -->

<table cellspacing="0" cellpadding="0" width="100%">
    <tbody>
    <tr height="14">
        <td bgcolor="#ACD943" valign="bottom" width="30%" class="t2">&nbsp;&raquo; Shortcuts</td>
        <td nowrap="" background="{$www_root}modules/controlcenter/images/green-triangle.gif" width="100%" style="background-repeat: no-repeat;" class="bg"></td
    </tr>
    <tr>
        <td height="3" bgcolor="#ACD943" colspan="2"/>
    </tr>
    <tr>
        <td bgcolor="#dde9cf" valign="top" colspan="2">
            <table cellspacing="1" border="0" width="100%">
            <tbody>
                <tr bgcolor="#ffffff">
                    <td>
                    <table cellspacing="6" width="100%">
                        <tbody>
                        <tr>
                            <td>

                                {* Start:Shortcuts Content*}
                                
                                    <script type="text/javascript">
                                    jQuery(function() {
        	                            jQuery('#vertical-tabs > ul').tabs({ fx: { height: 'toggle', opacity: 'toggle' } });
                                    });
                                    </script>
                                

                                {move_to target="pre_head_close"}
                                <link rel="stylesheet" type="text/css" href="{$www_root_themes}/admin/vertical-tabs.css" />
                                {/move_to}

                                {* jQuery used for Vertical Tabs *}
                                <div id="vertical-tabs">
                                    <ul>
                                        <li><a href="#fragment-1"><span><img alt="Modules Shortcut Icon" src="{$www_root_themes_core}images/symbols/modules.png" /><br />Modules</span></a></li>
                                        <li><a href="#fragment-2"><span><img alt="Admin Shortcut Icon" src="{$www_root_themes_core}images/symbols/settings.png" /><br />Administration</span></a></li>
                                        <li><a href="#fragment-3"><span><img alt="Settings Shortcut Icon" src="{$www_root_themes_core}images/symbols/system.png" /><br />System</span></a></li>
                                        <li><a href="#fragment-4"><span><img alt="User Shortcut Icon" src="{$www_root_themes_core}images/symbols/groups.png" /><br />Benutzer</span></a></li>
                                        <li><a href="#fragment-5"><span><img alt="Layout Shortcut Icon" src="{$www_root_themes_core}images/symbols/templates.png" /><br />Layout</span></a></li>
                                    </ul>

                                    <div id="fragment-1">
                                        {* Table for Administration Symbols/Shortcuts *}
                                        <table cellspacing="10" cellpadding="5" {* Tabelle zentrieren: style="margin:0 auto" *}>
                                            
                                          {*
                                            {foreach key=row item=image from=$shortcuts}
                                                <tr class="tr_row2">

                                                {foreach key=col item=data from=$image}
                                                    <td align="center" style="width:65px; padding: 10px;">
                                                        <a href="{$data.href}">
                                                            <img alt="Shortcut Icon" src="{$www_root_themes_core}images/symbols/{$data.file_name}" />
                                                            <br />
                                                            <span style="margin-top: 10px; display: block">{t}{$data.title}{/t}</span>
                                                        </a>
                                                    </td>
                                                {/foreach}

                                                </tr>
                                            {/foreach}
                                           *}

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

                                    <!-- Clear -->
                                    <div style="clear:both">
                                 <!-- Close Vertical Tabs -->
                                 </div>
                                 {* End:Shortcuts Content *}

                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
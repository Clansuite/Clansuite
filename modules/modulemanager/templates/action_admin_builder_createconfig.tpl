<td class="cell2">Create Config for the module?</td>
<td class="cell1">

    <div style="padding-bottom: 5px;">
        <input type="checkbox" name="m[config][checked]" id="config" class="check_below" value="1" />
    </div>

    <div id="config_display">

    <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">

        <thead>
            <tr>
                <td class="td_header_small">
                    {t}Key{/t}
                </td>
                <td class="td_header_small">
                    {t}Value{/t}
                </td>
                <td class="td_header_small" align="left">
                    <img style="cursor: pointer;"  src="{$www_root_themes_core}/images/icons/add.png" id="config_add" />
                </td>
            </tr>
        </thead>

        <tbody id="config_wrapper">
            <tr id="config_input">
                <td height="20" class="cell2">
                    <input class="input_text" type="text" value="" name="m[config][config_keys][0]" pattern="^[a-zA-Z0-9_]+$" />
                </td>
                <td height="20" class="cell1">
                    <input class="input_text" type="text" value="" name="m[config][config_values][0]" pattern="^[a-zA-Z0-9_]+$" />
                </td>
                <td class="cell2" align="left" width="99%">
                    <img src="{$www_root_themes_core}/images/icons/delete.png" id="config_delete" style="margin-top: 2px; cursor: pointer;" />
                </td>
            </tr>
        </tbody>

    </table>

    </div>
</td>
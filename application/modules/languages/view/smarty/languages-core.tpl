{* {$cores|var_dump} *}

<table width="100%" cellspacing="0" cellpadding="0" border="0" class="Datagrid">

    <tbody>
        <tr>
            <th class="ColHeader">#</td>
            <th class="ColHeader">Core Components</th>
            <th class="ColHeader">Actions</th>
        </tr>

       {foreach $cores as $core}
        <tr class="DatagridRow DatagridRow-Row_{$core@iteration} Alternate">
            <td align="center">{$core@iteration}</td>
            <td>
                <table width="100%" border="0" class="type-info">
                    <tbody>
                        <tr>
                            <td>
                                <h3 class="name">
                                {$core.name}
                                    <span style="font-weight: small;">&nbsp;&nbsp;&copy;&nbsp;</span>
                                    <sup>
                                        <em><a href="{$core.homepage}">{$core.author}</a></em>
                                    </sup>
                                </h3>
                            </td>
                        </tr>
                        <tr>
                            <td>{t}Version{/t}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>{t}Status{/t}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>{t}Description{/t}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td>
                <table width="100%" cellspacing="0" summary="localization|core" id="mo-list-core" class="mo-list">
                    <tbody>
                        <tr class="mo-list-head">
                            <td nowrap="nowrap" colspan="2">
                                <img src="images/gettext.gif" class="alignleft" alt="GNU Gettext Icon">
                                &nbsp;
                                <a href="mod=language&sub=admin&action=addnewlanguage">Add New Language</a>
                            </td>
                        </tr>

                        <tr class="mo-list-desc">
                            <td nowrap="nowrap" style="text-align: center;">{t}Language{/t}</td>
                            <td nowrap="nowrap" style="text-align: center;">{t}Permissions{/t}</td>
                            <td nowrap="nowrap" style="text-align: center;">{t}Actions{/t}</td>
                        </tr>

                        {foreach $module.langfiles as $langfile}
                        <tr class="mo-file" lang="{$language}">
                            <td width="100%" nowrap="nowrap">
                                <img src="images/flags/{$language_short}.gif" alt="(locale: {$language_short})" title="Locale: {$language_short}">&nbsp;{$language_name}
                            </td>
                            <td nowrap="nowrap" align="center">
                                <div style="width: 44px;">
                                    <a title="{$languages.stamp}" class="{$languages.po-class}">&nbsp;</a>
                                    <a title="{$languages.stamp}" class="{$languages.mo-class}">&nbsp;</a>
                                </div>
                            </td>
                            <td nowrap="nowrap" style="text-align: center;">
                                Edit
                                <span> | </span>
                                Rescan
                                <span> | </span>
                                Delete
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </td>
        </tr>
       {/foreach}

    </tbody>

</table>
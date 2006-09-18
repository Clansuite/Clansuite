{foreach key=key item=item from=$folders}
    <div class='folder' id='folder-{$item}'>
        <img src="{$www_core_tpl_root}/admin/adminmenu/images/tree-node.gif" width="18" height="18" border="0" id="node-{$key}" onclick="sendFilebrowserAjaxRequest('{$key}', '{$key}');">
        <img src="{$www_core_tpl_root}/admin/adminmenu/images/tree-folder.gif" width="18" height="18" border="0">
        {$item}
        <div class="section" id="section-{$key}" style="display: none"></div>
    </div>
{/foreach}

{foreach key=key item=item from=$files}
    <div class='doc' id='folder-{$item}'>
        <img src="{$www_core_tpl_root}/admin/adminmenu/images/tree-leaf.gif" width="18" height="18" border="0" id="node-item">
        <img src="{$www_core_tpl_root}/admin/adminmenu/images/tree-doc.gif" width="18" height="18" border="0">
        {$item}
    </div>
{/foreach}
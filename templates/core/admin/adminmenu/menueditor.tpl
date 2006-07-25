{php}
                //----------------------------------------------------------------
                // Read menu from DB
                //----------------------------------------------------------------
                global $db;

                $stmt = $db->prepare('SELECT *
                                      FROM ' . DB_PREFIX .'adminmenu
                                      ORDER BY id ASC, parent ASC');
                $stmt->execute();
                $adminmenudb = $stmt->fetchAll( PDO::FETCH_ASSOC );

                function build_editormenu(&$result, $parent = 0, $level = 0)
                {
                    $output = array();
                    $rows = count($result);
                    for($i = 0; $i < $rows; $i++)
                    {
                        if($result[$i]['parent'] == $parent)
                        {
                            $output[$result[$i]['id']] = array(
                                'name' => $result[$i]['title'],
                                'level' => $level,
                                'type' => $result[$i]['type'],
                                'parent' => $result[$i]['parent'],
                                'id' => $result[$i]['id'],
                                'href' => $result[$i]['href']
                                );
                            $output[$result[$i]['id']]['content'] = build_editormenu($result, $result[$i]['id'], $level + 1);
                            if (count($output[$result[$i]['id']]['content']) == 0)
                                unset($output[$result[$i]['id']]['content']);
                            else
                                $output[$result[$i]['id']]['expanded'] = true;
                        }
                    }    
                    return array_values($output);
                }

                //----------------------------------------------------------------
                // This function generates html-div based menu lists
                //----------------------------------------------------------------
                function get_adminmenu_div($menu)
                {
                    #var_dump($menu);
                    foreach($menu as $entry)
                    {
                    	if ($entry['href'] == '' )
                        {
                            $entry['href'] = 'javascript:void(0)';
                        }
                        else
                        {
                            $c = parse_url($$entry['href']);
                            if( !array_key_exists('host', $c) )
                            {
                                $entry['href'] = WWW_ROOT . $entry['href'];
                            }
                        }
                                        
                        if (htmlentities($entry['type']) == 'button')
                        {
                            $result .= '<div "folder">';
                        }
                                              
                        if (htmlentities($entry['type']) == 'item')
                        {
                            $result .= '<div class="doc">';
                        }
                        
                        $result .= '<a class="'.htmlentities($entry['type']).'" href="'.$entry['href'];
                        $result .= '">'.htmlentities($entry['name']) . '</a>';
                        
                        if (isset($entry['content']))
                        {
                        	$result .= '<div class="doc">';
                        	$result .= get_adminmenu_div($entry['content']);
                        	$result .= '</div>';
                        }
                    	
                    	if (htmlentities($entry['type']) == 'item')
                        {
                            $result .= '</div>';
                        }
                    	
                        if (htmlentities($entry['type']) == 'button')
                        {
                            $result .= '</div>';
                        }
                    }
                    
                    return $result;
                }

                $adminmenu = build_editormenu($adminmenudb); unset($adminmenudb);
{/php}

<h2> Menueditor </h2>

{doc_raw}
<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/adminmenu/DynamicTree.css" />
{* bei src= die anführungsstriche setzen *}
{literal}                        
<script type="text/javascript" src="{/literal}{$www_core_tpl_root}{literal}/admin/adminmenu/DynamicTreeBuilder.js"></script>
<script type="text/javascript" src="{/literal}{$www_core_tpl_root}{literal}/admin/adminmenu/plugins.js"></script>
<style type="text/css">
    body { background: #F1EFE2; }
    body, table { font-family: georgia, sans-serif; font-size: 11px; }
    form { margin: 0; }
    input,select { font-size: 11px; font-family: georgia, tahoma, verdana; }
    input[readonly] { border: 1px solid #7F9DB9; background: #ffffff; }
    a { color: #0000ee; text-decoration: none; }
    a:hover { color: #0000ee; text-decoration: underline; }
    p { margin-top: 0; margin-bottom: 1em; }
    #tree-plugin, #tree-plugin-button-import-html { display: none; }
    #tree-plugin-textarea { white-space: nowrap; }
    </style>
    {/literal}
{/doc_raw}


<table cellspacing="0" cellpadding="10" style="margin-top: 1em;">
    <tr>
        <td valign="top">

            <div class="DynamicTree">
                <div class="wrap1">
                    <div class="top">Tree Builder</div>
                    <div class="wrap2" id="tree">
                         {php}
                         echo get_adminmenu_div($adminmenu);
                         {/php}
                    </div>
                </div>
                <div class="actions">
                    <a id="tree-moveUp" class="moveUp" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/moveUp.gif" width="20" height="20" alt="" /></a>
                    <a id="tree-moveDown" class="moveDown" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/moveDown.gif" width="20" height="20" alt="" /></a>
                    <a id="tree-moveLeft" class="moveLeft" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/moveLeft.gif" width="20" height="20" alt="" /></a>
                    <a id="tree-moveRight" class="moveRight" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/moveRight.gif" width="20" height="20" alt="" /></a>
                    <a id="tree-insert" class="insert" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/insert.gif" width="20" height="20" alt="" /></a>
                    <a id="tree-info" class="info" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/info.gif" width="20" height="20" alt="" /></a>
                    <a id="tree-remove" class="remove" href="javascript:void(0)"><img src="{$www_core_tpl_root}/admin/adminmenu/images/delete.gif" width="20" height="20" alt="" /></a>
                    <div class="tooltip" id="tree-tooltip"></div>
                </div>
                <div id="tree-insert-form">
                    <form action="javascript:void(0)" method="get">
                        <table cellspacing="0" cellpadding="0">
                        <tr id="tree-insert-where-div">
                            <td class="label">Where</td>
                            <td><select id="tree-insert-where" name="tree-insert-where" class="where"><option value="before">Before</option><option value="after">After</option></select></td>
                        </tr>
                        <tr>
                            <td class="label">Type</td>
                            <td><select id="tree-insert-type" name="tree-insert-type"><option value="doc">Document</option><option value="folder">Folder</option></select></td>
                        </tr>
                        <tr>
                            <td class="label">Name</td>
                            <td><input class="input" size="20" id="tree-insert-name" name="tree-insert-name" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">Href</td>
                            <td><input class="input" size="20" id="tree-insert-href" name="tree-insert-href" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">Title</td>
                            <td><input class="input" size="20" id="tree-insert-title" name="tree-insert-href" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">Target</td>
                            <td><input class="input" size="20" id="tree-insert-target" name="tree-insert-target" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input id="tree-insert-button" class="button" type="button" value="Insert" />
                                <input id="tree-insert-cancel" type="button" value="Cancel" />
                            </td>
                        </tr>
                        </table>
                    </form>
                </div>
                <div id="tree-info-form">
                    <form action="javascript:void(0)" method="get">
                        <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="label">Name</td>
                            <td><input class="input" size="20" id="tree-info-name" name="tree-info-name" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">Href</td>
                            <td><input class="input" size="20" id="tree-info-href" name="tree-info-href" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">Title</td>
                            <td><input class="input" size="20" id="tree-info-title" name="tree-info-href" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td class="label">Target</td>
                            <td><input class="input" size="20" id="tree-info-target" name="tree-info-target" type="text" value="" /></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input id="tree-info-button" class="button" type="button" value="Update" />
                                <input id="tree-info-cancel" type="button" value="Cancel" />
                            </td>
                        </tr>
                        </table>
                    </form>
                </div>
            </div>

        </td>
        <td valign="top">

            <a href="javascript:void(window.open('{$www_core_tpl_root}/admin/adminmenu/help.html', 'Contents', 'width=400,height=400,scrollbars=yes'))">HELP</a> <br />
            <br />

            <a id="tree-plugin-import-html" href="javascript:void(0)">Import from Html</a> <br />
            <a id="tree-plugin-export-html" href="javascript:void(0)">Export to Html</a> <br />
            <a id="tree-plugin-export-php" href="javascript:void(0)">Export to Php</a> <br />
            <a id="tree-plugin-export-sql" href="javascript:void(0)">Export to Sql</a> <br />

        </td>
        <td valign="top">

            <div id="tree-plugin">
        
                <b id="tree-plugin-header">Import from html</b> <br />
                <textarea id="tree-plugin-textarea" cols="50" rows="10"></textarea><br />
                <input id="tree-plugin-button-import-html" type="button" value="Import" />

            </div>

        </td>
    </tr>
    </table>
    
    {literal}
    <script type="text/javascript">
    var tree = new DynamicTreeBuilder("tree");
    tree.init();
    DynamicTreePlugins.call(tree);
    </script>
    <script type="text/javascript" src="{/literal}{$www_core_tpl_root}{literal}/admin/adminmenu/actions.js"></script>
    {/literal}
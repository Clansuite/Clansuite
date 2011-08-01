{* {$modules|var_dump} *}

<style type="text/css">
/*<![CDATA[*/
/* general usage */
.filetype-po, .filetype-po-r, .filetype-po-rw,
.filetype-mo, .filetype-mo-r, .filetype-mo-rw { cursor: default; display:block; float: left; margin-top: 2px; height: 12px; width: 18px;}
.filetype-po { background: url({$www_root_themes_core}images/po.gif) no-repeat 0 0; }
.filetype-po-r { cursor: pointer !important; background: url({$www_root_themes_core}images/po.gif) no-repeat -18px 0; }
.filetype-po-rw { background: url({$www_root_themes_core}images/po.gif) no-repeat -36px 0; }
.filetype-mo { margin-left: 5px; background: url({$www_root_themes_core}images/mo.gif) no-repeat 0 0; }
.filetype-mo-r { cursor: pointer !important; margin-left: 5px; background: url({$www_root_themes_core}images/mo.gif) no-repeat -18px 0; }
.filetype-mo-rw { margin-left: 5px; background: url({$www_root_themes_core}images/mo.gif) no-repeat -36px 0; }

/* overview page styles */
.csp-active { background-color: #E7F7D3; }
*:first-child + html tr.csp-active td{ background-color: #E7F7D3; }
.csp-type-name { 	margin: 0pt 10px 1em 0pt; }
.csp-type-info {}
table.csp-type-info td {	padding:0; border-bottom: 0px; }
table.csp-type-info td.csp-info-value { padding:0 5px; }


/*table.mo-list td { border-color: #FFFFFF #ACA899 #ACA899 #ACA899;}
table.mo-list td { padding:3px 0 3px 5px;border-bottom: 0px !important; }
table.mo-list tr.mo-list-head td, table.mo-list tr.mo-list-desc td { border-bottom: 1px solid #aaa !important; }
*/
.ta-right { text-align: right; }
tr.mo-file:hover td { border-bottom: 1px dashed #666 !important; }

table.Datagrid tr:nth-child(odd)		{ background-color:#eee; }
table.Datagrid tr:nth-child(even)		{ background-color:#fff; }

.name { font-size: 12px; font-weight: bold; }

/*]]>*/
</style>
<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}css/datagrid.css" />

<table width="100%" cellspacing="0" cellpadding="0" border="0" class="Datagrid">
<tbody>
    <tr>        
        <th class="ColHeader">{t}Module{/t}</th>
        <th class="ColHeader">{t}Languages{/t}</th>
    </tr>

   {foreach $modules as $module}

   {* we need to construct some array key names *}
   {assign var=modulename_info value="`$module.name`_info"}
   {assign var=modulename_package value="`$module.name`_package"}

    <tr class="DatagridRow DatagridRow-Row_{$module@iteration}">
        <td>
             <h3 class="name">{$module.name|ucfirst}</h3>                
        </td>
        <td>
            <table width="100%" cellspacing="0" summary="localization|modules" class="mo-list" id="mo-list-{$module@iteration}">
            <tbody>

                {* Table Header: Language - Permissions - Actions *}

                <tr class="mo-list-desc">
                    <td nowrap="nowrap" style="text-align: center;" width="30%">
                        <span style="float:center;">
                            {t count=$module.languages|count 1=$module.languages|count plural="%1 Languages"}%1 Language{/t}                    
                        </span>                    
                        <span style="float:right;">     
                            <a href="{$www_root}index.php?mod=languages&sub=admin&action=addlanguage&modulename={$module.name}">Add Language</a>
                        </span>
                    </td>
                    <td nowrap="nowrap" style="text-align: center;" width="30%">{t}Permissions{/t}</td>
                    <td nowrap="nowrap" style="text-align: center;" width="30%">{t}Actions{/t}</td>
                </tr>

            {foreach $module.languages as $language}
            
                {if is_string($language)}
                <tr><td> {$language} </td></tr>
                {break}
                {/if}
            
                <tr class="mo-file" lang="{$language.lang_native}">
                    
                    {* Flag and Name of Language *}
                    
                    <td>
                        <img src="{$www_root_themes_core}images/countries/{$language.country_www}.png"
                             alt="(locale: {$language.lang})"
                             title="Locale: {$language.lang_native}">
                        &nbsp;{$language.lang}
                    </td>
                    
                    {* File Permissions of .po|mo files *}
                    
                    <td nowrap="nowrap" align="center">
                        <div style="width: 44px;">
                            
                            {if array_key_exists('po', $language)} 
                            <a title="{$language.po.timestamp}" class="filetype-po{$language.po.cssClass}">&nbsp;</a>
                            {else}
                            <a title="No po file found." class="filetype-po">&nbsp;</a>
                            {/if} 
                            
                            <span style="width: 2px;" />
                            
                            {if array_key_exists('mo', $language)} 
                            <a title="{$language.mo.timestamp}" class="filetype-mo{$language.mo.cssClass}">&nbsp;</a>
                            {else}
                            <a title="No mo file found." class="filetype-mo">&nbsp;</a>
                            {/if} 
                                                     
                        </div>
                    </td>
                   
                    {* Actions: Edit - Rescan - Delete *}
                    
                    <td nowrap="nowrap" style="text-align: center;">
                    
                    {* Do not display edit and delete link for english language. 
                       English is hardcoded and not editable. *}
                    {if $language.lang == 'English'}
                        <a href="{$www_root}index.php?mod=languages&sub=admin&action=scanmodule&modulename={$module.name}">Rescan</a>
                    {else}
                        <a href="/@todo">Edit</a>                       
                        <span> | </span>
                        <a href="/@todo">Delete</a>
                    {/if} 
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
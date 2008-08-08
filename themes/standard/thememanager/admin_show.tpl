{*  {$themes|@var_dump} *}

<h2>Thememanager</h2>

<table border="0" cellspacing="1" cellpadding="3" style="width:99%">

    <caption class="td_header">Thememanager</caption>
    
    {*
    <tr class="tr_row1">
        <td height="20" colspan="8" align="right">
    *}
           {* {include file="tools/paginate.tpl"} *}
    {*
        </td>
    </tr>
  
    {*
    <tr class="td_header">
        <th>{columnsort html='Datum'}</th>
        <th>{columnsort selected_class="selected"
                        html='Title'}</th>
        <th>{columnsort html='Kategorie'}</th>
        <th>{columnsort html='Verfasser'}</th>
        <th>Draft</th>
        <th>Action</th>
        <th>Select</th>
    </tr>
    *}

{foreach item=theme from=$themes} 
   
    <tr  class="{cycle values="tr_row2,tr_row1"}">
        <td> <img src="{$www_root_themes}/{$theme.dirname}/preview_thumb.png"></td>
        <td> <b>{$theme.name}</b> - {$theme.author}</td>
        <td> {if isset($theme.required_clansuite_version)} {$theme.required_clansuite_version} {/if}</td>
        <td> {$theme.date}</td>
        <td> {$theme.fullpath} | {$theme.dirname}</td>
        <td>  
             <form action="index.php?mod=thememanager&amp;sub=admin&amp;action=setusertheme" method="post">
                <input type="button" value="{t}Set as my theme{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=thememanager&amp;sub=admin&amp;action=setusertheme&amp;id={/literal}{$theme.dirname}{literal}&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:900, height: 600});{/literal}' />
                <input type="submit" name="submit" value="{t}Delete{/t}" />
            </form>
        </td>
    </tr>

{/foreach}

</table>
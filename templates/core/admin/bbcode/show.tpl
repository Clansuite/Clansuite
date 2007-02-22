{doc_raw}
    {* Prototype + Tablegrid Extension *}
    <script src="{$www_core_tpl_root}/javascript/prototype/prototype.js" type="text/javascript"></script>
    <script src="{$www_core_tpl_root}/javascript/scriptaculous/scriptaculous.js" type="text/javascript"></script>
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/tablegrid.js"></script>
    {literal}
      <style type="text/css">
            /* Define the basic CSS used by TableGrid - Editable Ajax Table */
            .tableedit {
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 10px;
                /*width: 500px;*/
            }
            .tableedit td {
                /display: block;
                overflow: hidden;*/
                float: left;
                margin-left: 2px;
                border-bottom:1px solid #eeeeee;
                border-right:1px solid #eeeeee;
                background-color: #fff;
                padding: 3px;
                /*width: 85px;*/
                height: 20px;
            }

            .tableedit input {
                border: 1px solid #f0b604;
                width: 100px;
                padding-top: 1px;
                height: 14px;
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 10px;
            }

        </style>
   {/literal}
{/doc_raw}

<form action="index.php?mod=admin&sub=bbcode&action=create" method="post">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
    <tr class="tr_header">
        <td align="center">{translate}Tag Name{/translate}</td>
        <td align="center">{translate}Start Tag{/translate}</td>
        <td align="center">{translate}End Tag{/translate}</td>
        <td align="center">{translate}Content Type{/translate}</td>
        <td align="center">{translate}Allowed in{/translate}</td>
        <td align="center">{translate}Not allowed in{/translate}</td>
    </tr>
    <tr>
        <td class="cell2" align="center">
            <input type="text" class="input_text" value="" name="info[name]" />
        </td>
        <td class="cell1" align="center">
            <input type="text" class="input_text" value="" name="info[start_tag]" />
        </td>
        <td class="cell2" align="center">
            <input type="text" class="input_text" value="" name="info[end_tag]" />
        </td>
        <td class="cell1" align="center">
            <input type="text" class="input_text" value="" name="info[content_type]" />
        </td>
        <td class="cell2" align="center">
            <input type="text" class="input_text" value="" name="info[allowed_in]" />
        </td>
        <td class="cell1" align="center">
            <input type="text" class="input_text" value="" name="info[not_allowed_in]" />
        </td>
    </tr>
    <tr>
        <td class="cell1" align="center" colspan="6">
            <input type="submit" class="ButtonGreen" value="{translate}Create new BB Code{/translate}" />
        </td>
    </tr>
</table>
</form>

<table class="tableedit" cellspacing="0" cellpadding="0" border="0" width="90%" align="center" id="table_bbcode">
    <tr class="tr_header">
        <td width="70px">{translate}Tag Name{/translate}</td>
        <td width="100px">{translate}Start Tag{/translate}</td>
        <td width="100px">{translate}End Tag{/translate}</td>
        <td width="100px">{translate}Content Type{/translate}</td>
        <td width="100px">{translate}Allowed in{/translate}</td>
        <td width="100px">{translate}Not allowed in{/translate}</td>
        <td>{translate}Preview{/translate}</td>
    </tr>
{foreach key=schluessel item=wert from=$bb_codes}
    <tr>
        <td class="editcell" id="{$wert.bb_code_id}_name">
            {$wert.name|escape:"htmlall"}
        </td>
        <td class="editcell" id="{$wert.bb_code_id}_start_tag">
            {$wert.start_tag|escape:"htmlall"}
        </td>
        <td class="editcell" id="{$wert.bb_code_id}_end_tag">
            {$wert.end_tag|escape:"htmlall"}
        </td>
        <td class="editcell" id="{$wert.bb_code_id}_content_type">
            {$wert.content_type|escape:"htmlall"}
        </td>
        <td class="editcell" id="{$wert.bb_code_id}_allowed_in">
            {$wert.allowed_in|escape:"htmlall"}
        </td>
        <td class="editcell" id="{$wert.bb_code_id}_not_allowed_in">
            {$wert.not_allowed_in|escape:"htmlall"}
        </td>
        <td class="cell2">
            {$wert.preview}
        </td>
    </tr>
{/foreach}
        <script type="text/javascript">new TableGrid('table_bbcode', '6', 'index.php?mod=admin&sub=bbcode&action=ajaxupdate_bbcode');</script>
</table>
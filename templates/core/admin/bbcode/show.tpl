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
                margin: 0px;
                border-bottom:1px solid #eeeeee;
                border-right:1px solid #eeeeee;
                background-color: #fff;
                padding: 0px;
                /*width: 85px;*/
                height: 25px;
            }

            .tableedit input {
                border: 1px solid #f0b604;
                /*width: 64px;
                padding-top: 1px;
                height: 17px;*/
            }

        </style>
   {/literal}
{/doc_raw}

<table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
    <tr class="tr_header">
        <td>{translate}Tag Name{/translate}</td>
        <td>{translate}Start Tag{/translate}</td>
        <td>{translate}End Tag{/translate}</td>
        <td>{translate}Content Type{/translate}</td>
        <td>{translate}Allowed in{/translate}</td>
        <td>{translate}Not allowed in{/translate}</td>
    </tr>
{foreach key=schluessel item=wert from=$bb_codes}
    <tr>
        <td class="cell2">
            {$wert.name|escape:"htmlall"}
        </td>
        <td class="cell1">
            {$wert.start_tag|escape:"htmlall"}
        </td>
        <td class="cell2">
            {$wert.end_tag|escape:"htmlall"}
        </td>
        <td class="cell1">
            {$wert.content_type|escape:"htmlall"}
        </td>
        <td class="cell2">
            {$wert.allowed_in|escape:"htmlall"}
        </td>
        <td class="cell1">
            {$wert.not_allowed_in|escape:"htmlall"}
        </td>
    </tr>
{/foreach}
</table>
{doc_raw}     
    {literal}
    <script type="text/javascript">
        function clip_area(id)
        {
            if( !this.old_area )
            {
                document.getElementById('select_text').style.display = 'none'
                var old_area = '';
            }
            if( old_area != '' && document.getElementById(this.old_area).style.display == 'block' )
            {
                document.getElementById(this.old_area).style.display = 'none';
            }
            
            if( document.getElementById(id).style.display == 'none' )
            {
                document.getElementById(id).style.display = 'block';
                this.old_area = id;
            }
            else
            {
                document.getElementById(id).style.display = 'none'
            }
        }
    </script>
    {/literal}
{/doc_raw}
{* Debuganzeige, wenn DEBUG = 1 | {$permissions_data|@var_dump} 
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$permissions_data} {/if}*}



<table align="center" cellpadding="0" cellspacing="0" border="0">
    <tr class="tr_header">
        <td align="center" width="100">
            {translate}Areas{/translate}
        </td>
        <td>
            {translate}Rights{/translate}
        </td>
    </tr>
    <tr class="tr_row1">
        <td align="center">
            {foreach key=area_id item=area_array from=$areas}
                <input type="button" value="{$area_array.name}" onclick="clip_area('area_{$area_array.name}')" class="ButtonYellow" style="width: 80px";><br />
            {/foreach}
            {if count($unassigned) > 0}
                <input type="button" value="{translate}Unassigned{/translate}" onclick="clip_area('area_unassigned')" class="ButtonRed" style="width: 80px";>
            {/if}
        </td>
        <td align="center" width="700" height="200" style="padding: 0px; border: 0px;">
            <div class="cell1" style="height: 100%;" id="select_text">
            {translate}Select the area on the left side{/translate}
            </div>
            {foreach key=area_id item=area_array from=$areas}
                <form action="index.php?mod=admin&sub=permissions&action=delete_right" method="POST">
                    <table style="display: none;" id="area_{$area_array.name}" cellpadding="0" cellspacing="0" border="0">
                        <tr class="tr_header_small">
                            <td width="100">{translate}Right ID{/translate}        </td>
                            <td width="100">{translate}Right Name{/translate}     </td>
                            <td width="200">{translate}Description{/translate}     </td>
                            <td width="200">{translate}Actions{/translate}          </td>
                            <td width="5%"> {translate}Delete{/translate}          </td>
                        </tr>                        
                        {foreach key=right_name item=right_array from=$rights.$area_id}
                            <tr class="{cycle values="tr_row1, tr_row2"}">
                                <td><input type="hidden" name="ids[]" value="{$right_array.right_id}" />{$right_array.right_id}</td>
                                <td>{$right_array.name}</td>
                                <td>{$right_array.description}</td>
                                
                                <td align="center">
                                    <a href="index.php?mod=admin&sub=permissions&action=edit_right&right_id={$right_array.right_id}"><input type="Button" class="ButtonGreen" value="{translate}Edit{/translate}" /></a>
                                    <a href="index.php?mod=admin&sub=permissions&action=lookup_users&right_id={$right_array.right_id}"><input type="Button" class="ButtonYellow" value="{translate}Lookup users{/translate}" /></a>
                                </td>
                                <td align="center">
                                    <input type="hidden" name="ids[]" value="{$right_array.right_id}">
                                    <input name="delete[]" type="checkbox" value="{$right_array.right_id}">
                                </td>
                            </tr>
                        {/foreach}
                        <tr class="tr_row1">
                            <td align="right" colspan="5">
                                <a href="index.php?mod=admin&sub=permissions&action=edit_area&area_id={$area_id}"><input type="Button" class="ButtonGreen" value="{translate}Edit the area{/translate}" /></a>
                                <a href="index.php?mod=admin&sub=permissions&action=delete_area&area_id={$area_id}"><input type="Button" class="ButtonRed" value="{translate}Delete the area{/translate}" /></a>
                                <input type="submit" name="submit" class="ButtonRed" value="{translate}Delete selected Permissions{/translate}" />
                                <input type="reset" name="submit" class="ButtonGrey" value="{translate}Reset{/translate}" />
                        </tr>
                    </table>
                </form>
            {/foreach}
                <form action="index.php?mod=admin&sub=permissions&action=delete_perm" method="POST">
                    <table style="display: none;" id="area_unassigned" cellpadding="0" cellspacing="0" border="0">
                        <tr class="tr_header_small">
                            <td width="100">{translate}Right ID{/translate}        </td>
                            <td width="100">{translate}Right Name{/translate}     </td>
                            <td width="200">{translate}Description{/translate}     </td>
                            <td width="200">{translate}Actions{/translate}          </td>
                            <td width="5%"> {translate}Delete{/translate}          </td>
                        </tr>                        
                        {foreach key=right_name item=right_array from=$unassigned}
                            <tr class="{cycle values="tr_row1, tr_row2"}">
                                <td><input type="hidden" name="ids[]" value="{$right_array.right_id}" />{$right_array.right_id}</td>
                                <td>{$right_array.name}</td>
                                <td>{$right_array.description}</td>
                                
                                <td align="center">
                                    <a href="index.php?mod=admin&sub=permissions&action=edit_perm&right_id={$right_array.right_id}"><input type="Button" class="ButtonGreen" value="{translate}Edit{/translate}" /></a>
                                    <a href="index.php?mod=admin&sub=permissions&action=lookup_users&right_id={$right_array.right_id}"><input type="Button" class="ButtonYellow" value="{translate}Lookup users{/translate}" /></a>
                                </td>
                                <td align="center">
                                    <input type="hidden" name="ids[]" value="{$right_array.right_id}">
                                    <input name="delete[]" type="checkbox" value="{$right_array.right_id}">
                                </td>
                            </tr>
                        {/foreach}
                        <tr class="tr_row1">
                            <td align="right" colspan="5">
                                <input type="submit" name="submit" class="ButtonRed" value="{translate}Delete selected right(s){/translate}" />
                                <input type="reset" name="submit" class="ButtonGrey" value="{translate}Reset{/translate}" />
                        </tr>
                    </table>
                </form>            
        </td>
    </tr>
    <tr class="tr_row1">
        <td colspan="2" align="right">
            <a href="index.php?mod=admin&sub=permissions&action=create_area"><input type="Button" class="ButtonGreen" value="{translate}Create new area{/translate}" /></a>
            <a href="index.php?mod=admin&sub=permissions&action=create_right"><input type="Button" class="ButtonGreen" value="{translate}Create new right{/translate}" /></a>
        </td>
    </tr>
</table>
{* DEBUG OUTPUT of assigned Arrays:
	{$boards|@var_dump}
	{$paginate|@var_dump}
*}

{doc_raw}
 {* Tabs *}
    <link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/admin/luna.css" />
    <script type="text/javascript" src="{$www_root_tpl_core}/javascript/tabpane.js"></script>
{/doc_raw}


{$err}

<div class="tab-pane" id="tab-pane-1">


   <div class="tab-page" id="tab-page-1">
      <h2 class="tab">Overview</h2>

      <b>Overview</b> 
      <br /> {* Show Board Cats | New Board-Category *}
      
      <div style="padding-left: 20px;">
      {* Doing an advanced Smarty recursion until our heads explode *}
                    {defun name="boardtreerecursion1" list=$boards nestinglevel=0}
                        
                        {* nestinglevel counter *}
                        {assign var=nestinglevel value=$nestinglevel+1}
                        
                        {foreach from=$list item=element}
                            
                         
                                {* toplevel element without indention *}
                                {if $nestinglevel == 1}
                                    {$element.name}<br />
                                {else}
                                    |{$element.name|indent:$nestinglevel:"- "}&nbsp;<br />
                                {/if}                        
                           
                            
                               {* Call Recursion if children exists and until our heads explode *}
                               {if !empty($element.children)}                          
                                  {fun name="boardtreerecursion1" list=$element.children}
                               {/if}
                            
                           
                        
                        {/foreach}
                        </ul>
                    {/defun}
        </div>

   </div>

   <div class="tab-page" id="tab-page-2">
      <h2 class="tab"><b>Add Board</b></h2>

   

      <form action="index.php?mod=board&amp;sub=admin&amp;action=create_board" method="post" accept-charset="UTF-8">
      <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="td_header_small"  colspan="2">
                    {translate}Name, Description and Position of the new Forum{/translate}
                </td>
            </tr>
            {*
            <tr>
                <td class="cell2" width="15%">
                    {translate}Category{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <select name="newboard[category]" class="input_text">
                        <option value="catid" selected="selected">NO CAT</option>
                        <option value="catid">catname (#id)</option>
                    </select>
                </td>
            </tr>
            *}
            <tr>
                <td class="cell2" width="15%">
                    {translate}Name{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="name" name="newboard[name]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Description{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="desc" name="newboard[description]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Position{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    
                    <input type="radio" value="before" name="newboard[positiontype]" /> {translate}before{/translate}
                    <input type="radio" value="child" name="newboard[positiontype]"  /> {translate}child{/translate}
                    <input type="radio" value="after" name="newboard[positiontype]" checked="checked"  /> {translate}after{/translate}
                    
                    <select name="newboard[parentid]" class="input_text">
                    
                    {* Doing an advanced Smarty recursion until our heads explode *}
                    {defun name="boardtreerecursion" list=$boards nestinglevel=0}
                        
                        {* nestinglevel counter *}
                        {assign var=nestinglevel value=$nestinglevel+1}
                        
                        {foreach from=$list item=element}
                            
                            <option value="{$element.forumid}">
                                {* toplevel element without indention *}
                                {if $nestinglevel == 1}
                                    {$element.name}
                                {else}
                                    |{$element.name|indent:$nestinglevel:"- "}&nbsp;    
                                {/if}                        
                            </option>
                            
                               {* Call Recursion if children exists and until our heads explode *}
                               {if !empty($element.children)}                          
                                    {fun name="boardtreerecursion" list=$element.children}
                               {/if}
                            
                        {/foreach}
                    
                    {/defun}
                    
                    </select>
                    
                </td>
            </tr>
            
            <tr>
                <td class="td_header_small"  colspan="2">
                    {translate}Access related Settings{/translate}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Access Groups{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                   Permissionssystem Groups
                   <br />
                   Rank Groups
                   <br />
                   all
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Restrict Actions{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="normal" name="newboard[permissiontype]" checked="checked" /> {translate}normal{/translate}
                    <input type="radio" value="readonly" name="newboard[permissiontype]"  /> {translate}read only{/translate}
                    <input type="radio" value="onlyanswer" name="newboard[permissiontype]"  /> {translate}only answer{/translate}
                    <input type="radio" value="nopolls" name="newboard[permissiontype]"  /> {translate}no polls{/translate}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Moderators{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    modlist ... username, userid
                </td>
            </tr>
            <tr class="tr_row1">
                <td align="right" colspan="9">
                    <input type="submit" class="ButtonGreen" value="{translate}Add Board{/translate}" name="submit" />
                </td>
            </tr>            
       </table>
   </div>
   </form>
</div>

{* #### Init TabPane #### *}
<script type="text/javascript">setupAllTabs();</script>
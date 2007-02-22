{$chmod_tpl}

{if $err.wrong_filetype == 1}
<div class="cell1" align="center">
    <b>{translate}That file has the wrong filetype.{/translate}</b>
</div>
{/if}

{if $err.no_correct_upload == 1}
<div class="cell1" align="center">
    <b>{translate}The upload failed. Please try again.{/translate}</b>
</div>
{/if}

<form enctype="multipart/form-data" action="index.php?mod=admin&sub=modules&action=import" method="post">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    
    <td class="td_header" width="20%">
    {translate}Information{/translate}
    </td>
    
    <td class="td_header" width="80%">
    {translate}File{/translate}
    </td>
</tr>
<tr>

    <td class="cell1">
        {translate}Only tar files are allowed which were exported by this CMS{/translate}
    </td>
    
    <td class="cell2">
         <input type="file" name="file">
    </td>

</tr>
</table>
<p align="center">
    <input class="ButtonGrey" type="submit" value="{translate}Import module{/translate}" name="submit">
</p>
</form>
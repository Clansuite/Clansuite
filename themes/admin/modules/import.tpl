{$chmod_tpl}

{if $err.wrong_filetype == 1}
<div class="cell1" align="center">
    <strong>{t}That file has the wrong filetype.{/t}</strong>
</div>
{/if}

{if $err.no_correct_upload == 1}
<div class="cell1" align="center">
    <strong>{t}The upload failed. Please try again.{/t}</strong>
</div>
{/if}

<form enctype="multipart/form-data" action="index.php?mod=controlcenter&sub=modules&action=import" method="post" accept-charset="UTF-8">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    
    <td class="td_header" width="20%">
    {t}Information{/t}
    </td>
    
    <td class="td_header" width="80%">
    {t}File{/t}
    </td>
</tr>
<tr>

    <td class="cell1">
        {t}Only tar files are allowed which were exported by this CMS{/t}
    </td>
    
    <td class="cell2">
         <input type="file" name="file">
    </td>

</tr>
</table>
<p align="center">
    <input class="ButtonGrey" type="submit" value="{t}Import module{/t}" name="submit">
</p>
</form>
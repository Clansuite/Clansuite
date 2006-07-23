{$chmod_tpl}

{if $err.wrong_filetype == 1}
<div id="cell1" align="center">
    <b>{translate}That file has the wrong filetype.{/translate}</b>
</div>
{/if}

{if $err.no_correct_upload == 1}
<div id="cell1" align="center">
    <b>{translate}The upload failed. Please try again.{/translate}</b>
</div>
{/if}

<form enctype="multipart/form-data" action="{$www_root}/index.php?mod=admin&sub=modules&action=import" method="POST">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    
    <td id="td_header" width="20%">
    {translate}Information{/translate}
    </td>
    
    <td id="td_header" width="80%">
    {translate}File{/translate}
    </td>
</tr>
<tr>

    <td id="cell1">
        {translate}Only tar files are allowed which were exported by this CMS{/translate}
    </td>
    
    <td id="cell2">
         <input type="file" name="file">
    </td>

</tr>
</table>
<p align="center">
    <input class="input_submit" type="submit" value="{translate}Import module{/translate}" name="submit">
</p>
</form>
{if $err.mod_folder_not_writeable == 1}
<form action="index.php?mod=admin&sub=modules&action=chmod" method="post" accept-charset="UTF-8">
<div class="cell1" align="center">
    <strong>{translate}The module folder is not writeable! You have to chmod the folder to 755. You can try to chmod the folder by pressing the button below.{/translate}</strong>
</div>
<input type="hidden" name="type" value="modules">
<input type="hidden" name="chmod_redirect_url" value="{$chmod_redirect_url|urlencode}">
<p align="center"><input type="submit" class="ButtonGrey" name="chmod" value="{translate}Set CHMOD for modules folder{/translate}"></p>
</form>
{/if}
{if $err.upload_folder_not_writeable == 1}
<form action="index.php?mod=admin&sub=modules&action=chmod" method="post" accept-charset="UTF-8">
<div class="cell1" align="center">
    <strong>{translate}The upload folder is not writeable! You have to chmod the folder to 755. You can try to chmod the folder by pressing the button below.{/translate}</strong>
</div>
<input type="hidden" name="type" value="uploads">
<input type="hidden" name="chmod_redirect_url" value="{$chmod_redirect_url|urlencode}">
<p align="center"><input type="submit" class="ButtonGrey" name="chmod" value="{translate}Set CHMOD for upload folder{/translate}"></p>
</form>
{/if}
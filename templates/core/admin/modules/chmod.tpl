{if $err.mod_folder_not_writeable == 1}
<form action="/index.php?mod=admin&sub=modules&action=chmod&type=modules" method="POST">
<div id="cell1" align="center">
    <b>{translate}The module folder is not writeable! You have to chmod the folder to 755. You can try to chmod the folder by pressing the button below.{/translate}</b>
</div>
<p align="center"><input type="submit" class="input_submit" name="chmod" value="{translate}Set CHMOD for modules folder{/translate}"></p>
</form>
{/if}
{if $err.upload_folder_not_writeable == 1}
<form action="/index.php?mod=admin&sub=modules&action=chmod&type=uploads" method="POST">
<div id="cell1" align="center">
    <b>{translate}The upload folder is not writeable! You have to chmod the folder to 755. You can try to chmod the folder by pressing the button below.{/translate}</b>
</div>
<p align="center"><input type="submit" class="input_submit" name="chmod" value="{translate}Set CHMOD for upload folder{/translate}"></p>
</form>
{/if}
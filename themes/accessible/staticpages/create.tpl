{move_to target="pre_head_close"}
	<script type="text/javascript" src="{$www_root}core/fckeditor/fckeditor.js"></script>
{/move_to}

{if $err.no_special_chars == 1}
    {error title="Special Chars"}
        No special chars except '_' are allowed.
    {/error}
{/if}

{if $err.give_correct_url == 1}
    {error title="Valid URL"}
        Please enter a valid URL or leave the field blank.
    {/error}
{/if}

{if $err.fill_form == 1}
    {error title="Fill form"}
        Please fill all fields.
    {/error}
{/if}

{if $err.static_already_exist == 1}
    {error title="Already exists"}
        We are sorry but a static page with this name already exists in the database.
    {/error}
{/if}
<form action="index.php?mod=controlcenter&sub=static&action=create" method="post" accept-charset="UTF-8">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="td_header" width="100%" colspan="2">
        Edits    </td>

    </tr>
    <tr>

        <td class="td_header_small" width="40">
        {t}Title{/t}    </td>

        <td class="td_header_small" width="99%">
        {t}Information{/t}    </td>

    </tr>
    <tr>
        <td class="cell1">
            <strong>{t}Title{/t}:</strong>
        </td>
        <td class="cell2">
            <input name="title" type="text" value="{$title|escape:html}" class="input_text">
        </td>
    </tr>
    <tr>
        <td class="cell1">
            <strong>{t}Description{/t}:</strong>
        </td>
        <td class="cell2">
            <input name="description" type="text" value="{$description|escape:html}" class="input_text">
        </td>
    </tr>
    <tr>
        <td class="cell1">
            <strong>URL:</strong>
        </td>
        <td class="cell2">
            <input name="url" type="text" value="{$url|escape:html}" class="input_text"><br />
            <span id="font_mini">
            {t}If you enter an URL the content below will not be recognized.{/t}<br />
            {t}Instead the content of the URL will be taken as static page.{/t}<br />
            {t}This is no redirection! The content will be taken "as is".{/t}
            </span><br />
            <input type="radio" name="iframe" value="1" checked>{t}Use the URL in an iFrame{/t}<br />
            {t}Height of the iframe:{/t} <input type="text" name="iframe_height" value="300" size="5"><br />
            <input type="radio" name="iframe" value="0">{t}Use the URL by file_get_contents();{/t}
        </td>
    </tr>
    <tr>
        <td colspan="2" class="cell3">
        	<script type="text/javascript">

            var sBasePath = "{$www_root}core/fckeditor/";

            var oFCKeditor = new FCKeditor( 'html' );
            oFCKeditor.BasePath	= sBasePath;
            oFCKeditor.Height	= 400;
            oFCKeditor.Value	= '{$html|escape:javascript}';
            oFCKeditor.Create();
        	</script>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="cell2" align="center">
            <input class="ButtonGrey" type="submit" name="submit" value="{t}Create static page{/t}" />
        </td>
    </tr>
</table>

</form>
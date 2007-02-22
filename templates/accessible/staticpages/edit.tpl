{doc_raw}
	<script type="text/javascript" src="{$www_root}/core/fckeditor/fckeditor.js"></script>
{/doc_raw}

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
<form action="index.php?mod=admin&sub=static&action=edit" method="post" target="_self">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="td_header" width="100%" colspan="2">
        Edits    </td>
    
    </tr>
    <tr>
        
        <td class="td_header_small" width="40">
        Title    </td>
        
        <td class="td_header_small" width="99%">
        Information    </td>   
    
    </tr>
    <tr>
        <td class="cell1">
            <b>Title:</b>
        </td>
        <td class="cell2">
            <input name="id" type="hidden" value="{$info.id}">
            <input name="orig_title" type="hidden" value="{$info.title}">
            <input name="title" type="text" value="{$info.title|escape:html}" class="input_text">
        </td>
    </tr>
    <tr>
        <td class="cell1">
            <b>Description:</b>
        </td>
        <td class="cell2">
            <input name="description" type="text" value="{$info.description|escape:html}" class="input_text">
        </td>
    </tr>
    <tr>
        <td class="cell1">
            <b>URL:</b>
        </td>
        <td class="cell2">
            <input name="url" type="text" value="{$info.url|escape:html}" class="input_text"><br />
            <span id="font_mini">
            {translate}If you enter an URL the content below will not be recognized.{/translate}<br />
            {translate}Instead the content of the URL will be taken as static page.{/translate}<br />
            {translate}This is no redirection! The content will be taken "as is".{/translate}
            </span><br />
            <input type="radio" name="iframe" value="1" {if $info.iframe == '1'}checked{/if}>{translate}Use the URL in an iFrame{/translate}<br />
            {translate}Height of the iframe:{/translate} <input class="input_text" type="text" name="iframe_height" value="{$info.iframe_height}" size="5"><br />
            <input type="radio" name="iframe" value="0" {if $info.iframe == '0'}checked{/if}>{translate}Use the URL by file_get_contents();{/translate}
        </td>
    </tr>
    <tr>
        <td colspan="2" class="cell3">
        	<script type="text/javascript">

            var sBasePath = "{$www_root}/core/fckeditor/";
            
            var oFCKeditor = new FCKeditor( 'html' );
            oFCKeditor.BasePath	= sBasePath;
            oFCKeditor.Height	= 400;
            oFCKeditor.Value	= '{$info.html|escape:javascript}';
            oFCKeditor.Create();
        	</script>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="cell2" align="center">
            <input class="ButtonGrey" type="submit" name="submit" value="{translate}Edit the static page{/translate}" />
        </td>
    </tr>
</table>

</form>
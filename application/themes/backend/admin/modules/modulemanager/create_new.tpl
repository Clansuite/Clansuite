{$chmod_tpl}

{if $err.no_special_chars == 1}
    {error title="No special chars"}
        No special chars except '_' are allowed, because of php and file relating sourcecode. Whitespaces are allowed, except for the name.
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

{if $err.mod_already_exist == 1}
    {error title="Already exists"}
        We are sorry but a module with this name already exists as folder or database setting.
    {/error}
{/if}

{if $err.mod_not_existing == 1}
    {error title="Mod not existing"}
        The main mod you have selected does not exist.
    {/error}
{/if}

{if $err.sub_already_exists == 1}
    {error title="Already exists"}
        We are sorry but a submodule with this name already exists as file.
    {/error}
{/if}

{move_to target="pre_head_close"}

{*

<script type="text/javascript">

function str_replace (search, replace, subject)
{
  var result = "";
  var  oldi = 0;
  for (i = subject.indexOf (search)
     ; i > -1
     ; i = subject.indexOf (search, i))
  {
    result += subject.substring (oldi, i);
    result += replace;
    i += search.length;
    oldi = i;
  }
  return result + subject.substring (oldi, subject.length);
}

function add_col()
{
    var row_container = document.getElementById('add_col_container').innerHTML;
    if( this.key != 0 && !this.key )
    {
        this.key = 2;
    }
    else
    {
        this.key++;
    }
    row_replaced = str_replace('{$key}', this.key, row_container);
    document.getElementById('add_new').innerHTML = row_replaced;
    document.getElementById('add_new').id = 'col' + this.key;
    var newTR = document.createElement("tr");
    newTR.id = 'add_new';
    document.getElementById("all_cols").appendChild(newTR);
}

function rem_col(id)
{
    document.getElementById('col' + id).innerHTML = '';
    document.getElementById('col' + id).outerHTML = '';
}

</script>
*}
{/move_to}

<form action="index.php?mod=controlcenter&amp;sub=modules&amp;action=create_new" method="post" accept-charset="UTF-8">
<table cellspacing="0" cellpadding="0" border="0" align="center" width="400">
<tr>

    <td class="td_header" width="100">
        {t}Description{/t}
    </td>

    <td class="td_header" width="90%">
    {t}Needed information{/t}
    </td>

</tr>
<tr>

    <td class="cell1">
        {t}This are the information that are needed to be stored into the Database and for preparing the necessary files.{/t}
    </td>

    <td class="cell2">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr class="tr_row1"><td><strong>{t}Title:{/t}</strong></td><td><input class="input_text" type="text" name="title" value="{$smarty.post.title|escape:"html"}" /></td></tr>
            <tr class="tr_row1"><td><strong>{t}Name:{/t}<br /><span class="font_mini">?mod=name</span></strong></td><td><input class="input_text" type="text" name="name" value="{$smarty.post.name|escape:"html"}" /></td></tr>
            <tr class="tr_row1"><td><strong>{t}Description:{/t}</strong></td><td><input class="input_text" type="text" name="description" value="{$smarty.post.description|escape:"html"}" /></td></tr>
            <tr class="tr_row1"><td><strong>{t}Author:{/t}</strong></td><td><input class="input_text" type="text" name="author" value="{$smarty.post.author|escape:"html"}" /></td></tr>
            <tr class="tr_row1"><td><strong>{t}Copyright:{/t}</strong></td><td><input class="input_text" type="text" name="copyright" value="{$smarty.post.copyright|escape:"html"}" /></td></tr>
            <tr class="tr_row1"><td><strong>{t}Homepage:{/t}</strong></td><td><input class="input_text" type="text" name="homepage" value="{$smarty.post.homepage|escape:"html"}" /></td></tr>
            <tr class="tr_row1"><td><strong>{t}License:{/t}</strong></td><td><input class="input_text" type="text" name="license" value="{$smarty.post.license|escape:"html"}" /></td></tr>
            <tr class="tr_row1"><td><strong>{t}Enabled:{/t}</strong></td><td><input type="checkbox" name="enabled" value="1" /></td></tr>
            <tr class="tr_row1"><td><strong>{t}Core module:{/t}</strong></td><td><input type="checkbox" name="core" value="1" /></td></tr>
            <tr class="tr_row1">
                <td>
                    <strong>{t}Submodule:{/t}</strong>
                </td>
                <td>
                    <input type="checkbox" name="submodule" value="1" />
                    <select name="module_id" class="input_text">
                        <option value="0"></option>
                        {foreach key=key item=item from=$modules}
                            <option value="{$item.module_id}">{$item.name}</option>
                        {/foreach}
                    </select>
                </td>
                </tr>
        </table>
    </td>
</tr>
<tr>
    <td align="center" colspan="2" style="padding: 10px" class="cell1">
        <input class="ButtonGreen" type="submit" value="{t}Create the new module{/t}" name="submit" />
    </td>
</tr>
</table>
</form>

{*
<tr>
    <td class="cell1">
        {t}This optional information inserts a table and rows into the Database.{/t}
    </td>

    <td class="cell2">
        <table border="0" cellpadding="2" cellspacing="0" width="800">
            <tr>
            <td colspan="2">
            {if $err.sql_failure == 1}
                {error title="SQL Failure"}
                    {$err.sql_message}
                {/error}
            {/if}
            {if $err.sql_int_length == 1}
                {error title="SQL - Length as integer"}
                    Please make sure the length value is a number. For example 255 is the maximum for the col type VARCHAR.
                {/error}
            {/if}
            {if $err.sql_no_special_chars == 1}
                {error title="SQL - No special chars"}
                    No special chars except '_' are allowed in the cols and the tablename.
                {/error}
            {/if}
                <table border="0" cellpadding="2" cellspacing="0" id="all_cols" width="800">
                <tr>
                    <td width="50" align="center" class="cell1">
                        <strong>{t}Tablename:{/t}</strong>
                    </td>
                    <td class="cell2" colspan="6">
                        <strong>{$db_prefix}</strong><input class="input_text" type="text" name="db_table" value="{$smarty.post.db_table|escape:"html"}">
                    </td>
                </tr>
                <tr>
                    <td class="td_header_small" align="center">Nr.</td>
                    <td class="td_header_small" align="center">{t}Name{/t}</td>
                    <td class="td_header_small" align="center">{t}Type{/t}</td>
                    <td class="td_header_small" align="center">{t}Length{/t}</td>
                    <td class="td_header_small" align="center">{t}Extras{/t}</td>
                    <td class="td_header_small" align="center">{t}Keys{/t}</td>
                    <td class="td_header_small" align="center">{t}Options{/t}</td>
                </tr>
                {if is_array($smarty.post.db_cols) }
                {foreach key=key item=item from=$smarty.post.db_cols}
                    <tr id="col{$key}">
                        <td class="cell1" width="50" align="center">
                            <strong>{t}Col{/t} #{$key}:</strong>
                        </td>
                        <td class="cell2" width="50" align="center">
                            <input class="input_text" type="text" name="db_cols[{$key}][name]" value="{$item.name}">
                        </td>
                        <td class="cell1" align="center" width="50">
                            <select name="db_cols[{$key}][type]" class="input_text">
                                <option value="VARCHAR" selected>VARCHAR</option>
                                <option value="TINYINT">TINYINT</option>

                                <option value="TEXT">TEXT</option>
                                <option value="DATE">DATE</option>
                                <option value="SMALLINT">SMALLINT</option>
                                <option value="MEDIUMINT">MEDIUMINT</option>
                                <option value="INT">INT</option>
                                <option value="BIGINT">BIGINT</option>

                                <option value="FLOAT">FLOAT</option>
                                <option value="DOUBLE">DOUBLE</option>
                                <option value="DECIMAL">DECIMAL</option>
                                <option value="DATETIME">DATETIME</option>
                                <option value="TIMESTAMP">TIMESTAMP</option>
                                <option value="TIME">TIME</option>

                                <option value="YEAR">YEAR</option>
                                <option value="CHAR">CHAR</option>
                                <option value="TINYBLOB">TINYBLOB</option>
                                <option value="TINYTEXT">TINYTEXT</option>
                                <option value="BLOB">BLOB</option>
                                <option value="MEDIUMBLOB">MEDIUMBLOB</option>

                                <option value="MEDIUMTEXT">MEDIUMTEXT</option>
                                <option value="LONGBLOB">LONGBLOB</option>
                                <option value="LONGTEXT">LONGTEXT</option>
                                <option value="ENUM">ENUM</option>
                                <option value="SET">SET</option>
                                <option value="BOOL">BOOL</option>

                                <option value="BINARY">BINARY</option>
                                <option value="VARBINARY">VARBINARY</option>
                            </select>
                        </td>

                        <td class="cell2" width="50" align="center">
                            <input type="text" class="input_text" value="{$item.length}" name="db_cols[{$key}][length]">
                        </td>

                        <td class="cell1" width="50" align="center">
                            <select name="db_cols[{$key}][extras]" class="input_text">
                                <option value="" selected></option>
                                <option value="AUTO_INCREMENT">{t}AUTO_INCREMENT{/t}</option>
                            </select>
                        </td>

                        <td class="cell2" width="50" align="center">
                            <select name="db_cols[{$key}][keys]" class="input_text">
                                <option value="" selected></option>
                                <option value="PRIMARY KEY">{t}PRIMARY KEY{/t}</option>
                                <option value="INDEX">{t}INDEX{/t}</option>
                                <option value="UNIQUE">{t}UNIQUE{/t}</option>
                            </select>
                        </td>
                        <td class="cell1" align="center">
                            <input type="button" class="ButtonRed" value="{t}Remove{/t}" onclick="javascript:rem_col('{$key}');" />
                        </td>
                    </tr>
                {/foreach}
                {else}
                    <tr id="col1">
                        <td class="cell1" width="50" align="center">
                            <strong>{t}Col{/t} #1:</strong>
                        </td>
                        <td class="cell2" width="50" align="center">
                            <input class="input_text" type="text" name="db_cols[1][name]" value="">
                        </td>
                        <td class="cell1" align="center" width="50">
                            <select name="db_cols[1][type]" class="input_text">
                                <option value="VARCHAR" selected>VARCHAR</option>
                                <option value="TINYINT">TINYINT</option>

                                <option value="TEXT">TEXT</option>
                                <option value="DATE">DATE</option>
                                <option value="SMALLINT">SMALLINT</option>
                                <option value="MEDIUMINT">MEDIUMINT</option>
                                <option value="INT">INT</option>
                                <option value="BIGINT">BIGINT</option>

                                <option value="FLOAT">FLOAT</option>
                                <option value="DOUBLE">DOUBLE</option>
                                <option value="DECIMAL">DECIMAL</option>
                                <option value="DATETIME">DATETIME</option>
                                <option value="TIMESTAMP">TIMESTAMP</option>
                                <option value="TIME">TIME</option>

                                <option value="YEAR">YEAR</option>
                                <option value="CHAR">CHAR</option>
                                <option value="TINYBLOB">TINYBLOB</option>
                                <option value="TINYTEXT">TINYTEXT</option>
                                <option value="BLOB">BLOB</option>
                                <option value="MEDIUMBLOB">MEDIUMBLOB</option>

                                <option value="MEDIUMTEXT">MEDIUMTEXT</option>
                                <option value="LONGBLOB">LONGBLOB</option>
                                <option value="LONGTEXT">LONGTEXT</option>
                                <option value="ENUM">ENUM</option>
                                <option value="SET">SET</option>
                                <option value="BOOL">BOOL</option>

                                <option value="BINARY">BINARY</option>
                                <option value="VARBINARY">VARBINARY</option>
                            </select>
                        </td>

                        <td class="cell2" width="50" align="center">
                            <input type="text" class="input_text" value="" name="db_cols[1][length]">
                        </td>

                        <td class="cell1" width="50" align="center">
                            <select name="db_cols[1][extras]" class="input_text">
                                <option value="" selected></option>
                                <option value="auto_increment">{t}auto_increment{/t}</option>
                            </select>
                        </td>

                        <td class="cell2" width="50" align="center">
                            <select name="db_cols[1][keys]" class="input_text">
                                <option value="" selected></option>
                                <option value="PRIMARY KEY">{t}PRIMARY KEY{/t}</option>
                                <option value="INDEX">{t}INDEX{/t}</option>
                                <option value="UNIQUE">{t}UNIQUE{/t}</option>
                            </select>
                        </td>
                        <td class="cell1" align="center">
                            <input type="button" class="ButtonRed" value="{t}Remove{/t}" onclick="javascript:rem_col('1');" />
                        </td>
                    </tr>
                {/if}
                    <tr id="add_new">

                    </tr>
                </table>
            </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <input type="button" class="ButtonGreen" value="{t}Add a column{/t}" onclick="javascript:add_col();" />
                </td>
            </tr>
        </table>
    </td>
</tr>


<table style="display: none">
    <tr id="add_col_container">
        <td class="cell1" width="50" align="center">
            <strong>{t}Col{/t} #{$key}:</strong>
        </td>
        <td class="cell2" width="50" align="center">
            <input class="input_text" type="text" name="db_cols[{$key}][name]" value="">
        </td>
        <td class="cell1" align="center" width="50">
            <select name="db_cols[{$key}][type]" class="input_text">
                <option value="VARCHAR">VARCHAR</option>
                <option value="TINYINT">TINYINT</option>

                <option value="TEXT">TEXT</option>
                <option value="DATE">DATE</option>
                <option value="SMALLINT">SMALLINT</option>
                <option value="MEDIUMINT">MEDIUMINT</option>
                <option value="INT">INT</option>
                <option value="BIGINT">BIGINT</option>

                <option value="FLOAT">FLOAT</option>
                <option value="DOUBLE">DOUBLE</option>
                <option value="DECIMAL">DECIMAL</option>
                <option value="DATETIME">DATETIME</option>
                <option value="TIMESTAMP">TIMESTAMP</option>
                <option value="TIME">TIME</option>

                <option value="YEAR">YEAR</option>
                <option value="CHAR">CHAR</option>
                <option value="TINYBLOB">TINYBLOB</option>
                <option value="TINYTEXT">TINYTEXT</option>
                <option value="BLOB">BLOB</option>
                <option value="MEDIUMBLOB">MEDIUMBLOB</option>

                <option value="MEDIUMTEXT">MEDIUMTEXT</option>
                <option value="LONGBLOB">LONGBLOB</option>
                <option value="LONGTEXT">LONGTEXT</option>
                <option value="ENUM">ENUM</option>
                <option value="SET">SET</option>
                <option value="BOOL">BOOL</option>

                <option value="BINARY">BINARY</option>
                <option value="VARBINARY">VARBINARY</option>
            </select>
        </td>

        <td class="cell2" width="50" align="center">
            <input type="text" class="input_text" value="" name="db_cols[{$key}][length]">
        </td>

        <td class="cell1" width="50" align="center">
            <select name="db_cols[{$key}][extras]" class="input_text">
                <option value="" selected></option>
                <option value="auto_increment">{t}auto_increment{/t}</option>
            </select>
        </td>

        <td class="cell2" width="50" align="center">
            <select name="db_cols[{$key}][keys]" class="input_text">
                <option value="" selected="selected"></option>
                <option value="PRIMARY KEY">{t}PRIMARY KEY{/t}</option>
                <option value="INDEX">{t}INDEX{/t}</option>
                <option value="UNIQUE">{t}UNIQUE{/t}</option>
            </select>
        </td>
        <td class="cell1" align="center">
            <input type="button" class="ButtonRed" value="{t}Remove{/t}" onclick="javascript:rem_col('{$key}');" />
        </td>
    </tr>
</table>
*}
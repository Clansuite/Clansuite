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

{doc_raw}
{literal}
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
{/literal}
</script>
{/doc_raw}

<table style="display: none">
    <tr id="add_col_container">
        <td class="cell1" width="50" align="center">
            <b>{translate}Col{/translate} #{literal}{$key}{/literal}:</b>
        </td>
        <td class="cell2" width="50" align="center">
            <input class="input_text" type="text" name="db_cols[{literal}{$key}{/literal}][name]" value="">
        </td>
        <td class="cell1" align="center" width="50">
            <select name="db_cols[{literal}{$key}{/literal}][type]">
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
            <input type="text" class="input_text" value="" name="db_cols[{literal}{$key}{/literal}][length]">
        </td>
        
        <td class="cell1" width="50" align="center">
            <select name="db_cols[{literal}{$key}{/literal}][extras]">
                <option value="" selected></option>
                <option value="auto_increment">{translate}auto_increment{/translate}</option>
            </select>
        </td>
        
        <td class="cell2" width="50" align="center">
            <select name="db_cols[{literal}{$key}{/literal}][keys]">
                <option value="" selected></option>
                <option value="PRIMARY KEY">{translate}PRIMARY KEY{/translate}</option>
                <option value="INDEX">{translate}INDEX{/translate}</option>
                <option value="UNIQUE">{translate}UNIQUE{/translate}</option>
            </select>
        </td>
        <td class="cell1" align="center">
            <a class="ButtonGrey" href="javascript:rem_col('{literal}{$key}{/literal}');">{translate}Remove{/translate}</a>
        </td>
    </tr>
</table>

<form action="index.php?mod=admin&sub=modules&action=create_new" method="POST">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    
    <td class="td_header" width="100">
        {translate}Description{/translate}
    </td>
    
    <td class="td_header" width="90%">
    {translate}Needed information{/translate}
    </td>
    
</tr>
<tr>
   
    <td class="cell1">
        {translate}This are the information that are needed to be stored into the Database and for preparing the necessary files.{/translate}
    </td>
    
    <td class="cell2">
        <table border="0" cellpadding="2" cellspacing="2">
            <tr><td><b>{translate}Title:{/translate}</b></td><td><input class="input_text" type="text" name="title" value="{$smarty.post.title|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}Name:{/translate}<br /><div class="font_mini">?mod=name</div></b></td><td><input class="input_text" type="text" name="name" value="{$smarty.post.name|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}Description:{/translate}</b></td><td><input class="input_text" type="text" name="description" value="{$smarty.post.description|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}Author:{/translate}</b></td><td><input class="input_text" type="text" name="author" value="{$smarty.post.author|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}Copyright:{/translate}</b></td><td><input class="input_text" type="text" name="copyright" value="{$smarty.post.copyright|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}Homepage:{/translate}</b></td><td><input class="input_text" type="text" name="homepage" value="{$smarty.post.homepage|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}License:{/translate}</b></td><td><input class="input_text" type="text" name="license" value="{$smarty.post.license|escape:"htmlall"}"></td></tr>
            <tr><td><b>{translate}Enabled:{/translate}</b></td><td><input type="checkbox" name="enabled" value="1"></td></tr>
            <tr><td><b>{translate}Core module:{/translate}</b></td><td><input type="checkbox" name="core" value="1"></td></tr>
        </table>
    </td>
</tr>
<tr>
    <td class="cell1">
        {translate}This optional information inserts a table and rows into the Database.{/translate}
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
                        <b>{translate}Name:{/translate}</b>
                    </td>
                    <td class="cell2" colspan="6">
                        <b>{$db_prefix}</b><input class="input_text" type="text" name="db_table" value="{$smarty.post.db_table|escape:"htmlall"}">
                    </td>
                </tr>                
                <tr>
                    <td class="td_header_small" align="center">Nr.</td>
                    <td class="td_header_small" align="center">{translate}Name{/translate}</td>
                    <td class="td_header_small" align="center">{translate}Type{/translate}</td>
                    <td class="td_header_small" align="center">{translate}Length{/translate}</td>
                    <td class="td_header_small" align="center">{translate}Extras{/translate}</td>
                    <td class="td_header_small" align="center">{translate}Keys{/translate}</td>
                    <td class="td_header_small" align="center">{translate}Options{/translate}</td>
                </tr>
                {if is_array($smarty.post.db_cols) }
                {foreach key=key item=item from=$smarty.post.db_cols}
                    <tr id="col{$key}">
                        <td class="cell1" width="50" align="center">
                            <b>{translate}Col{/translate} #{$key}:</b>
                        </td>
                        <td class="cell2" width="50" align="center">
                            <input class="input_text" type="text" name="db_cols[{$key}][name]" value="{$item.name}">
                        </td>
                        <td class="cell1" align="center" width="50">
                            <select name="db_cols[{$key}][type]">
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
                            <select name="db_cols[{$key}][extras]">
                                <option value="" selected></option>
                                <option value="AUTO_INCREMENT">{translate}AUTO_INCREMENT{/translate}</option>
                            </select>
                        </td>
                        
                        <td class="cell2" width="50" align="center">
                            <select name="db_cols[{$key}][keys]">
                                <option value="" selected></option>
                                <option value="PRIMARY KEY">{translate}PRIMARY KEY{/translate}</option>
                                <option value="INDEX">{translate}INDEX{/translate}</option>
                                <option value="UNIQUE">{translate}UNIQUE{/translate}</option>
                            </select>
                        </td>
                        <td class="cell1" align="center">
                            <a class="ButtonGrey" href="javascript:rem_col('{$key}');">{translate}Remove{/translate}</a>
                        </td>
                    </tr>
                {/foreach}
                {else}
                    <tr id="col1">
                        <td class="cell1" width="50" align="center">
                            <b>{translate}Col{/translate} #1:</b>
                        </td>
                        <td class="cell2" width="50" align="center">
                            <input class="input_text" type="text" name="db_cols[1][name]" value="">
                        </td>
                        <td class="cell1" align="center" width="50">
                            <select name="db_cols[1][type]">
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
                            <select name="db_cols[1][extras]">
                                <option value="" selected></option>
                                <option value="auto_increment">{translate}auto_increment{/translate}</option>
                            </select>
                        </td>
                        
                        <td class="cell2" width="50" align="center">
                            <select name="db_cols[1][keys]">
                                <option value="" selected></option>
                                <option value="PRIMARY KEY">{translate}PRIMARY KEY{/translate}</option>
                                <option value="INDEX">{translate}INDEX{/translate}</option>
                                <option value="UNIQUE">{translate}UNIQUE{/translate}</option>
                            </select>
                        </td>
                        <td class="cell1" align="center">
                            <a class="ButtonGrey" href="javascript:rem_col('{$key}');">{translate}Remove{/translate}</a>
                        </td>
                    </tr>
                {/if}
                    <tr id="add_new">
                    
                    </tr>                
                </table>
            </td>
            </tr>
            <tr>
                <td colspan="2" align="right"><a class="ButtonGrey" href="javascript:add_col();">
                    {translate}Add a column{/translate}</a>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
<p align="center">
    <input class="ButtonGrey" type="submit" value="{translate}Create the new module{/translate}" name="submit">
</p>
</form>
<a href="javascript:clip('proc_infos')">Document Processing Info</a>
<span id="proc_infos" style="display: none;">
{t}Document:{/t} 
[ {* {"end"|timemarker:"Exectime:"} | {"end"|timemarker:"Rendertime:"} *} |Gzip: {gzipcheck} ] <br />
Db: 
[ {t}Queries:{/t} {$db_counter} ExecTime: {* {$db_exectime} *} ] <br />
Server: 
[ {t}Memory: {/t} {memusage} | {t}Serverload:{/t} {serverload} ]
</span>
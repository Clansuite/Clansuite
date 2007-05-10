<a href="javascript:clip('proc_infos')">Document Processing Info</a>
<span id="proc_infos" style="display: none;">
{translate}Document:{/translate} 
[ {"end"|timemarker:"Exectime:"} | {"end"|timemarker:"Rendertime:"} |Gzip: {gzipcheck} ] <br />
Db: 
[ {translate}Queries:{/translate} {$db_counter} ExecTime: {$db_exectime} ] <br />
Server: 
[ {translate}Memory: {/translate} {memusage} | {translate}Serverload:{/translate} {serverload} ]
</span>
<a href="javascript:clip('proc_infos')">Document Processing Info</a>
<span id="proc_infos" style="display: none;">
<strong>Document</strong> [ Time: {* {"end"|timemarker:"Exectime:"} | {"end"|timemarker:"Rendertime:"} *} | Gzip: {gzipcheck} ]
<br />
<strong>Database</strong> [ Time: {* {$db_exectime} *} | Queries: {$db_counter} ]
<br />
<strong>Serverload</strong> {serverload}
<br />
<strong>Memory</strong> [ {memusage} ]
</span>
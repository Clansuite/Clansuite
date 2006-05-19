
<!-- start footer.tpl //-->

</div><!-- close div id="main" //-->

{include file="right.tpl"}

<div id="contentfooter">
	 {literal}
	 <script type="text/javascript">
	 <!--
	 function highlight(){
		 var t=document.getElementsByTagName('div');
		 
		 for(i=0;i<t.length;i++){	
		 
		 	void(t[i].style.padding='5px;');
		 	b=t[i].id;
		 	h=t[i].innerHTML;void(t[i].innerHTML='<p style=\'color:red;font-weight:bold;\'>'+b+'<\/p>'+h);
		 	void(t[i].style.border='2px solid red');}
	  } 
	 //-->
	</script>
	{/literal}
	<div id="left">
		<script type="text/javascript" src="/javascript/divdebugger.js"></script>
		<a href='http://clansuite.knd-squad.de/'>Clansuite - codename: [ snakeeye ]</a> 
		&copy;1999-2006 <a title='License Agreement' href='http://www.gnu.org/licenses/gpl.html'>GPL</a> | 
		Version: <a title='Check for update' href='?command=checkVersion'>{$clansuite_version}</a>
    	<p>
    	<a onclick="highlight()" href="javascript:void(0)">ContentBoxDebug</a>
    	</p>
	</div>
    
    <div id="right">
    	<br />Db-Queries: {php} global $Db; echo $Db->query_counter; {/php} | Runtime: {php}
    	$clansuite_endtime = (float) array_sum(explode(' ', microtime()));
		$clansuite_runtime = round($clansuite_endtime - $_CONFIG['starttime'],4);
		echo $clansuite_runtime; {/php} sec's
		{* entfernt: <br /><b>Letzte Änderung am xy</b>
        <br /><b>Seite kompiliert in xy Sekunden</b>
        <br /><b>Seite generiert in xy Sekunden</b>
    	<br /> *}
		
		
		{php}  global $User, $user;
			   #echo "ses: <pre>"; var_dump($_SESSION); echo "</pre>";
			   #echo "</pre><br />Session -> init successfull !<br />"; 
			   //}
			   //echo "CONFIG: <pre>"; var_dump($_CONFIG); echo "</pre>";
			   #echo "COOKIES <pre>"; var_dump($_COOKIE); echo "</pre>";
			   #echo "USER <pre>"; var_dump($User); echo "</pre>";
			   #echo "user <pre>"; var_dump($user); echo "</pre>";
		{/php}
    </div>

</div><!-- close div contentfooter //-->
</div><!-- close div main-frame //-->
</body>
</html>
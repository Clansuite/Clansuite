<!-- Fusszeile -->
    	    	    	
 <div id="contentfooter">
	
	 <script type="text/javascript">
	 <!--
	 function highlight(){
		 var t=document.getElementsByTagName('div');
		 
		 for(i=0;i<t.length;i++){	
		 
		 	void(t[i].style.padding='5px;');
		 	b=t[i].id;
		 	h=t[i].innerHTML;void(t[i].innerHTML='<p style=\'color:red;font-weight:bold;\'>'+b+'</p>'+h);
		 	void(t[i].style.border='2px solid red');}
	  } 
	 //-->
	</script>

 
	 <a onclick="highlight()" href="javascript:void(0)">ContentBoxDebug</a>
	 <script type="text/javascript" src="/javascript/divdebugger.js"></script>
	 
	 
 <br /> <?php echo "queries: ", $Db->query_counter; ?>
 Db-Queries: {query_counter} | Runtime: {clansuite_runtime} sec's
  </div>    


</body>
</html>
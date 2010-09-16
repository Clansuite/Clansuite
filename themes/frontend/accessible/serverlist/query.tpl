 <h1>Query a game server with gsQuery</h1>
    <div style="text-align: left">
        <p>Enter the hostname, port and protocol of your game server: </p>
	<form action="example_usage.php" method="get">
	  <div class="formrow"><span class="label">Host: </span><span class="input"><input name="host" type="text"/></span></div>
	  <div class="formrow"><span class="label">Port: </span><span class="input"><input name="queryport" type="text"/></span></div>
	  <div class="formrow"><span class="label">Protocol: </span><span class="input"><input name="protocol" type="text"/></span></div>
	  <div><span style="margin-left: 80px;"><input type="submit" value="Submit"/></span></div>
	</form>

          <p>If UDP traffic isn't allowed from this host gsQuery can fetch a serialized object via HTTP: </p>

          <form action="example_usage.php" method="get">
            <div>URL: <input name="url" value="http://" type="text"/><input type="submit" value="Submit"/></div>
          </form>
    </div>
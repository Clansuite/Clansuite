{* DEBUG {$serverdata|@var_dump} *}


<table style="font-size: 11px;" cellpadding=0 cellspacing=0>
    <tr><td colspan="2">    
    Serverdetails for {$serverdata.gamename} Engine based Server <br />  Time: {$serverdata.time}</td></tr>
                    
    <tr>
    <td valign=top>
								
	    <table style="font-size: 12px; font : small/1.2 "Tahoma", "Bitstream Vera Sans";"
	           cellspacing=0 cellpadding=3 align="center">
    		<tr>
    	    <td style="padding-left: 10px; padding-right: 10px;" valign=top>

				<table style="font : small/1.2 "Tahoma", "Bitstream Vera Sans";" cellspacing=0 cellpadding=0>
                    
                    <tr><td>    Server name     </td><td>   {$serverdata.hostname}             </td></tr>
                    <tr><td>    Server Address  </td><td>   {$serverdata.gameip}           </td></tr>
                    <tr><td>    Server Version  </td><td>   {$serverdata.gameip}           </td></tr>
                    
                    <tr><td>    Ping            </td><td>   {$serverdata.response}         </td></tr>
                
                    <tr><td>    Players         </td><td>   {$serverdata.nowplayers}/{$serverdata.maxplayers}   </td></tr>
                    <tr><td>    VAC             </td><td>   {if $serverdata.secure == '1'}ON{else}OFF{/if}      </td></tr>
                
                </table>
				
			<br />
				
				<table style="font : small/1.2 "Tahoma", "Bitstream Vera Sans";"
				       cellspacing=0 cellpadding=0>
					<tr><td>    Password        </td>
						<td>   This server is open to the public (No password) - {$serverdata.password}</td>						
				    </tr>
				</table>
            </td>
    		<td width="20%" valign="top" style="padding-left: 10px; padding-right: 10px;">
    			
    			{if $serverdata.map == 'unknown_map.gif' }
    			
    			<img src='{$www_core_tpl_root}/images/serverlist/maps/{$serverdata.map}';
    			alt="No Picture for {$serverdata.mapname}" style="border: 1px solid #000000;">
    			
    			{else}
    			test2
    			<img src="{$www_core_tpl_root}/images/serverlist/{$serverdata.map_path}/{$serverdata.mapname}.jpg" 
    			alt="Picture for {$serverdata.mapname}" style="border: 1px solid #000000;">
    			
    			{/if}
    			
    			<br />
    			<br />
    			
    			<table cellspacing=1 cellpadding=1>
        			<tr><td><font class="color">Current Map: </td><td>{$serverdata.mapname}</td></tr>
        			<tr><td><font class="color">Game Type: </td><td>DEATHMATCH</td></tr>
                </table>
	    </td>
		</tr>
		</table>
		
		<br />
		
		{$serverdata.rules.map_path}
        
        
        <table style="font : small/1.2 "Tahoma", "Bitstream Vera Sans";" width="50%" cellspacing=1 cellpadding=1>
        <tr><td colspan=3">Player Information</td></tr>
		
		{$serverdata.playerlist}
		
		</table>
		
</table>
</span>
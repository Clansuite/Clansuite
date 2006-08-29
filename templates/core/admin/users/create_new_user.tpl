<form id="h3sForm"
             action="index.php?mod=admin&sub=users&action=add" method="POST" target="_self">
        
            <fieldset> 
                          
               <h3> Create new User </h3>
        	    
        	    <label for="firstname">
        			First Name
        			<input id="firstname" name="firstname" type="text" value="first name" />
        		</label>
        	    
               <label for="usernick">
        			Nickname
        			<input id="usernick" name="usernick" type="text" value="nickname" />
        		</label>
        		
        		<label for="lastname">
        			Nickname
        			<input id="lastname" name="lastname" type="text" value="last name" />
        		</label>	
        		
        		<label for="email">
        			Email
        			<input id="email" name="email" type="text" value="email" />
        		</label>
        		
        		<label for="password">
        			Email
        			<input id="password" name="password" type="text" value="password" />
        		</label>
        		
        		<label for="user_picture">
                    Icon
        			{* <input id="icon" name="icon" type="text" value="iconname?" /> *}
        				<input type="text" id="icon" class="selectFile" name="icon" />
                        <input type="button" name="select" onclick="ImageSelector.select('icon');" />
        		</label>
        		
        		<input class="Button" type="submit" name="submit" value="{translate}Create User{/translate}" />
        			
        	 </fieldset>
        
        </form>
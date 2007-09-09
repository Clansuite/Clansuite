    <div id="sidebar">
        <div id="stepbar">
            <?=$language['MENU_HEADING']?>
            <div class="step-pass"><?=$language['MENUSTEP1']?> </div>
            <div class="step-pass"><?=$language['MENUSTEP2']?></div>
            <div class="step-pass"><?=$language['MENUSTEP3']?></div>
            <div class="step-pass"><?=$language['MENUSTEP4']?></div>
            <div class="step-on"><?=$language['MENUSTEP5']?></div>
            <div class="step-off"><?=$language['MENUSTEP6']?></div>
            <div class="step-off"><?=$language['MENUSTEP7']?></div>
        </div>
    </div>

    <div id="content" class="narrowcolumn">

        <div id="content_footer">

            <div class="accordion">
        	   <h2 class="headerstyle">
        	       <img src="images/64px-Preferences-system.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
        	       <?=$language['STEP5_CONFIG']?>
        	   </h2>

        	   <p><?=$language['STEP5_SENTENCE1']?></p>
        	   
        	   <form action="index.php" method="post">
        	   <dl>
			   		<dt><?=$language['STEP5_CONFIG_SITENAME']?></dt>
			   		<dd><input type="name" name="site_name" value="<?=$values['site_name']?>" /></dd>
			   
			   		<dt><?=$language['STEP5_CONFIG_SYSTEMEMAIL']?></dt>
			   		<dd><input type="name" name="system_email" value="<?=$values['system_email']?>" /></dd>
			   		
			   		<? # festes dropdown ?>
			   		<dt><?=$language['STEP5_CONFIG_USERACCOUNT_ENCRYPTION']?></dt>
			   		<dd><input type="name" name="user_account_enc" value="<?=$values['user_account_enc']?>" /></dd>
			   		
			   		<? # random salting ?>
			   		<dt><?=$language['STEP5_CONFIG_SALTING']?></dt>
			   		<dd><input type="name" name="salting" value="<?=$values['salting']?>" /></dd>
			   		
			   		<? # timezone detection fucntion ?>
			   		<dt><?=$language['STEP5_CONFIG_TIMEZONE']?></dt>
			   		<dd><input type="name" name="time_zone" value="<?=$values['time_zone']?>" /></dd>
			   
			   </dl>

            <div class="navigation">

                        <span style="font-size:10px;">
                        <?=$language['CLICK_NEXT_TO_PROCEED']?>
                        <br />
                        <?=$language['CLICK_BACK_TO_RETURN']?>
                        </span>

						<form action="index.php" method="post">
	            			<div class="alignright">
	                            <input type="submit" value="<?=$language['NEXTSTEP']?>" class="button" name="step_forward" />
	            			</div>
	            			
							<div class="alignleft">
	                            <input type="submit" value="<?=$language['BACKSTEP']?>" class="button" name="step_backward" />
	                            <input type="hidden" name="lang" value="<?=$_SESSION['lang']?>" />
	                        </div>	
						</form>
            </div><!-- div navigation end -->

        	</div> <!-- div accordion end -->

        </div> <!-- div content_footer end -->

    </div> <!-- div content end -->
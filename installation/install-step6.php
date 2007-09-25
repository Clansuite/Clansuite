    <div id="sidebar">
        <div id="stepbar">
            <?=$language['MENU_HEADING']?>
            <div class="step-pass"><?=$language['MENUSTEP1']?> </div>
            <div class="step-pass"><?=$language['MENUSTEP2']?></div>
            <div class="step-pass"><?=$language['MENUSTEP3']?></div>
            <div class="step-pass"><?=$language['MENUSTEP4']?></div>
            <div class="step-pass"><?=$language['MENUSTEP5']?></div>
            <div class="step-on"><?=$language['MENUSTEP6']?></div>
            <div class="step-off"><?=$language['MENUSTEP7']?></div>
        </div>
    </div>

    <div id="content" class="narrowcolumn">

        <div id="content_footer">

            <div class="accordion">
        	   <h2 class="headerstyle">
        	       <img src="images/64px-Contact-new.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
        	       <?=$language['STEP6_ADMINUSER']?>
        	   </h2>

        	   <p><?=$language['STEP6_SENTENCE1']?></p>
				
				<form action="index.php" method="post">
				<dl>
					<dt><?=$language['STEP6_ADMIN_NAME']?></dt>
			   		<dd><input type="name" name="admin_name" value="<?=$values['admin_name']?>" /></dd>
			   		<dt><?=$language['STEP6_ADMIN_PASSWORD']?></dt>
			   		<dd><input type="name" name="admin_password" value="<?=$values['admin_password']?>" /></dd>
			   		<dt><?=$language['STEP6_ADMIN_EMAIL']?></dt>
			   		<dd><input type="name" name="admin_email" value="<?=$values['admin_email']?>" /></dd>
			   		<dt><?=$language['STEP6_ADMIN_LANGUAGE']?></dt>
			   		<dd><input type="name" name="admin_language" value="<?=$values['admin_language']?>" /></dd>
			   	</dl>


            <div class="navigation">

                        <span style="font-size:10px;">
                        <?=$language['CLICK_NEXT_TO_PROCEED']?>
                        <br />
                        <?=$language['CLICK_BACK_TO_RETURN']?>
                        </span>

							<div class="alignright">
	                            <input type="submit" value="<?=$language['NEXTSTEP']?>" class="ButtonGreen" name="step_forward" />
	            			</div>
	            			
	            			<div class="alignleft">
	                            <input type="submit" value="<?=$language['BACKSTEP']?>" class="ButtonRed" name="step_backward" />
	                            <input type="hidden" name="lang" value="<?=$_SESSION['lang']?>" />
	                        </div>
							</form>
                 </div><!-- div navigation end -->

        	</div> <!-- div accordion end -->

        </div> <!-- div content_footer end -->

    </div> <!-- div content end -->
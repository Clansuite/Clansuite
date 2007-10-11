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

        <div id="content_middle">

            <div class="accordion">
        	   <h2 class="headerstyle">
        	       <img src="images/64px-Preferences-system.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
        	       <?=$language['STEP5_CONFIG']?>
        	   </h2>

        	   <p><?=$language['STEP5_SENTENCE1']?></p>

        	   <form action="index.php" method="post">
        	   <dl>
			   		<dt><?=$language['STEP5_CONFIG_SITENAME']?></dt>
			   		<dd><input type="text" name="site_name" value="<?=$values['site_name']?>" /></dd>

			   		<dt><?=$language['STEP5_CONFIG_SYSTEMEMAIL']?></dt>
			   		<dd><input type="text" name="system_email" value="<?=$values['system_email']?>" /></dd>

			   		<? # festes dropdown ?>
			   		<dt><?=$language['STEP5_CONFIG_USERACCOUNT_ENCRYPTION']?></dt>
			   		<dd>
						<select name="user_account_enc">
							<option value="md5"<?php echo ($values['user_account_enc']=='md5') ? ' selected="selected"' : ''; ?>>MD5</option>
							<option value="sha1"<?php echo ($values['user_account_enc']=='sha1') ? ' selected="selected"' : ''; ?>>SHA1</option>
						</select>
					</dd>

			   		<? # random salting ?>
			   		<dt><?=$language['STEP5_CONFIG_SALTING']?></dt>
			   		<dd><input type="text" name="salt" value="<?=$values['salt']?>" /></dd>

			   		<? # timezone detection fucntion ?>
			   		<dt><?=$language['STEP5_CONFIG_TIMEZONE']?></dt>
			   		<dd>
						<select name="time_zone" class="form">
							<option value="-36000">UTC -10</option>
							<option value="-32400">UTC -9</option>
							<option value="-28800">UTC -8</option>
							<option value="-25200">UTC -7</option>
							<option value="-21600">UTC -6</option>
							<option value="-18000">UTC -5</option>
							<option value="-14400">UTC -4</option>
							<option value="-10800">UTC -3</option>
							<option value="-7200">UTC -2</option>
							<option value="-3600">UTC -1</option>
							<option value="0">UTC +0</option>
							<option value="3600" selected="selected">UTC +1</option>
							<option value="7200">UTC +2</option>
							<option value="10800">UTC +3</option>
							<option value="14400">UTC +4</option>
							<option value="18000">UTC +5</option>
							<option value="21600">UTC +6</option>
							<option value="25200">UTC +7</option>
							<option value="28800">UTC +8</option>
							<option value="32400">UTC +9</option>
							<option value="36000">UTC +10</option>
							<option value="39600">UTC +11</option>
							<option value="43200">UTC +12</option>
						</select>
					</dd>

			   </dl>

            <div id="content_footer">
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
			</div> <!-- div content_footer end -->
             
        	</div> <!-- div accordion end -->

        </div> <!-- div content_middle end -->

    </div> <!-- div content end -->
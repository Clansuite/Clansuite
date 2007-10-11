    <div id="sidebar">
        <div id="stepbar">
            <?=$language['MENU_HEADING']?>
            <div class="step-pass"><?=$language['MENUSTEP1']?> </div>
            <div class="step-pass"><?=$language['MENUSTEP2']?></div>
            <div class="step-pass"><?=$language['MENUSTEP3']?></div>
            <div class="step-on"><?=$language['MENUSTEP4']?></div>
            <div class="step-off"><?=$language['MENUSTEP5']?></div>
            <div class="step-off"><?=$language['MENUSTEP6']?></div>
            <div class="step-off"><?=$language['MENUSTEP7']?></div>
        </div>
    </div>

    <div id="content" class="narrowcolumn">

        <div id="content_middle">

            <div class="accordion">
        	   <h2 class="headerstyle">
        	       <img src="images/64px-Document-save.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
        	       <?=$language['STEP4_DATABASE']?>
        	   </h2>

        	<?php
			if ($error != '' )
			{ ?>
				<fieldset class="error_red"">
            	<legend>Error</legend><strong><?=$error ?></strong>
            	</fieldset>
			<?php
			} ?>

			<p><?=$language['STEP4_SENTENCE1']; ?></p>

			<form action="index.php" method="post">
        	   <dl>
			   		<dd><input type="hidden" name="db_type" value="mysql" /></dd>
			   		<dt><?=$language['DB_NAME']?></dt>
			   		<dd><input type="text" name="db_name" value="<?=$values['db_name']?>" /></dd>
			   		<dt><?=$language['DB_HOST']?></dt>
			   		<dd><input type="text" name="db_host" value="<?=$values['db_host']?>" /></dd>
			   		<dt><?=$language['DB_USER']?></dt>
			   		<dd><input type="text" name="db_user" value="<?=$values['db_username']?>" /></dd>
			   		<dt><?=$language['DB_PASS']?></dt>
			   		<dd><input type="text" name="db_pass" value="<?=$values['db_password']?>" /></dd>
			   		<dt><?=$language['DB_PREFIX']?></dt>
			   		<dd><input type="text" name="db_prefix" value="<?=$values['db_prefix']?>" /></dd>
			   </dl>

			<p><?=$language['STEP4_SENTENCE2']; ?></p>

			<p><?=$language['STEP4_SENTENCE3']; ?></p>

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
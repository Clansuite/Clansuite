    <div id="sidebar">
        <div id="stepbar">
            <p><?=$language['MENU_HEADING']?></p>
            <div class="step-pass"><?=$language['MENUSTEP1']?> </div>
            <div class="step-pass"><?=$language['MENUSTEP2']?></div>
            <div class="step-pass"><?=$language['MENUSTEP3']?></div>
            <div class="step-pass"><?=$language['MENUSTEP4']?></div>
            <div class="step-pass"><?=$language['MENUSTEP5']?></div>
            <div class="step-pass"><?=$language['MENUSTEP6']?></div>
            <div class="step-on"><?=$language['MENUSTEP7']?></div>
         </div>
    </div>

    <div id="content" class="narrowcolumn">

         <div id="content_middle">

            <div class="accordion">
        	   <h2 class="headerstyle">
        	       <img src="images/64px-Face-smile-big.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
        	       <?=$language['STEP7_FINISH']?>
        	   </h2>
        	   <p style="color:darkgreen"><strong><?=$language['STEP7_SENTENCE1']?></strong></p>
        	    <p><?=$language['STEP7_SENTENCE2']?>
        	      <img src="images/face-wink.png" border="0" style="vertical-align:middle;" alt="installstep image" />
        	       <br /><?=$language['STEP7_SENTENCE3']?>
        	       <p>
                	    <b>Visit the </b><a href="../index.php">Frontend</a>
                	    or 
                	    <a href="../index.php?mod=admin">Admin Control Panel (ACP)</a>
                	    <br/>
                	    <br/>
                	    Ihre Logindaten: Name / Password
                	    </p>
        	       <br /> <?=$language['STEP7_SENTENCE4']?>
        	       <br />
        	       Klick here to delete it now.
        	    </p>        	   
        	   <br />

            <div id="content_footer">
            <div class="navigation">


                        <span style="font-size:10px;">
                        <?=$language['CLICK_BACK_TO_RETURN']?>
                        </span>

						<form action="index.php" method="post">
	            			<div class="alignleft">
	                            <input type="submit" value="<?=$language['BACKSTEP']?>" class="ButtonRed" name="step_backward" />
	                            <input type="hidden" name="lang" value="<?=$_SESSION['lang']?>" />
	                        </div>

						</form>
                        <!--
            			<div class="alignright">
            			 <form action="index.php" name="lang" method="post">
                            <input type="submit" value="<?=$language['NEXTSTEP']?>" class="ButtonGreen" name="step_forward"/>
                            <input type="hidden" name="lang" value="<?=$_SESSION['lang']?>">                           
            			 </form>
            			</div>-->                        
                    </div><!-- div navigation end -->
			</div> <!-- div content_footer end -->
             
        	</div> <!-- div accordion end -->

        </div> <!-- div content_middle end -->

    </div> <!-- div content end -->
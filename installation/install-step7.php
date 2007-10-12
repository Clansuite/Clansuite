    <div id="sidebar">
        <div id="stepbar">
            <?=$language['MENU_HEADING']?>
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
        	   <p style="color:darkgreen"><strong>Geschafft! Sie haben Clansuite erfolgreich installiert.</strong></p>
        	    <p>Das Entwicklerteam w&#252;nscht Ihnen nun viel Freude beim Entdecken und Nutzen von Clansuite.
        	      <img src="images/face-wink.png" border="0" style="vertical-align:middle;" alt="installstep image" />
        	       <br />Sie finden nachfolgend die Links zur Hauptseite und zum Adminbereich, sowie ihre Logindaten als Administrator.
        	       <br />
        	       <br /> Vergessen Sie Bitte nicht, das Verzeichnis "/Installation" aus SicherheitsgrÅnden umzubenennen bzw. zu lîschen.
        	      </p>

        	   <p>Paths Home/Admin - Links - Useraccount mit Logindata </p>
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
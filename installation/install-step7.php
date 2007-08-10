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
    
        <div id="content_footer">
                    
            <div class="accordion">
        	   <h2 class="headerstyle">
        	       <img src="images/64px-Face-smile-big.svg.png" border="0" style="vertical-align:middle" alt="installstep image" /> 
        	       <?=$language['STEP7_FINISH']?>
        	   </h2>
        			
        	   <p>Paths / Links / Useraccount - Data <img src="images/face-wink.png" border="0" style="vertical-align:middle" alt="installstep image" /></p>

            <div class="navigation">
            			<div class="alignleft">
            			 <form action="index.php" name="lang" method="post">
                            <input type="submit" value="<?=$language['BACKSTEP']?>" class="button" name="Button2"/>
                            <input type="hidden" name="lang" value="<?=$_SESSION['lang']?>">
                            <input type="hidden" name="step" value="<?=$_SESSION['step']-1; ?>">
                         </form>
                        </div>
                        <!--
            			<div class="alignright">
            			 <form action="index.php" name="lang" method="post">
                            <input type="submit" value="<?=$language['NEXTSTEP']?>" class="button" name="Button2"/>
                            <input type="hidden" name="lang" value="<?=$_SESSION['lang']?>">
                            <input type="hidden" name="step" value="<?=$_SESSION['step']+1; ?>"> 
            			 </form>
            			</div>-->
            </div><!-- div navigation end -->
           
        	</div> <!-- div accordion end -->   
    
        </div> <!-- div content_footer end -->

    </div> <!-- div content end -->
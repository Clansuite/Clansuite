    <div id="sidebar">
        <div id="stepbar">
            <?=$language['MENU_HEADING']?>
            <div class="step-pass"><?=$language['MENUSTEP1']?> </div>
            <div class="step-pass"><?=$language['MENUSTEP2']?></div>
            <div class="step-on"><?=$language['MENUSTEP3']?></div>
            <div class="step-off"><?=$language['MENUSTEP4']?></div>
            <div class="step-off"><?=$language['MENUSTEP5']?></div>
            <div class="step-off"><?=$language['MENUSTEP6']?></div>
            <div class="step-off"><?=$language['MENUSTEP7']?></div>
        </div>
    </div>

    <div id="content" class="narrowcolumn">
    
        <div id="content_footer">
                    
            <div class="accordion">
        	   <h2 class="headerstyle">
        	       <img src="images/64px-Application-certificate.svg.png" border="0" style="vertical-align:middle" alt="installstep image" /> 
        	       <?=$language['STEP3_LICENCE']; ?>
        	   </h2>
        		
        	    
                <p><?=$language['STEP3_SENTENCE1']; ?></p>
                
                <!-- IFRAME WITH LICENCE -->
                <iframe scrolling="auto" 
			            frameborder="0" 
			            marginwidth="15"
			          	class="license" 
			            src="languages/<?php echo $_SESSION['lang']; ?>.gpl.html" 
			            /></iframe>
			     
			    <!-- CHECKBOX -> READ LICENCE -->
			    <div class="">
			        <input type="checkbox" class="inputbox" id="agreecheck" name="agreecheck"
				           onClick="if(this.checked==true) { document.ButtonNextForm.ButtonNext.disabled=false; } else { document.ButtonNextForm.ButtonNext.disabled=true;}" />
				    <label for="agreecheck"><?=$language['STEP3_CHECKBOX']?></label>
			    </div>			     
			     
			     <div class="navigation">
			                
			                <span style="font-size:10px;">
                            <?=$language['CLICK_NEXT_TO_PROCEED']?>
                            <br />
                            <?=$language['CLICK_BACK_TO_RETURN']?>
                            </span>
			        
                			<div class="alignleft">
                			 <form action="index.php" name="ButtonPrevForm" method="post">
                                <input type="submit" value="<?=$language['BACKSTEP']?>" class="button" name="ButtonPrev"/>
                                <input type="hidden" name="lang" value="<?=$_SESSION['lang']?>">
                                <input type="hidden" name="step" value="<?=$_SESSION['step']-1; ?>">
                             </form>
                            </div>
                            
                			<div class="alignright">
                			 <form action="index.php" name="ButtonNextForm" method="post">
                                <input type="submit" value="<?=$language['NEXTSTEP']?>" class="button" name="ButtonNext" disabled />
                                <input type="hidden" name="lang" value="<?=$_SESSION['lang']?>">
                                <input type="hidden" name="step" value="<?=$_SESSION['step']+1; ?>"> 
                			 </form>
                			</div>
                </div><!-- div navigation end -->
           
        	</div> <!-- div accordion end -->   
    
        </div> <!-- div content_footer end -->

    </div> <!-- div content end -->     
     
    <div id="sidebar" id="leftsidebar">
        <div id="stepbar">
            Installationsschritte
            <div class="step-pass"> [1] Sprachauswahl</div>
            <div class="step-pass"> [2] Systemcheck</div>
            <div class="step-on">   [3] GNU/GPL Lizenz</div>
            <div class="step-off">  [4] Datenbank</div>
            <div class="step-off">  [5] Konfiguration</div>
            <div class="step-off">  [6] Settings</div>
            <div class="step-off">  [7] Abschluss</div>
        </div>
    </div>

    <div id="content" class="narrowcolumn">
    
        <div id="content_footer">
                    
            <div class="accordion">
        	   <h2 class="headerstyle">
        	       <img src="images/64px-Application-certificate.svg.png" border="0" align="absmiddle"> 
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
				    <label for="agreecheck"><?=$language['STEP3_CHECKBOX'] ?></label>
			    </div>			     
			     
			     <div class="navigation">
                			<div class="alignleft">
                			 <form action="index.php" name="ButtonPrevForm" method="post">
                                <input type="submit" value="<?=$language['BACKSTEP'] ?>" class="button" name="ButtonPrev"/>
                                <input type="hidden" name="lang" value="<?=$_SESSION['lang'] ?>">
                                <input type="hidden" name="step" value="<?=$_SESSION['step']-1; ?>">
                             </form>
                            </div>
                            
                			<div class="alignright">
                			 <form action="index.php" name="ButtonNextForm" method="post">
                                <input type="submit" value="<?=$language['NEXTSTEP'] ?>" class="button" name="ButtonNext" disabled />
                                <input type="hidden" name="lang" value="<?=$_SESSION['lang'] ?>">
                                <input type="hidden" name="step" value="<?=$_SESSION['step']+1; ?>"> 
                			 </form>
                			</div>
                </div><!-- div navigation end -->
           
        	</div> <!-- div accordion end -->   
    
        </div> <!-- div content_footer end -->

    </div> <!-- div content end -->     
     
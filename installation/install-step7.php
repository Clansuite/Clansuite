<?php
/**
 * Security Handler
 */
if (defined('IN_CS') == false){ die( 'Clansuite not loaded. Direct Access forbidden.' );}
?>
    <div id="content" class="narrowcolumn">
         <div id="content_middle">
            <div class="accordion">
               <h2 class="headerstyle">
                   <img src="images/64px-Face-smile-big.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
                   <?php echo $language['STEP7_FINISH']; ?>
               </h2>
               <p style="color:darkgreen"><strong><?php echo $language['STEP7_SENTENCE1']; ?></strong></p>
               <p><?php echo $language['STEP7_SENTENCE2']; ?>
                  <img src="images/face-wink.png" border="0" style="vertical-align:middle;" alt="installstep image" />
                   <br /><br />
                   <?php echo $language['STEP7_SENTENCE3']; ?>
                   <br /><br />
                   <p><b><?php echo $language['STEP7_SENTENCE4']; ?>
                                 <a href="../index.php"><?php echo $language['STEP7_SENTENCE5']; ?></a>
                                 <?php echo $language['STEP7_SENTENCE6']; ?>
                                 <a href="../index.php?mod=controlcenter">Control Center (CC)</a>.                        
                              </b>
                      <br /><br />                      
                      <?php echo $language['STEP7_SENTENCE8']; ?>
                      <a href="http://www.clansuite.com/documentation/user/manual"><?php echo $language['STEP7_SENTENCE9']; ?></a>.
                      <br/>
                   </p>
                   <br />
                   <fieldset style="border-color: red; background:#ffc">
                    <legend>
                    <strong style='border: 1px solid #000000; background: white; -moz-opacity:0.75;
                                   filter:alpha(opacity=75);'>&nbsp;<?php echo $language['STEP7_SENTENCE10']; ?>&nbsp;</strong>
                    </legend>
                    <i><?php echo $language['STEP7_SENTENCE11']; ?></i>
                    <br />
                    <br />
                    <b><a href="?delete_installation">Delete Installation</a></b>
                    </fieldset>
                </p>
               <br />
            <div id="content_footer">
            <div class="navigation">
                <span style="font-size:10px;">
                    <?php echo $language['CLICK_BACK_TO_RETURN']; ?>
                </span>
                <form action="index.php" method="post">
                    <div class="alignleft">
                    <input type="submit" value="<?php echo $language['BACKSTEP']; ?>" class="ButtonRed" name="step_backward" />
                    <input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>" />
                    </div>
                </form>
                <!--
                <div class="alignright">
                 <form action="index.php" name="lang" method="post">
                    <input type="submit" value="<?php echo $language['NEXTSTEP']; ?>" class="ButtonGreen" name="step_forward"/>
                    <input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>">
                 </form>
                </div>-->
            </div><!-- div navigation end -->
            </div> <!-- div content_footer end -->
            </div> <!-- div accordion end -->
        </div> <!-- div content_middle end -->
    </div> <!-- div content end -->
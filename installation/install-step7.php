<?php
/**
 * Security Handler
 */
if (!defined('IN_CS')){ die( 'Clansuite not loaded. Direct Access forbidden.' );}
?>
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
                   <p><b><?=$language['STEP7_SENTENCE4']?>
                        <ul>
                            <li><a href="../index.php"><?=$language['STEP7_SENTENCE5']?></a><br/></li>
                            <?=$language['STEP7_SENTENCE6']?>
                            <li><a href="../index.php?mod=admin">Admin Control Panel (ACP)</a>.<br /></li>
                        </ul>
                      </b>
                      <br />
                      <?=$language['STEP7_SENTENCE8']?><a href="http://www.clansuite.com/documentation/user/manual"><?=$language['STEP7_SENTENCE9']?></a>.
                      <br/>
                   </p>
                   <fieldset style="border-color: red; background:lightsalmon;">
                        <legend>
                        <strong style='border: 1px solid #000000; background: white; -moz-opacity:0.75;
                                       filter:alpha(opacity=75);'>&nbsp;<?=$language['STEP7_SENTENCE10']?>&nbsp;</strong>
                        </legend>
                        <i><?=$language['STEP7_SENTENCE11']?></i>
                        <br /><a href="?delete_installation">Delete Installation</a>
                    </fieldset>
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
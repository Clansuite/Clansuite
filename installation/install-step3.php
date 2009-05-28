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
                    <img src="images/64px-Application-certificate.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
                    <?=$language['STEP3_LICENCE']; ?>
                </h2>
                <p><?=$language['STEP3_SENTENCE1']; ?></p>
                <p><?=$language['STEP3_REVIEW_THIRDPARTY']; ?></p>
                <p><?=$language['STEP3_REVIEW_CLANSUITE']; ?></p>
                <!-- IFRAME WITH LICENCE -->
                <iframe scrolling="auto" frameborder="0" marginwidth="15" class="license" src="languages/<?php echo $_SESSION['lang']; ?>.gpl.html"></iframe>
                <!-- CHECKBOX -> READ LICENCE -->
                <div class="">
                    <p><?=$language['STEP3_MUST_AGREE']; ?></p>
                    <label for="agreecheck">
                        <input type="checkbox" class="inputbox" id="agreecheck" name="agreecheck"
                           onclick="if(this.checked==true) { document.getElementById('ButtonNext').disabled=false; } else { document.getElementById('ButtonNext').disabled=true;}" />
                        <?=$language['STEP3_CHECKBOX']?>
                    </label>
                </div>
                <div id="content_footer">
                    <div class="navigation">
                        <span style="font-size:10px;">
                            <?=$language['CLICK_NEXT_TO_PROCEED']?><br />
                            <?=$language['CLICK_BACK_TO_RETURN']?>
                        </span>
                        <form action="index.php" method="post">
                            <div class="alignleft">
                                <input type="submit" value="<?=$language['BACKSTEP']?>" class="ButtonRed" name="step_backward" />
                                <input type="hidden" name="lang" value="<?=$_SESSION['lang']?>" />
                            </div>
                            <div class="alignright">
                                <input type="submit" value="<?=$language['NEXTSTEP']?>" class="ButtonGreen" name="step_forward" id="ButtonNext" disabled="disabled" />
                            </div>
                        </form>
                    </div><!-- div navigation end -->
                </div> <!-- div content_footer end -->
            </div> <!-- div accordion end -->
        </div> <!-- div content_middle end -->
    </div> <!-- div content end -->
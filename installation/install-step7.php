<?php
# Security Handler
if (defined('IN_CS') === false)
{
    die( 'Clansuite not loaded. Direct Access forbidden.' );
}
?>
    <div id="content" class="narrowcolumn">
        <div id="content_middle">
            <div class="accordion">
                <h2 class="headerstyle">
                    <img src="images/64px-Face-smile-big.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP7_FINISH']; ?>
                </h2>
                <?php if (!empty($error)) { ?>
                <div class="error_red">
                    <strong><?php echo $error ?></strong>
                </div>
                <?php } ?>
                <p style="color:darkgreen"><strong><?php echo $language['STEP7_SENTENCE1']; ?></strong></p>
                <p><?php echo $language['STEP7_SENTENCE2']; ?>
                    <img src="images/face-wink.png" border="0" style="vertical-align:middle;" alt="installstep image" />
                    <br />
                    <?php echo $language['STEP7_SENTENCE3']; ?>
                <p><b><?php echo $language['STEP7_SENTENCE4']; ?>
                        <a href="../index.php"><?php echo $language['STEP7_SENTENCE5']; ?></a>
                        <?php echo $language['STEP7_SENTENCE6']; ?>
                        <a href="../index.php?mod=controlcenter">Control Center (CC)</a>.
                    </b>
                    <br/><br/>
                    <?php echo $language['STEP7_SENTENCE8']; ?>
                    <a href="http://docs.clansuite.com/user/manual"><?php echo $language['STEP7_SENTENCE9']; ?></a>.
                    <br/>
                </p>
                <br />
                <fieldset class="error_red">
                    <legend style="border: 1px solid rgba(0, 0, 0, 0.3); border-radius: 6px; background-color: #fff;">
                        <strong>&nbsp;<?php echo $language['STEP7_SENTENCE10']; ?>&nbsp;</strong>
                    </legend>
                    <?php echo $language['STEP7_SENTENCE11']; ?>
                    <br />
                    <br />
                    <b><a href="?delete_installation"><?php echo $language['STEP7_SENTENCE12']; ?></a></b>
                </fieldset>
                <!-- @todo http://trac.clansuite.com/ticket/7
                <br />
                <fieldset class="error_red">
                    <legend style="border: 1px solid rgba(0, 0, 0, 0.3); border-radius: 6px; background-color: #fff;">
                        <strong>&nbsp;<?php echo $language['STEP7_SUPPORT_ENTRY_LEGEND']; ?>&nbsp;</strong>
                    </legend>
                    <?php echo $language['STEP7_SUPPORT_ENTRY_1']; ?>
                    <?php echo $language['STEP7_SUPPORT_ENTRY_2']; ?>
                    <?php echo $language['STEP7_SUPPORT_ENTRY_3']; ?>
                    <?php echo $language['STEP7_SUPPORT_ENTRY_4']; ?>
                </fieldset>
                -->
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
                    </div>
                </div>
            </div>
        </div>
    </div>
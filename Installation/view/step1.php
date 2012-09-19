    <div id="content" class="narrowcolumn">
        <div id="content_middle">
            <div class="accordion">
                <h2 class="headerstyle">
                    <img src="assets/images/64px-Tango_Globe_of_Letters.svg.png" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP1_LANGUAGE_SELECTION']; ?>
                </h2>
                <p><strong><?php echo $language['STEP1_WELCOME']; ?></strong></p>
                <p><?php echo $language['STEP1_THANKS_CHOOSING']; ?></p>
                <p><?php echo $language['STEP1_APPINSTALL_STEPWISE']; ?></p>
                <p><?php echo $language['STEP1_CHOOSELANGUAGE']; ?></p>
                <form action="index.php" name="lang" method="post">
                    <p>
                      <?php \Clansuite\Installation\Application\Viewhelper::renderLanguageDropdown($language); ?>
                    </p>
                    <div id="content_footer">
                        <div class="navigation">
                            <span class="font-10"><?php echo $language['CLICK_NEXT_TO_PROCEED']; ?></span>
                            <div class="alignright">
                                <input type="submit" value="<?php echo $language['NEXTSTEP']; ?>" class="ButtonGreen" name="step_forward" />
                                <input type="hidden" name="submitted_step" value="1" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
     </div>

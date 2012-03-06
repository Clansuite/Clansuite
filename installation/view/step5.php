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
                    <img src="images/64px-Preferences-system.svg.png" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP5_CONFIG']; ?>
                </h2>
                <?php if (!empty($error)) { ?>
                <div class="error_red">
                    <strong><?php echo $error ?></strong>
                </div>
                <?php } ?>
                <p><?php echo $language['STEP5_SENTENCE1']; ?></p>
                <p><?php echo $language['STEP5_SENTENCE2']; ?></p>
                <form action="index.php" method="post">
                <fieldset>
                    <legend><?php echo $language['STEP5_LEGEND']; ?></legend>
                    <ol class="formular">
                        <li>
                            <label class="formularleft" for="page_title"><?php echo $language['STEP5_CONFIG_SITENAME']; ?></label>
                            <input class="formularright" type="text" id="page_title"
                                   name="config[template][pagetitle]"
                                   title="<?php echo $language['STEP5_SITENAME_TOOLTIP']; ?>"
                                   value="<?php echo $values['pagetitle']; ?>" />
                        </li>
                        <li>
                            <label class="formularleft" for="from"><?php echo $language['STEP5_CONFIG_EMAILFROM']; ?></label>
                            <input class="formularright" type="text" id="from"
                                   name="config[email][from]"
                                   title="<?php echo $language['STEP5_SYSTEM_EMAIL_TOOLTIP']; ?>"
                                   value="<?php echo $values['from']; ?>" />
                        </li>
                        <li>
                            <label class="formularleft" for="encrytion"><?php echo $language['STEP5_CONFIG_USERACCOUNT_ENCRYPTION']; ?></label>
                            <select class="formularright" id="encryption" name="encryption"
                                    title="<?php echo $language['STEP5_ACCOUNT_CRYPT_TOOLTIP']; ?>">
                                <?php # SHA1 ?>
                                <option value="sha1"<?php echo ($values['encryption']=='sha1') ? ' selected="selected"' : ''; ?>>SHA1</option>
                                <?php # HASH Options ?>
                                <?php
                                if (extension_loaded('hash')) {
                                    $hash_algos = hash_algos();
                                    foreach ($hash_algos as $hash_algo) {
                                        echo "<option value='$hash_algo'";
                                        if ($values['encryption'] == $hash_algo) {
                                            echo ' selected="selected"';
                                        }
                                        echo ">$hash_algo</option>";
                                    }
                                }
                                ?>
                            </select>
                        </li>
                        <li>
                            <?php # timezone selection ?>
                            <label class="formularleft" for="timezone"><?php echo $language['STEP5_CONFIG_GMTOFFSET']; ?></label>
                            <select class="formularright" id="timezone"
                                    name="config[language][gmtoffset]" class="form"
                                    title="<?php echo $language['STEP5_GMTOFFSET_TOOLTIP']; ?>">
                                <option value="-36000">UTC -10 Hawaii</option>
                                <option value="-32400">UTC -9 Alaska</option>
                                <option value="-28800">UTC -8 Pacific (USA, Canada)</option>
                                <option value="-25200">UTC -7 Arizona, Salt Lake City</option>
                                <option value="-21600">UTC -6 Chicago, Mexico City</option>
                                <option value="-18000">UTC -5 New York, Miami, Toronto</option>
                                <option value="-14400">UTC -4 Santiago de Chile, Quebec, La Paz</option>
                                <option value="-10800">UTC -3 Brasilien, Groenland</option>
                                <option value="-7200">UTC -2 Mittelatlantik</option>
                                <option value="-3600">UTC -1 Azoren</option>
                                <option value="0">UTC +0 London GMT</option>
                                <option value="3600" selected="selected">UTC +1 Amsterdam, Berlin, Bern, Rom, Stockholm, Wien</option>
                                <option value="7200">UTC +2 Athen, Helsinki, Kairo</option>
                                <option value="10800">UTC +3 Moskau, Bagdad, Teheran</option>
                                <option value="14400">UTC +4 Abu Dhabi, Kaukasus</option>
                                <option value="18000">UTC +5 Islamabad, Karatschi, Taschkent</option>
                                <option value="21600">UTC +6 Nowosibirsk, Dhaka</option>
                                <option value="25200">UTC +7 Bangkok, Hanoi, Jakarta</option>
                                <option value="28800">UTC +8 Peking, Hongkong</option>
                                <option value="32400">UTC +9 Ossaka, Sapporo, Tokyo</option>
                                <option value="36000">UTC +10 Brisbane, Wladiwostok</option>
                                <option value="39600">UTC +11 Magadan, Salomonen</option>
                                <option value="43200">UTC +12 Fidschi, Wellingtion, Marshall-Islands</option>
                            </select>
                        </li>
                    </ol>
                    </fieldset>
                    <div id="content_footer">
                        <div class="navigation">
                            <span style="font-size:10px;">
                                <?php echo $language['CLICK_NEXT_TO_PROCEED']; ?><br />
                                <?php echo $language['CLICK_BACK_TO_RETURN']; ?><br />
                            </span>
                            <div class="alignright">
                                <input type="submit" value="<?php echo $language['NEXTSTEP']; ?>" class="ButtonGreen" name="step_forward" />
                            </div>
                            <div class="alignleft">
                                <input type="submit" value="<?php echo $language['BACKSTEP']; ?>" class="ButtonRed" name="step_backward" />
                                <input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>" />
                            </div>
                        </div>
                    </div>
            </form>
            </div>
        </div>
    </div>
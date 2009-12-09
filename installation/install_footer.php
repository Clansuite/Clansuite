<?php
/**
 * Security Handler
 */
if (!defined('IN_CS')){ die( 'Clansuite not loaded. Direct Access forbidden.' );}

?>

    <!-- Fusszeile -->
    <div id="footer">
        <p style="filter:alpha(opacity=65); -moz-opacity:0.65; padding-bottom: 25px;">
            <br />
            <?php
            # Set Timezone for date functions to avoide E_NOTICES & E_WARNINGS on calling date/time functions
            # @todo hardcoded berlin, detection of some kind needed here?
            # fmpov timezone of server has to be set in php.ini, so it's not in our scope
            date_default_timezone_set('Europe/Berlin');
            $webinstaller_version = 'Version : 0.3 - '. date("l, jS F Y",getlastmod()); ?>
            Clansuite Installation <?php echo $webinstaller_version; ?>
            <br />
            SVN: $Rev$ $Author$
            <br />
            &copy; 2005-<?php echo date("Y"); ?> by <a href="http://www.jens-andre-koch.de" target="_blank" style="text-decoration=none">Jens-Andr&#x00E9; Koch</a> &amp; Clansuite Development Team
        </p>
    </div><!-- Fusszeile ENDE -->
</div><!-- PAGE ENDE -->

<div id="rightpage">
       <div id="top"></div>
       <div id="rightsidebar">
       <div id="rightinner">
                <ul>

                    <!-- Installation Progress BAR -->
                    <li><h2><?php echo $language['INSTALL_PROGRESS']?></h2></li>
                    <li><?php echo $language['COMPLETED']?> <b><?php echo $_SESSION['progress']?>%</b>
                      <div id="progressbar">
                            <?php
                            #note by vain: this fixes a 2pixel problem while displaying the progress bar at 100percent:P
                            if ($_SESSION['step'] == 7 ) { $_SESSION['progress'] = $_SESSION['progress'] - 2; }
                            ?>
                            <div style="border: 1px solid white; height: 5px ! important; width: <?php echo $_SESSION['progress']?>px; background-color: rgb(181, 0, 22);"/>
                        </div>
                    </li>

                    <!-- Change Language -->
                    <li><h2><?php echo $language['CHANGE_LANGUAGE']?></h2></li>
                    <li>
                        <?php # pruefen ob es die aktuelle sprache ist, die reloaded werden soll
                              # nur reloaden, wenn neue sprache ausgewaehlt                ?>
                        <select name="lang" style="width: 100px"
                                onchange="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?lang='+this.options[this.selectedIndex].value;" >
                        <?php
                        echo '<option value="">- Select Language -</option>';
                        foreach (new DirectoryIterator('./languages/') as $file) {
                           // get each file not starting with dots ('.','..')
                           // or containing ".install.php"
                           if ((!$file->isDot()) && preg_match("/.gif$/",$file->getFilename()))
                           {
                              echo '<option style="padding-left: 30px; background-image: url(./languages/' . $file .'); background-position:5px 100%; background-repeat: no-repeat;"';
                              $file = substr($file->getFilename(), 0, -4);
                              if ($_SESSION['lang'] == $file) { echo ' selected="selected"'; }
                              echo '>';
                              echo $file;
                              echo "</option>\n";
                           }
                        }
                        ?>
                        </select>
                    </li>

                    <!-- Live Support (Link and Tracking) -->
                    <li><h2><?php echo $language['LIVESUPPORT']?></h2></li>
                        <!-- Start Live Support Javascript -->
                        <div style="text-align:center;width:120px;">
                           <a href="javascript:void(window.open('http://www.clansuite.com/livezilla/livezilla.php?code=T2ZmaXppZWxsZSBXZWJzZWl0ZSBjbGFuc3VpdGUuY29t&amp;reset=true','','width=600,height=600,left=0,top=0,resizable=yes,menubar=no,location=yes,status=yes,scrollbars=yes'))">
                            <img src="http://www.clansuite.com/livezilla/image.php?id=05" width="120" height="30" border="0" alt="LiveZilla Live Help" /></a>
                            <noscript>
                                <div>
                                 <a href="http://www.clansuite.com/livezilla/livezilla.php?code=T2ZmaXppZWxsZSBXZWJzZWl0ZSBjbGFuc3VpdGUuY29t&amp;reset=true" target="_blank">Start Live Help Chat</a>
                                </div>
                           </noscript>               
                        </div>
                        <!-- End Live Support Javascript -->

                        <!-- Start Live Support Tracking Javascript -->
                        <div id="livezilla_tracking" style="display:none"></div>
                        <script type="text/javascript">
                            <!--
                             var script = document.createElement("script");
                                 script.type="text/javascript";
                             var src = "http://www.clansuite.com/livezilla/server.php?request=track&output=jcrpt&code=SW5zdGFsbGF0aW9u&reset=true&nse="+Math.random();
                                        setTimeout("script.src=src;document.getElementById('livezilla_tracking').appendChild(script)",1);
                            -->
                        </script>
                        <!-- End Live Support Tracking Javascript -->
                        <br /><br />
                    </li>

                    <!-- Clansuite Shortcuts -->
                    <li><h2><?php echo $language['SHORTCUTS']?></h2></li>
                    <li><strong><a href="http://www.clansuite.com/">Website</a></strong></li>
                    <li><strong><a href="http://forum.clansuite.com/">Forum</a></strong></li>
                    <li><strong><a href="http://forum.clansuite.com/index.php?board=25">Installsupport</a></strong></li>
                    <li><strong><a href="http://trac.clansuite.com/">Bugtracker</a></strong></li>
                    <li><strong><a href="http://webchat.quakenet.org/?channels=clansuite">IRC Webchat</a></strong></li>
                    <li><strong><a href="http://www.clansuite.com/toolbar/">Toolbar</a></strong></li>

                    <!-- Donate -->
                    <!-- <li><h2>Donate</h2></li>
                    <li> -->
                    <!-- PayPal Direct Image Button
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                        <input type="hidden" name="cmd" value="_s-xclick" />
                        <input type="image" src="https://www.paypal.com/de_DE/i/btn/x-click-but04.gif" name="submit" alt="Zahlen Sie mit PayPal - schnell, kostenlos und sicher!" />
                        <img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1" />
                        <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHTwYJKoZIhvcNAQcEoIIHQDCCBzwCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYB0LEEuVZOTu++bRevqW4bD4mdoGvWnTwCQ4urr8cax4ilsFehU4sl729m3S9QtPQv0B7CFhtWGxJ7pXhx3cQ35nTzobkxCYRYy01Aw0Gkmlxnc+6Rz7lIjAKOnL6U9Ftr7iCJH74c6ryJSlI8QB9dsqUi2YBsgfljyx5w/bunS9TELMAkGBSsOAwIaBQAwgcwGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIXabqVcNbyPOAgaiz4LIIs8323fnbtieAP3ump4WwZ7rItgWlTYEj4DnK3zhL8nj78XevGVKQ3PjAHGHPIqvqHeP8QEgUWtW4B7cnRGZyPGF6eXOPnNGAfDpALa4us2I38klL3HI207q5ob+2Rz/9gu5wLccfDcWfyi5aTBVzWcozcyIwyhaOgZP8z1JzVj26uYhqZwPOryQ6KmvUa//K9+6RyEyttVo51/EtejO1zX/KNsKgggOHMIIDgzCCAuygAwIBAgIBADANBgkqhkiG9w0BAQUFADCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wHhcNMDQwMjEzMTAxMzE1WhcNMzUwMjEzMTAxMzE1WjCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAMFHTt38RMxLXJyO2SmS+Ndl72T7oKJ4u4uw+6awntALWh03PewmIJuzbALScsTS4sZoS1fKciBGoh11gIfHzylvkdNe/hJl66/RGqrj5rFb08sAABNTzDTiqqNpJeBsYs/c2aiGozptX2RlnBktH+SUNpAajW724Nv2Wvhif6sFAgMBAAGjge4wgeswHQYDVR0OBBYEFJaffLvGbxe9WT9S1wob7BDWZJRrMIG7BgNVHSMEgbMwgbCAFJaffLvGbxe9WT9S1wob7BDWZJRroYGUpIGRMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbYIBADAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4GBAIFfOlaagFrl71+jq6OKidbWFSE+Q4FqROvdgIONth+8kSK//Y/4ihuE4Ymvzn5ceE3S/iBSQQMjyvb+s2TWbQYDwcp129OPIbD9epdr4tJOUNiSojw7BHwYRiPh58S1xGlFgHFXwrEBb3dgNbMUa+u4qectsMAXpVHnD9wIyfmHMYIBmjCC
                        </form>
                    -->
                        
                    <!-- Pledige Campaign -->
                    <!-- <a href='http://www.pledgie.com/campaigns/6324'><img alt='Click here to lend your support to: Unterstuetzt Clansuite! and make a donation at www.pledgie.com !' src='http://www.pledgie.com/campaigns/6324.png?skin_name=eight_bit' border='0' /></a> -->
                    <!-- </li> -->

                    <!-- Link Us -->
                    <li><h2>Link us</h2></li>
                    <li><a href="http://www.clansuite.com/banner/" target="_blank"><img src="http://www.clansuite.com/website/images/banners/clansuite-crown-banner-80x31.png" alt="Clansuite 80x31 LOGO" /></a></li>

               </ul>
        </div><!-- rightinner ENDE -->
        </div><!-- rightsidebar ENDE -->
        <div id="bottom"></div>
</div><!-- rightpage ENDE -->

</div><!-- Wrapper ENDE -->
</body>
</html>
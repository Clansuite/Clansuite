<?php
# Security Handler
if (defined('IN_CS') === false)
{
    die( 'Clansuite not loaded. Direct Access forbidden.' );
}
?>

    <div id="footer">
        <p style="filter:alpha(opacity=65); -moz-opacity:0.65; padding-bottom: 25px;">
            <br />
            <?php
            date_default_timezone_set('Europe/Berlin');
            $webinstaller_version = 'Version 0.4 - '. date('l, jS F Y',getlastmod()); ?>
            Clansuite Installation <?php echo $webinstaller_version; ?>
            <br />
            SVN: $Rev$ $Author$
            <br />
            &copy; 2005-<?php echo date('Y'); ?> by <a href="http://www.jens-andre-koch.de" target="_blank">Jens-Andr&#x00E9; Koch</a> &amp; Clansuite Development Team.
        </p>
    </div>
</div><!-- PAGE ENDE -->

<div id="rightpage">
   <div id="top"></div>
   <div id="rightsidebar">
   <div id="rightinner">
        <ul>

            <!-- Installation Progress BAR -->
            <li><h2><?php echo $language['INSTALL_PROGRESS']; ?></h2></li>
            <li><?php echo $language['COMPLETED']; ?>
              <b><?php echo $_SESSION['progress']; ?>%</b>
              <div id="progressbar">
                    <?php
                    #note by vain: this fixes a 2pixel problem while displaying the progress bar at 100percent:P
                    if ($_SESSION['step'] == 7 ) { $_SESSION['progress'] = $_SESSION['progress'] - 2; }
                    ?>
                    <div style="border: 1px solid white; height: 5px ! important; width: <?php echo $_SESSION['progress']; ?>px; background-color: rgb(181, 0, 22);"/>
                </div>
            </li>

            <!-- Change Language -->
            <li><h2><?php echo $language['CHANGE_LANGUAGE']; ?></h2></li>
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
                      echo ' value=' . $file .'>';
                      echo $file;
                      echo "</option>\n";
                   }
                }
                ?>
                </select>
            </li>

            <!-- Live Support (Link and Tracking) -->
            <li><h2><?php echo $language['LIVESUPPORT']; ?></h2></li>
            <li>
                <!-- Start Live Support Javascript -->
                <div style="text-align:center;width:120px;">
                   <a href="javascript:void(window.open('http://support.clansuite.com/livezilla.php?code=T2ZmaXppZWxsZSBXZWJzZWl0ZSBjbGFuc3VpdGUuY29t&amp;reset=true','','width=600,height=600,left=0,top=0,resizable=yes,menubar=no,location=yes,status=yes,scrollbars=yes'))">
                    <img src="http://support.clansuite.com/image.php?id=05" width="120" height="30" border="0" alt="LiveZilla Live Help" /></a>
                    <noscript>
                        <div>
                         <a href="http://support.clansuite.com/livezilla.php?code=T2ZmaXppZWxsZSBXZWJzZWl0ZSBjbGFuc3VpdGUuY29t&amp;reset=true" target="_blank">Start Live Help Chat</a>
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
                     var src = "http://support.clansuite.com/server.php?request=track&output=jcrpt&code=SW5zdGFsbGF0aW9u&reset=true&nse="+Math.random();
                                setTimeout("script.src=src;document.getElementById('livezilla_tracking').appendChild(script)",1);
                    -->
                </script>
                <!-- End Live Support Tracking Javascript -->
                <br /><br />
            </li>

            <!-- Clansuite Shortcuts -->
            <li><h2><?php echo $language['SHORTCUTS']; ?></h2></li>
            <li><strong><a href="http://www.clansuite.com/">Website</a></strong></li>
            <li><strong><a href="http://forum.clansuite.com/">Forum</a></strong></li>
            <li><strong><a href="http://forum.clansuite.com/index.php?board=25">Installsupport</a></strong></li>
            <li><strong><a href="http://trac.clansuite.com/">Bugtracker</a></strong></li>
            <li><strong><a href="http://webchat.quakenet.org/?channels=clansuite">IRC Webchat</a></strong></li>
            <li><strong><a href="http://www.clansuite.com/toolbar/">Toolbar</a></strong></li>

            <!-- Link Us -->
            <li><h2>Link us</h2></li>
            <li><a href="http://www.clansuite.com/banner/" target="_blank"><img src="http://www.clansuite.com/banner/clansuite-crown-banner-88x31.png" alt="Clansuite 80x31 LOGO" /></a></li>

       </ul>
    </div><!-- rightinner ENDE -->
    </div><!-- rightsidebar ENDE -->
    <div id="bottom"></div>
</div><!-- rightpage ENDE -->

</div><!-- Wrapper ENDE -->
</body>
</html>
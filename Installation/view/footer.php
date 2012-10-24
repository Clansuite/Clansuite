    <div id="footer">
        <p>
            <br />
            <?php
            date_default_timezone_set('Europe/Berlin');
            $version = APPLICATION_VERSION . ' - ' . date('l, jS F Y', getlastmod()); ?>
            Clansuite Installation v<?php echo $version; ?>
            <br />
            &copy; 2005-<?php echo date('Y'); ?> by <a href="http://www.jens-andre-koch.de" target="_blank">Jens-Andr&#x00E9; Koch</a> &amp; Clansuite Development Team.
        </p>
        <span id="footer-left">
            <a href="http://www.opensource.org/">
              <img height="65" width="75" title="OpenSource Logo" alt="OpenSource Logo" src="assets/images/opensource-75x65-t.png">
            </a>
        </span>
        <span id="footer-right">
            <a href="http://clansuite.com/banner/">
                <img height="31" width="88" title="Clansuite Logo" alt="Clansuite 80x31 Crown Logo" src="assets/images/clansuite-crown-banner-88x31.png">
            </a>
        </span>
    </div>

</div><!-- PAGE ENDE -->

<div id="rightpage">
   <div id="rightsidebar">

        <ul>

            <!-- Installation Progress BAR -->
            <li><h2><?php echo $language['INSTALL_PROGRESS']; ?></h2></li>
            <li><?php echo $language['COMPLETED']; ?>
              <b><?php echo $_SESSION['progress']; ?>%</b>
              <div id="progressbar">
                    <?php
                    #note by vain: this fixes a 2pixel problem while displaying the progress bar at 100percent:P
                    if ($_SESSION['step'] == 7) { $_SESSION['progress'] = $_SESSION['progress'] - 2; }
                    ?>
                  <div style="border: 1px solid white; height: 5px ! important; width: <?php echo $_SESSION['progress']; ?>px; background-color: rgb(181, 0, 22);"></div>
              </div>
            </li>

            <!-- Change Language -->
            <li><h2><?php echo $language['CHANGE_LANGUAGE']; ?></h2></li>
            <li><?php \Clansuite\Installation\Application\Viewhelper::renderLanguageDropdown($language); ?></li>

            <!-- Live Support (Link and Tracking) -->
            <li><h2><?php echo $language['LIVESUPPORT']; ?></h2></li>
            <li>
                <!-- Start Live Support Javascript -->
                <div style="text-align:center;width:120px;">
                   <a href="javascript:void(window.open('http://support.clansuite.com/livezilla.php?code=T2ZmaXppZWxsZSBXZWJzZWl0ZSBjbGFuc3VpdGUuY29t&amp;reset=true','','width=600,height=600,left=0,top=0,resizable=yes,menubar=no,location=yes,status=yes,scrollbars=yes'))">
                    <img src="http://support.clansuite.com/image.php?id=05" width="120" height="30" alt="LiveZilla Live Help" /></a>
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
                <br />
            </li>

            <!-- Clansuite Shortcuts -->
            <li><h2><?php echo $language['SHORTCUTS']; ?></h2></li>
            <li><strong><a href="http://clansuite.com/">Website</a></strong></li>
            <li><strong><a href="http://forum.clansuite.com/">Forum</a></strong></li>
            <li><strong><a href="http://forum.clansuite.com/index.php?board=4">Supportforum</a></strong></li>
            <li><strong><a href="http://trac.clansuite.com/">Bugtracker</a></strong></li>
            <li><strong><a href="http://webchat.quakenet.org/?channels=clansuite">IRC Webchat</a></strong></li>
            <li><strong><a href="http://clansuite.com/toolbar/">Toolbar</a></strong></li>

            <!-- Link Us -->
            <li><h2>Link us</h2></li>
            <li><a href="http://clansuite.com/banner/" target="_blank"><img src="assets/images/clansuite-crown-banner-88x31.png" alt="Clansuite 80x31 LOGO" /></a></li>

       </ul>
    </div>
</div>
</div>
<script type="text/javascript">
$('input[title]').poshytip({
    className: 'tip-yellowsimple',
    showOn: 'focus',
    alignTo: 'target',
    alignX: 'inner-left',
    offsetX: 0,
    offsetY: 5
});
$('select').poshytip({
    className: 'tip-yellowsimple',
    showOn: 'focus',
    alignTo: 'target',
    alignX: 'inner-left',
    offsetX: 0,
    offsetY: 5
});
</script>
</body>
</html>

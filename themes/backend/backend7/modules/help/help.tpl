<!-- Module Help - help.tpl -->
<div id="help_wrapper">

    <div id="help" class="admin_help">

    <ul start="1" type="I">
    <h3>Help Overview</h3>

    <!-- 1) Help for this specific module section displayed. -->
    <li>
        Help for this Module
        <p>
            {help}
        </p>
    </li>

    <!-- 2) Help in general -->
    <li>
        Help & Support in general
        <p>
           <ul start="1" type="1">
               <li><a target="_blank" href="http://docs.clansuite.com/user/manual/de"> {t}Documentation: User-Manual{/t} </a></li>
               <li><a target="_blank" href="http://forum.clansuite.com/index.php/board,4.0.html"> {t}Clansuite Forum: Support & Troubleshooting{/t} </a></li>
               <li><a target="_blank" href="teamspeak://clansuite.com:8000?channel=clansuite%20Admins?subchannel=clansuite%20Support"> {t}Get Clansuite Support via Teamspeak{/t} </a></li>
           </ul>
           <br />
        </p>
    </li>

    <!-- 3) Help Wiki -->
    <li>
        Get Live Support
        <p>
           <!-- http://www.LiveZilla.net Chat Button Link Code -->
           <div style="padding-left: 20px;">
               <a href="javascript:void(window.open('http://support.clansuite.com/livezilla.php?reset=true','','width=600,height=600,left=0,top=0,resizable=yes,menubar=no,location=yes,status=yes,scrollbars=yes'))">
               <img src="http://support.clansuite.com/image.php?id=04" width="160" height="40" border="0" alt="Clansuite Live Support">
               </a>
           <noscript>
               <div>
               <a href="http://support.clansuite.com/livezilla.php?reset=true" target="_blank">Start Live Help Chat</a>
               </div>
           </noscript>
           </div>
           <!-- http://www.LiveZilla.net Chat Button Link Code -->

           {if isset($helptracking) and ($helptracking==true)}
               <div style="padding-left: 40px; margin-top:2px;"><a href="http://support.clansuite.com/livezilla.php" target="_blank" title="Clansuite Live Support" style="font-size:10px;color:#bfbfbf;text-decoration:none;font-family:verdana,arial,tahoma;">Help Tracking enabled.</a></div>
               <!-- http://www.LiveZilla.net Tracking Code --><div id="livezilla_tracking" style="display:none"></div><script type="text/javascript">
                /* <![CDATA[ */
                var script = document.createElement("script");script.type="text/javascript";var src = "http://support.clansuite.com/server.php?request=track&output=jcrpt&nse="+Math.random();setTimeout("script.src=src;document.getElementById('livezilla_tracking').appendChild(script)",1);
                /* ]]> */
                </script><!-- http://www.LiveZilla.net Tracking Code -->
           {/if}

           
        </p>
     </li>

    </ul>

    </div>

</div>
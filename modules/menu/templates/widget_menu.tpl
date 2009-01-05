{doc_raw}
<script src="{$www_root_themes_core}/javascript/XulMenu.js" type="text/javascript"></script>
{/doc_raw}

<table id="Frontend-Menu-1" cellspacing="0" cellpadding="0" class="XulMenu" width="100%">
<tr>
    <td class="td_header">
        {t}Menu{/t}
    </td>
</tr>
<tr>
    <td>
        <a class="button" href="javascript:void(0)">Public<img class="arrow" src="{$www_root_themes_core}/images/arrow1.gif" width="4" height="7" alt="" /></a>

        <div class="section">
            <a class="item" href="javascript:void(0)"><img class="pic" src="{$www_root_themes_core}/images/icons/modules.png" border="0" width="16" height="16" alt="" />Modules<img class="arrow" src="{$www_root_themes_core}/images/arrow1.gif" width="4" height="7" alt="" /></a>
              <div class="section">
                  <a class="item" href="index.php">Main</a>
                  <a class="item" href="index.php?mod=news"><img class="pic" src="{$www_root_themes_core}/images/icons/news.png" border="0" width="16" height="16" alt=""/>News</a>
                  <a class="item" href="index.php?mod=news&amp;action=archiv"><img class="pic" src="{$www_root_themes_core}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Newsarchiv</a>
                  <a class="item" href="index.php?mod=guestbook"><img class="pic" src="{$www_root_themes_core}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Guestbook</a>
                  <a class="item" href="index.php?mod=board"><img class="pic" src="{$www_root_themes_core}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Board</a>
                  <a class="item" href="index.php?mod=serverlist"><img class="pic" src="{$www_root_themes_core}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Serverlist</a>
                  <a class="item" href="index.php?mod=users">Users</a>
                  <a class="item" href="index.php?mod=staticpages&amp;page=credits"><img class="pic" src="{$www_root_themes_core}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Credits</a>
                  <a class="item" href="index.php?mod=staticpages&amp;action=overview"><img class="pic" src="{$www_root_themes_core}/images/icons/news.png" border="0" width="16" height="16" alt="" />Static Pages Overview</a>
               </div>

            <a class="item" href="index.php?mod=users"><img class="pic" src="{$www_root_themes_core}/images/icons/users.png" border="0" width="16" height="16" alt=""/>Users<img class="arrow" src="{$www_root_themes_core}/images/arrow1.gif" width="4" height="7" alt="" /></a>
              <div class="section">
                  <a class="item" href="index.php?mod=account">Login</a>
                  <a class="item" href="index.php?mod=account"><img class="pic" src="{$www_root_themes_core}/images/icons/logout.png" border="0" width="16" height="16" alt=""/>Logout</a>
              </div>
        </div>

        <a class="button" href="index.php?mod=controlcenter">Control Center (CC)</a>

    </td>
</tr>
</table>

<!-- XUL Menu Init -->
<script type="application/javascript">
//<![CDATA[
    var menu1 = new XulMenu("Frontend-Menu-1");
    menu1.type = "vertical";
    menu1.position.level1.top = 0;
    menu1.arrow1 = "{$www_root_themes_core}/images/arrow1.gif";
    menu1.arrow2 = "{$www_root_themes_core}/images/arrow2.gif";
    menu1.init();
//]]>
</script>
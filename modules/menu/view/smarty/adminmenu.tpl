{move_to target="pre_head_close"}
<link rel="stylesheet" type="text/css" href="{$www_root}modules/menu/css/menu.css" />
<script type="text/javascript" src="{$www_root}modules/menu/javascript/XulMenu.js"></script>
{/move_to}

<!-- Start: Adminmenu (Modules->Menu)-->

<script type="text/javscript">
/* preload images */
var arrow1 = new Image(4, 7);
arrow1.src =  "{$www_root}modules/menu/images/arrow1.gif";
var arrow2 = new Image(4, 7);
arrow2.src =  "{$www_root}modules/menu/images/arrow2.gif";
</script>

<div class="menugradient">

    <div class="bar">

        <!-- XULMenu Table - Important is the id tag, it's the selector used by the JS. -->
        <table id="Adminmenu" cellspacing="0" cellpadding="0" class="XulMenu" width="100%">
            <tr>
                <!-- module-include: admin menueditor get_html_div -->
                {load_module name="menu" sub="admin" action="get_html_div"}
            </tr>
        </table>

        <script type="text/javascript">
            var Adminmenu = new XulMenu("Adminmenu");
            Adminmenu.arrow1 = "{$www_root}modules/menu/images/arrow1.gif";
            Adminmenu.arrow2 = "{$www_root}modules/menu/images/arrow2.gif";
            Adminmenu.init();
        </script>

    </div>

    <div class="adminmenu-rightside">

        <a class="itembtn" href="index.php?mod=account&amp;sub=admin&amp;action=usercenter">
            <img style="position:relative; top: 4px" src="{$www_root_themes_core}images/icons/user_suit.png" border="0" alt="user-image" width="16" height="16" />
            &nbsp;{$smarty.session.user.nick}
        </a>

        &nbsp;

        <a href="index.php" class="itembtn">
            <img style="position:relative; top: 4px" src="{$www_root_themes_core}images/icons/layout_header.png" border="0" alt="layout-image" width="16" height="16" />
            &nbsp;{t}Show Frontpage{/t}
        </a>

        &nbsp;

        <a href="index.php?mod=account&amp;action=logout" class="itembtn">
            <img style="position:relative; top: 4px" src="{$www_root_themes_core}images/icons/system-log-out.png" border="0" alt="logout-image" width="16" height="16" />
            &nbsp;{t}Logout{/t}
        </a>

      </div>

</div>
<!-- End: AdminMenu (Modules->Menu) -->
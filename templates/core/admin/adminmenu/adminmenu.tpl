{doc_raw}
<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/adminmenu/menu.css" />
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/XulMenu.js"></script>
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/ie5.js"></script>
{/doc_raw}

<!-- start: AdminMenu - Kopfzeile 2//-->

<script type="text/javscript">
/* preload images */
var arrow1 = new Image(4, 7);
arrow1.src =  "{$www_core_tpl_root}/images/adminmenu/arrow1.gif";
var arrow2 = new Image(4, 7);
arrow2.src =  "{$www_core_tpl_root}/images/adminmenu/arrow2.gif";
</script>

<div class="menugradient">

    <div class="bar">

        <table cellspacing="0" cellpadding="0" id="menu1" class="XulMenu" width="100%">
            <tr>
                <td width="1"><img src="{$www_core_tpl_root}/images/adminmenu/nubs.gif" alt="" /></td>

                {mod name="admin" sub="menueditor" func="get_html_div"}

                <td align="right" width="100%">
                    <span height="1%">

                        <a class="itembtn" href="index.php?mod=admin&sub=users&action=usercenter">
                            <img style="position:relative; top: 4px" src="{$www_core_tpl_root}/images/icons/user_suit.png" border="0" alt="user-image" width="16" height="16" />
                            &nbsp;{$smarty.session.user.first_name} '{$smarty.session.user.nick}' {$smarty.session.user.last_name}
                        </a>
                        &nbsp;

                        <a href="index.php?mod=account&action=logout" class="itembtn">
                            <img style="position:relative; top: 4px" src="{$www_core_tpl_root}/images/icons/door_out.png" border="0" alt="logout-image" width="16" height="16" />
                            &nbsp;{translate}Logout{/translate}
                        </a>
                    </span>
                    &nbsp;
                </td>
            </tr>
        </table>

        <script type="text/javascript">
            var menu1 = new XulMenu("menu1");
            menu1.arrow1 = "{$www_core_tpl_root}/images/adminmenu/arrow1.gif";
            menu1.arrow2 = "{$www_core_tpl_root}/images/adminmenu/arrow2.gif";
            menu1.init();
        </script>

    </div>

</div>
<!-- end: AdminMenu - Kopfzeile 2//-->
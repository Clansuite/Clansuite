{assign var=reduce value=$templatepath|strrpos:"/templates"}
{assign var=tplpath value=$templatepath|@truncate:$reduce:"":true}

{doc_raw}
<link rel="stylesheet" type="text/css" href="{$tplpath}/css/menu.css" />
<script type="text/javascript" src="{$tplpath}/javascript/XulMenu.js"></script>
{/doc_raw}

<!-- start: AdminMenu - Header 2 //-->

<script type="text/javscript">
/* preload images */
var arrow1 = new Image(4, 7);
arrow1.src =  "{$imgpath}/images/arrow1.gif";
var arrow2 = new Image(4, 7);
arrow2.src =  "{$imgpath}/images/arrow2.gif";
</script>

<div class="menugradient">

    <div class="bar">

        <table cellspacing="0" cellpadding="0" id="menu1" class="XulMenu" width="100%">
            <tr>
                <td>&nbsp;</td>

                <!-- module-include: admin menueditor get_html_div //-->
                {load_module name="menu" sub="admin" action="get_html_div"}

                <td align="right" width="100%">
                        <a class="itembtn" href="index.php?mod=admin&amp;sub=users&amp;action=usercenter">
                            <img style="position:relative; top: 4px" src="{$www_root_themes_core}/images/icons/user_suit.png" border="0" alt="user-image" width="16" height="16" />
                            &nbsp;{$smarty.session.user.nick}
                        </a>
                        &nbsp;

                        <a href="index.php" class="itembtn">
                            <img style="position:relative; top: 4px" src="{$www_root_themes_core}/images/icons/layout_header.png" border="0" alt="logout-image" width="16" height="16" />
                            &nbsp;{t}Show Frontpage{/t}
                        </a>
                        &nbsp;

                        <a href="index.php?mod=account&amp;action=logout" class="itembtn">
                            <img style="position:relative; top: 4px" src="{$www_root_themes_core}/images/icons/door_out.png" border="0" alt="logout-image" width="16" height="16" />
                            &nbsp;{t}Logout{/t}
                        </a>
                </td>
            </tr>
        </table>

       
        
        <script type="text/javascript">
            var menu1 = new XulMenu("menu1");
            menu1.arrow1 = "{$imgpath}/images/arrow1.gif";
            menu1.arrow2 = "{$imgpath}/images/arrow2.gif";
            menu1.init();
        </script>

    </div>

</div>
<!-- end: AdminMenu - Header 2 //-->
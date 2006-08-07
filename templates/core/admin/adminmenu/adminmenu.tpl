{doc_raw}
<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/adminmenu/menu.css" />
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/XulMenu.js"></script>
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/ie5.js"></script>
{/doc_raw}

<!-- start: Menu - Kopfzeile 2//-->
    
    <script type="text/javscript">
    /* preload images */
    var arrow1 = new Image(4, 7);
    arrow1.src =  "{$www_core_tpl_root}/images/arrow1.gif";
    var arrow2 = new Image(4, 7);
    arrow2.src =  "{$www_core_tpl_root}/images/arrow2.gif";
    </script>

<div id="menugradient">

<div id="bar" class="bar">
<table cellspacing="0" cellpadding="0" id="menu1" class="XulMenu">
  <tr>
        <td><img src="{$www_core_tpl_root}/images/adminmenu/nubs.gif" alt="" /></td>

        {mod name="admin" sub="menueditor" func="get_html_div"}

  </tr>
</table>

    <script type="text/javascript">
    var menu1 = new XulMenu("menu1");
    menu1.arrow1 = "{$www_core_tpl_root}/images/adminmenu/arrow1.gif";
    menu1.arrow2 = "{$www_core_tpl_root}/images/adminmenu/arrow2.gif";
    menu1.init();
    </script>
    
    </div>
		
    <div id="search" class="XulMenu">
            
        <!--
        <input type="text" name="searchField" value="" />
        <select name="searchWhat"><option value="">Articles</option><option value="">Links</option><option value="">PHP Manual</option></select>
        <input type="button" value="Search" />
        //-->
           
        <a class="itembtn" href="{$www_root}/index.php?mod=admin&sub=usercenter" >
        <img src="{$www_core_tpl_root}/images/adminmenu/user.gif" border="0" alt="user-image" />
        {$smarty.session.user.first_name} '{$smarty.session.user.nick}' {$smarty.session.user.last_name}</a>
        
        <a class="itembtn" href="{$www_root}/index.php?mod=account&action=logout.php">
        <img src="{$www_core_tpl_root}/images/adminmenu/logout.gif" border="0" alt="logout-image" />Logout</a>           
    
    </div>	 
    
</div>
<!-- end: Menu- Kopfzeile 2 //-->
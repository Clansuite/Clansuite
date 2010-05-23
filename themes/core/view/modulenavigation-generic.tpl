{* {$modulenavigation|@var_dump} *}

{* <link rel="stylesheet" href="{$www_root_themes_core}/css/tab.css" type="text/css" /> *}

{* /*- CSS Menus by exploding-boy
    Website: http://exploding-boy.com/images/cssmenus/menus.html
    Menu Tabs B
    --------------------------- */ *}

{move_to target="pre_head_close"}
<style type="text/css">
#tabsB
{
  float:right;
  /*width:100%;*/
  /*background:#F4F4F4;*/
  font-size:93%;
  line-height:normal;
  margin-top: -10px;
}

#tabsB ul
{
  margin:0;
  padding:10px 10px 0 50px;
  list-style:none;
}

#tabsB li
{
  display:inline;
  margin:0;
  padding:0;
}

#tabsB a
{
  float:left;
  background:url("{$www_root_themes}/core/css/tabs/tableftB.gif") no-repeat left top;
  margin:0;
  padding:0 0 0 4px;
  text-decoration:none;
}

#tabsB a span
{
  float:left;
  display:block;
  background:url("{$www_root_themes}/core/css/tabs/tabrightB.gif") no-repeat right top;
  padding:5px 15px 4px 6px;
  color:#666;
}

/* Commented Backslash Hack hides rule from IE5-Mac \*/

#tabsB a span
{
  float:none;
}

/* End IE5-Mac hack */
#tabsB a:hover span
{
  color:#000;
}
#tabsB a:hover
{
  background-position:0% -42px;
}

#tabsB a:hover span
{
 background-position:100% -42px;
}
</style>
{/move_to}

<div id="tabsB">
    <ul>
    {foreach $modulenavigation as $item}
    <li>
        <a href="{$item.url}" title="{$item.title}"> <span>{$item.name}</span> </a>
    </li>
    {/foreach}
    </ul>
</div>
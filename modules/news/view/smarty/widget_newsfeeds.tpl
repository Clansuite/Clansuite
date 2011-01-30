<!-- Start Widget Newsfeeds from Module Matches -->

<div class="news_widget" id="widget_newsfeeds" width="100%">

    <h2 class="menu_header">{t}Newsfeeds{/t}</h2>
        
    <div class="cell1">

    {* Valid format request strings are: RSS2.0, MBOX, OPML, ATOM, HTML, JS *}
    <a href="{$www_root}index.php?mod=news&action=getfeed&format=RSS2.0">RSS</a>
    <br />
    <a href="{$www_root}index.php?mod=news&action=getfeed&format=MBOX">MBOX</a>
    <br />
    <a href="{$www_root}index.php?mod=news&action=getfeed&format=ATOM">ATOM</a>
    <br />
    <a href="{$www_root}index.php?mod=news&action=getfeed&format=HTML">HTML</a>
    <br />
    <a href="{$www_root}index.php?mod=news&action=getfeed&format=JS">JS</a>
    
    </div>

</div>

<!-- Start Widget Newsfeeds from Module News -->
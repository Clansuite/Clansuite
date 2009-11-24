<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
<meta name="description" content="description"/>
<meta name="keywords" content="keywords"/>
<meta name="author" content="author"/>
<link rel="stylesheet" type="text/css" href="{$css}" media="screen" />
<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
<!-- page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}  -->
</head>

<!-- default margin = default layout -->
<body style="margin: 0 12%;">

<div class="container">

    <div class="header">
        <a href="index.html"><span>Clansuite in Shades of Gray</span></a>
    </div>

    <div class="stripes"><span></span></div>

    <div class="nav">
        <a href="index.php?mod=news">News</a>
        <a href="index.php?mod=members">Roster</a>
        <a href="index.php?mod=matches">Matches</a>
        <a href="index.php?mod=calendar">Calendar</a>
        <a href="index.php?mod=forum">Forum</a>
        <div class="clearer"><span></span></div>
    </div>

    <div class="stripes"><span></span></div>

    <div class="main">

        <div class="left">

            <div class="content">
            {include file='breadcrumbs.tpl'}
            <h1><!-- draw a line --></h1>
            {$content}

                <br /> <br /><br /> <br /><br /> <br />

                <h1>Formatting Examples</h1>
                <div class="descr">May 24, 2006 by Lectus</div>

                <a href="index.html">Nunc eget pretium</a> diam.

                <p>Praesent nisi sem, bibendum in, ultrices sit amet, euismod sit amet, dui. Fusce nibh. Curabitur pellentesque, lectus at <a href="index.html">volutpat interdum</a>. Pellentesque a nibh quis nunc volutpat aliquam</p>

                <blockquote><p>Sed sodales nisl sit amet augue. Donec ultrices, augue ullamcorper posuere laoreet, turpis massa tristique justo, sed egestas metus magna sed purus.</p></blockquote>

                <code>margin-bottom: 12px;
                font-size: 1.1em;
                background: url(images/quote.gif);
                padding-left: 28px;
                color: #555;</code>

                <ul>
                    <li>Tristique</li>
                    <li>Aenean</li>
                    <li>Pretium</li>
                </ul>

                <p>Eget feugiat est leo tempor quam. Ut quis neque convallis magna consequat molestie.</p>

            </div>

        </div>

        <div class="right">

            <div class="subnav">

                <div style="margin-top: 10px">
                <h2>Menu</h2>
                <dl>
                    <dd><a href="index.php">Home</a></dd>
                    <dt>Modules</dt>
                    <dd><a href="index.php?mod=news"><img class="pic" src="{$www_root_themes_core}/images/icons/news.png" border="0" width="16" height="16" alt=""/>News</a></dd>
                    <dd><a href="index.php?mod=news&amp;action=archiv"><img class="pic" src="{$www_root_themes_core}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Newsarchiv</a></dd>
                    <dd><a href="index.php?mod=serverlist"><img class="pic" src="{$www_root_themes_core}/images/icons/serverlist.png" border="0" width="16" height="16" alt=""/>Serverlist</a></dd>
                    <dd><a href="index.php?mod=staticpages&amp;page=credits"><img class="pic" src="{$www_root_themes_core}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Credits</a></dd>
                    <dd><a href="index.php?mod=staticpages&amp;action=overview"><img class="pic" src="{$www_root_themes_core}/images/icons/news.png" border="0" width="16" height="16" alt="" />Static Pages Overview</a></dd>
                    <dt>Users</dt>
                    <dd><a class="item" href="index.php?mod=account">Login</a></dd>
                    <dd><a class="item" href="index.php?mod=account"><img class="pic" src="{$www_root_themes}/images/icons/logout.png" border="0" width="16" height="16" alt=""/>Logout</a></dd>
                    <dt>ACP</dt>
                    <dd><a class="button" href="index.php?mod=admin">Admin</a></dd>
                </dl>
                </div>


                <div style="margin-top: 10px">{load_module name="news" action="widget_latestnews" items="2"}</div>

                <div style="margin-top: 10px">
                        <h2>{t}Statistics{/t}</h2>
                            {* {$stats|@var_dump} *}
                          Online: {* {$stats.online}  *}<br/>
                          - Users : {* {$stats.authed_users} *}
                          - Guests : {* {$stats.guest_users}  *}<br/>
                          Today: {* {$stats.today_impressions}  *}<br/>
                          Yesterday: {* {$stats.yesterday_impressions}  *}<br/>
                          Month: {* {$stats.month_impressions}  *}<br/>

                          This Page: {* {$stats.page_impressions}  *}<br/>
                          Total Impressions: {* {$stats.all_impressions} *}<br/>
                  </div>

                <h1>Sapien</h1>
                <ul>
                    <li><a href="index.html">sociis natoque</a></li>
                    <li><a href="index.html">magna sed purus</a></li>
                    <li><a href="index.html">tincidunt</a></li>
                </ul>

                <h1>Fringilla</h1>
                <ul>
                    <li><a href="index.html">sociis natoque</a></li>
                    <li><a href="index.html">magna sed purus</a></li>
                    <li><a href="index.html">tincidunt</a></li>
                </ul>

            </div>

        </div>

        <div class="clearer"><span></span></div>

    </div>

    <div class="footer">

            <div class="col3">

                <h2>Tincidunt</h2>
                <ul>
                    <li><a href="index.html">consequat molestie</a></li>
                    <li><a href="index.html">sem justo</a></li>
                    <li><a href="index.html">semper</a></li>
                    <li><a href="index.html">magna sed purus</a></li>
                    <li><a href="index.html">tincidunt</a></li>
                </ul>

            </div>

            <div class="col3center">
                <h2>Sed purus</h2>
                <ul>
                    <li><a href="index.html">consequat molestie</a></li>
                    <li><a href="index.html">sem justo</a></li>
                    <li><a href="index.html">semper</a></li>
                    <li><a href="index.html">magna sed purus</a></li>
                    <li><a href="index.html">tincidunt</a></li>
                </ul>
            </div>

            <div class="col3">
                <h2>Praesent</h2>
                <ul>
                    <li><a href="index.html">consequat molestie</a></li>
                    <li><a href="index.html">sem justo</a></li>
                    <li><a href="index.html">semper</a></li>
                    <li><a href="index.html">magna sed purus</a></li>
                    <li><a href="index.html">tincidunt</a></li>
                </ul>
            </div>

            <div class="bottom">

                <span class="left">&copy; 2006 <a href="index.html">Website.com</a>. Valid <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> &amp; <a href="http://validator.w3.org/check?uri=referer">XHTML</a>.</span>
                <span class="right">Template design by <a href="http://templates.arcsin.se">Arcsin</a></span>

                <!-- Footer with Copyright, Theme-Copyright, tpl-timeing and db-querycount // -->
                <p style="text-align:left;clear:both;margin-top:20px;" class="copyright">
                    <center>
                            <hr />
                            {include file='copyright.tpl'}
                            <p> {include file='server_stats.tpl'} </p>
                    </center>
                </p>

            <div class="clearer"><span></span></div>
            </div>
    </div>
</div>
</body>
</html>
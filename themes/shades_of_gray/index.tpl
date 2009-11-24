<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>

    {* display cache time as comment *}
    <!-- This Page was cached on {$smarty.now|dateformat}. -->

    {* Include the Clansuite Header Notice *}
    {include file='clansuite_header_notice.tpl'}

    {* Pagetitle *}
    <title>{$pagetitle} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>

    {* Dublin Core Metatags *}

    <link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
    <meta name="DC.Title" content="Clansuite - just an eSport CMS" />
    <meta name="DC.Creator" content="Jens-Andre Koch" />
    <meta name="DC.Date" content="20080101" />
    <meta name="DC.Identifier" content="http://www.clansuite.com/" />
    <meta name="DC.Subject" content="Subject" />
    <meta name="DC.Subject.Keyword " content="Subject.Keyword" />
    <meta name="DC.Subject.Keyword" content="Subject.Keyword" />
    <meta name="DC.Description" content="Description" />
    <meta name="DC.Publisher" content="Publisher" />
    <meta name="DC.Coverage" content="Coverage" />

    {* Metatags *}

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="{$meta.language}" />
    <meta name="author" content="{$meta.author}" />
    <meta http-equiv="reply-to" content="{$meta.email}" />
    <meta name="description" content="{$meta.description}" />
    <meta name="keywords" content="{$meta.keywords}" />
    <meta name="generator" content="Clansuite - just an eSports CMS" />

    {* Favicon *}

    <link rel="shortcut icon" href="{$www_root_themes_core}/images/clansuite_logos/Clansuite-Favicon-16.ico" />
    <link rel="icon" href="{$www_root_themes_core}/images/clansuite_logos/Clansuite-Favicon-16.ico" type="image/gif" />

    {* Clip *}

    <script src="{$www_root_themes_core}/javascript/clip.js" type="text/javascript"></script>

    {* Cascading Style Sheets *}

    <link rel="stylesheet" type="text/css" href="{$css}" />
    <link rel="alternate"  type="application/rss+xml" href="{$www_root}/cache/photo.rss" title="" id="gallery" />

</head>

<!-- default margin = default layout -->
<body style="margin: 0 12%;">

{* IE FIX! *}
<script type="text/javascript"></script>
<div class="container">

    <div class="header">
        <a href="index.html"><span>Clansuite in Shades of Gray</span></a>
    </div>

    <div class="stripes"><span></span></div>

    <div class="nav">
        <a href="index.php?mod=news">News</a>
        <a href="index.php?mod=Controlcenter">Controlcenter</a>
        <a href="index.php?mod=guestbook">Guestbook</a>
        <a href="index.php?mod=account">Account</a>
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
                    <dd><a class="button" href="index.php?mod=controlcenter">Controlcenter</a></dd>
                </dl>
                </div>


                <div style="margin-top: 10px">{load_module name="news" action="widget_latestnews" items="2"}</div>

                <div style="margin-top: 10px">{load_module name="statistics" action="widget_statistics"}</div>

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
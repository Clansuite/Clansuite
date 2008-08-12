{doc_info DOCTYPE=XHTML LEVEL=Transitional}
<base href="{$meta.domain}" />

{* everything in doc_raw is moved "as is" to header *}
{doc_raw}

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

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="{$meta.language}" />
<meta name="author" content="{$meta.author}" />
<meta http-equiv="reply-to" content="{$meta.email}" />
<meta name="description" content="{$meta.description}" />
<meta name="keywords" content="{$meta.keywords}" />

<link rel="shortcut icon" href="{$www_root_theme}/images/Clansuite-Favicon-16.ico" />
<link rel="icon" href="{$www_root_theme}/images/Clansuite-Favicon-16.ico" type="image/gif" />
<link rel="stylesheet" type="text/css" href="{$css}" />
<script  src="{$www_root_themes_core}/javascript/XulMenu.js" type="text/javascript"></script>
<script src="{$javascript}" language="javascript" type="text/javascript"></script>
<script src="{$www_root_themes_core}/javascript/clip.js" type="application/javascript"></script>
<script type="text/javascript" src="{$www_root_themes_core}/javascript/mootools/mootools.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}/javascript/mootools/mootools-more.js"></script>

<!--[if IE]>
<link rel="stylesheet" href="{$www_root_themes_core}/css/IEhack.css" type="text/css" />
<script type="application/javascript" src="{$www_root_themes_core}/javascript/catfish.js">
<![endif]-->

{if isset($additional_head)} {$additional_head} {/if}
{if isset($redirect)} {$redirect} {/if}

<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
<!-- page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}  -->
{/doc_raw}

{* BrowserCheck *}
 <h2 class="oops">{t}
	You shouldn't be able to read this, because this site uses complex stylesheets to
	display the information - your browser doesn't support these new standards. However, all
	is not lost, you can upgrade your browser absolutely free, so please

	UPGRADE NOW to a <a href="http://www.webstandards.org/upgrade/"
	title="Download a browser that complies with Web standards.">
	standards-compliant browser</a>. If you decide against doing so, then
	this and other similar sites will be lost to you. Remember...upgrading is free, and it
	enhances your view of the Web.{/t}
</h2>

{* Ajax Notification *}
<div id="notification" style="display: none;">
    <img src="{$www_root_themes_core}/images/ajax/2.gif" style="vertical-align: middle;" alt="Ajax Notification Image"/>
    &nbsp; Wait - while processing your request...
</div>

{* Header Table *}
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
    <td height="180" align="center">
        <img alt="Clansuite Header" src="{$www_root_theme}/images/clansuite-header.png" />
    </td>
</tr>
</table>

{* Preload XUL Menu Arrows *}
<script type="application/javascript">
//<![CDATA[
    var arrow1 = new Image(4, 7);
    arrow1.src = "{$www_root_theme}/images/arrow1.gif";
    var arrow2 = new Image(4, 7);
    arrow2.src = "{$www_root_theme}/images/arrow2.gif";
//]]>
</script>
{literal}
<script language="JavaScript" type="text/javascript">
    window.addEvent('domready', function() {
        var mySortables = new Sortables( {}, {
            revert: { duration: 500, transition: 'elastic:out' },
            opacity: '0.5',
            onComplete: function(it) {
                it.setStyle('left', '0');
                it.setStyle('top', '0');
            }
        });        
        $$('.block').each(function(item)
        {

            item.makeDraggable( {
                droppables: [$('left_block'), $('right_block')],
                
                onDrop: function(element, droppable){
                    if (droppable)
                    {
                        if( element.getParent().id != droppable.id )
                        { element.inject(droppable); }
                        element.setStyle('left', '0');
                        element.setStyle('top', '0');
                    }
                },
             
                onEnter: function(element, droppable){

                },
             
                onLeave: function(element, droppable){

                }
             
            });
            item.setStyle('position', 'relative');
            item.setStyle('left', '0');
            item.setStyle('top', '0');
            mySortables.addItems(item);
        });
        
    });
</script>{/literal}

{* Main Table *}
<table cellspacing="0" cellpadding="0" width="100%">
{* Header + Breadcrumbs *}
<tr class="tr_header">
    <td width="10">left Blocks</td>
    <td>{include file='tools/breadcrumbs.tpl'}</td>
    <td width="200">Right Blocks</td>
</tr>
{* Middle *}
<tr>
    {* Left Side *}
    <td class="cell1" width="200" height="300" id="left_block">
        <div class="block">{load_module name="menu"     action="widget_menu"}</div>
        <div class="block">{load_module name="tsviewer" action="widget_tsviewer"}</div>
    </td>
    
    {* Middle + Center = Main Content *}
    <td class="cell1">
        {$content}
    </td>

    {* Right Side *}
    <td class="cell1" width="200" id="right_block">
		   {*{load_module name="shoutbox" action="show"}*}
		   <div class="block">{load_module name="news"      action="widget_news" items="2"}</div>
		   <div class="block">{load_module name="wwwstats"  action="widget_wwwstats"}</div>
		   <div class="block">{load_module name="quotes"    action="widget_quotes"}</div>
		   <div class="block">{load_module name="users"     action="widget_lastregisteredusers"}</div>
    </td>
</tr>
</table>

<!-- Footer with Copyright, Theme-Copyright, tpl-timeing and db-querycount // -->
<p style="float:left; text-align:left;">
    <br/> Theme: {$smarty.session.user.theme} by {* {$theme_copyright} *}    
</p>
<p style="text-align:right;">
    <br /> {include file='server_stats.tpl'}
</p>

{$copyright}
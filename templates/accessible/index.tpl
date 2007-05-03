{"begin"|timemarker:"Rendertime:"}

{doc_info DOCTYPE=XHTML LEVEL=Transitional}
{* everything in doc_raw is moved "as is" to header *}
{doc_raw}

{* Dublin Core Metatags *}
<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
<meta name="DC.Title" content="Clansuite - just an eSport CMS" />
<meta name="DC.Creator" content="Florian Wolf, Jens-Andre Koch" />
<meta name="DC.Date" content="20070101" />
<meta name="DC.Identifier" content="http://www.clansuite.com/" />
<meta name="DC.Subject" content="Subject" />
<meta name="DC.Subject.Keyword " content="Subject.Keyword" />
<meta name="DC.Subject.Keyword" content="Subject.Keyword" />
<meta name="DC.Description" content="Description" />
<meta name="DC.Publisher" content="Publisher" />
<meta name="DC.Coverage" content="Coverage" />

<meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="content-language" content="{$meta.language}" />
<meta name="author" content="{$meta.author}" />
<meta http-equiv="reply-to" content="{$meta.email}" />
<meta name="description" content="{$meta.description}" />
<meta name="keywords" content="{$meta.keywords}" />

<link rel="shortcut icon" href="{$www_root_tpl}/images/favicon.ico" />
<link rel="icon" href="{$www_root_tpl}/images/animated_favicon.gif" type="image/gif" />

<link rel="stylesheet" type="text/css" href="{$css}" />
<script src="{$javascript}" type="text/javascript"></script>
{dhtml_calendar_init src="`$www_root_tpl_core`/javascript/jscalendar/calendar.js"
					 setup_src="`$www_root_tpl_core`/javascript/jscalendar/calendar-setup.js"
					 lang="`$www_root_tpl_core`/javascript/jscalendar/lang/calendar-de.js"
					 css="`$www_root_tpl_core`/javascript/jscalendar/calendar-win2k-1.css"}
{literal}
<!--[if lte IE 6]>
<style type="text/css">
table { width: 99%; }
</style>
<![endif]-->
<style type="text/css">
.special { background-color: #000; color: #fff; }
.calendar .inf { font-size: 80%; color: #444; }
  .calendar .wn { font-weight: bold; vertical-align: top; }
table { width: 99%; }
</style>
{/literal}

<script type="text/javascript" src="{$www_root_tpl_core}/javascript/prototype/prototype.js"></script>
<script type="text/javascript" src="{$www_root_tpl_core}/javascript/scriptaculous/scriptaculous.js"></script>

{if isset($additional_head)} {$additional_head} {/if}
{if isset($redirect)} {$redirect} {/if}

<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
<!--
page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}
-->
{/doc_raw}

<div id="header">
	<ul id="navigation">
		<li><a href="index.php?mod=news">News</a></li>
		<li><a href="index.php?mod=news&amp;action=archiv">Newsarchiv</a></li>
		<li><a href="index.php?mod=board">Board</a></li>
		<li><a href="index.php?mod=guestbook">Guestbook</a></li>
		<li><a href="index.php?mod=serverlist">Serverlist</a></li>
		<li><a href="index.php?mod=userslist">Userslist</a></li>
		<li><a href="index.php?mod=staticpages&amp;page=credits">Credits</a></li>
		<li><a href="index.php?mod=staticpages&amp;action=overview">Static Pages Overview</a></li>
	</ul>
</div>
{include file='tools/breadcrumbs.tpl'}
<div id="box">
	<div id="left">
		{mod name="account" func="login"}
		<br />
		<h3>{translate}Calendar{/translate}</h3>

		<div style="float: right; margin-top: 5px; margin-bottom: 1em; margin-right: 5px;" id="calendar-container"></div>

        <div id="dateInfotext">dateInfotext</div>
		<div id="dateinfo-div">dateinfo-div</div>
		  {literal}
    		<script type="application/javascript">
              // define the initial dateInfo array:
              var dateInfo = {
                "20070508" : "Mishoo's&nbsp;birthday",
                "20070510" : "foo2341234",
                "20070511" : "bar2341234",
                "20070518" : "25$412341234",
                "20070524" : "60$1234123",
                "20070624" : "60$1234123"
              };
            </script>

            <script type="application/javascript">
                  // <![CDATA[
            	  // get new dateInfos from database via ajax
            	  function getdateInfos(date)
            	  {
            	    // ok, now get the new data if month changed!
                    var y = date.getFullYear();
                    var m = date.getMonth();     // integer, 0..11

           	        var url = 'index.php?mod=calendar&amp;action=ajax_getdateinfos';
                    var pars = 'year=' + y + '&month=' + m;
                    var myAjax = new Ajax.Request(url,
                    {method: 'get', parameters: pars, onComplete: getResponse});
                  }

            	  // output dateInfo to id="dateInfo-output"
            	  function getResponse(oReq, oJSN)
            	  {
                        var data = eval(oReq.responseText);
                        $('dateinfo-div').innerHTML = '';
                        //populate the list
                        for (var i = 0; i < data.length; i++)
                        {
                            $('dateInfo-output').innerHTML += data[i]+', ';
                        }
                    }


                  // output Date + Infotext to separte field
                  // to the day-field
                  // or to an tooltip
                  function outputDateandInfotext(calendar)
                  {
                    var preview = document.getElementById("dateInfotext");
                    if (preview) {
                      preview.innerHTML = calendar.date.print('%a, %b %d, %Y');
                    }
                  }


            	   // jscalendar-params[dateText]
                   // this is called for every day (d) to assign a Text
                   // if d == 1 call ajax update for new dateInfo-array
                   function getDateText(date, d)
                   {
                     // ajax call
                     if (d == 1)
                     {
                       getdateInfos(date);

                     }

                    // assign text to all day divs
                    var inf = dateInfo[date.print("%Y%m%d")];
                    if (!inf) {
                      return d + "<div class='inf'>&nbsp;<\/div>";
                    } else {
                      return d + "<div class='inf'>" + inf +"<\/div>";
                    }

                   };

             	  function flatCallback(calendar)
             	  {
             	    if (calendar.dateClicked) {
             	      outputDateandInfotext(calendar);

             	      // windows.status changer
             	      window.status = "Selected: " + calendar.date;
             	      var inf = dateInfo[calendar.date.print("%Y%m%d")];
             	      if (inf) {
            	        window.status += ".  Additional info: " + inf;
             	      }
             	    }
             	  };

             	  function dateIsSpecial(year, month, day) {
                    var combined = year + month + day;
                    var m = dateInfo[year+month+day];
                    if (m == undefined) { return false; } else { return true; }
                  };

             	  // function to mark a Day as Special
                  // .special in css needed
                  function ourDateStatusFunc(date, y, m, d)
                  {
                    if (dateIsSpecial(y, m, d))
                    {
                    return "special";
                    }
                    else
                    {
                    return false;
                    }

                  };
                  // ]]>
            </script>
        {/literal}
		{dhtml_calendar flat="calendar-container"
		                dateText=getDateText
		                flatCallback=flatCallback
		                firstDay="1"}
		</div>
	<div id="right">
		{mod name="shoutbox" func="show"}
		<h3>{translate}Statistics{/translate}</h3>
		<ul id="counter">
			<li>
				<strong>Online:</strong>{* {$stats|@var_dump}  *} {$stats.online}
				<ul>
					<li><strong>Users:</strong> {$stats.authed_users}</li>
					<li><strong>Guests:</strong> {$stats.guest_users}</li>
				</ul>
				<strong>Who's online?</strong>
				{if $stats.authed_users > 1}
				<ul>
					{foreach item=who from=$stats.whoisonline}
					<li><a href="index.php?={$who.user_id}">{$who.nick} @ {$who.session_where}</a></li>
					{/foreach}
				</ul>
				{elseif $stats.authed_users == 1}
				<ul>
					<li><a href="index.php?={$stats.whoisonline.0.user_id}">{$stats.whoisonline.0.nick}</a> @ {$stats.whoisonline.0.session_where}</li>
				</ul>
				{/if}
			</li>
			<li><strong>Today:</strong> {$stats.today_impressions}</li>
			<li><strong>Yesterday:</strong> {$stats.yesterday_impressions}</li>
			<li><strong>Month:</strong> {$stats.month_impressions}</li>
			<li><strong>This Page:</strong> {$stats.page_impressions}</li>
			<li><strong>Total Impressions:</strong> {$stats.all_impressions}</li>
		</ul>
	</div>
	<div id="content">
		{$content}
	</div>
</div>
<div id="footer">
<!-- Footer with Copyright, Theme-Copyright, tpl-timeing and db-querycount // -->
	{$copyright}<br />
	Theme: {* {$theme-copyright} *}
	<br/>
	{include file='server_stats.tpl'}
</div>
{* Ajax Notification *}
<div id="notification" style="vertical-align:middle;display:none;z-index:99;">
    <img src="{$www_root_tpl_core}/images/ajax/2.gif" alt="Ajax Notification Image" />
    &nbsp; Wait - while processing your request...
</div>
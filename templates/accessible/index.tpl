{* New timemarker for Rendering this Template is set *}
{"begin"|timemarker:"Rendertime:"}

{* Document-Type and Level is set *}
{doc_info DOCTYPE=XHTML LEVEL=Transitional}

{* doc_raw movement! -> everything in doc_raw is moved "as is" to header *}
{doc_raw}

{* Dublin Core Metatags *}
<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
<meta name="DC.Title" content="Clansuite - just an eSport CMS" />
<meta name="DC.Creator" content="Jens-Andre Koch, Florian Wolf" />
<meta name="DC.Date" content="20070101" />
<meta name="DC.Identifier" content="http://www.clansuite.com/" />
<meta name="DC.Subject" content="Subject" />
<meta name="DC.Subject.Keyword " content="Subject.Keyword" />
<meta name="DC.Subject.Keyword" content="Subject.Keyword" />
<meta name="DC.Description" content="Description" />
<meta name="DC.Publisher" content="Publisher" />
<meta name="DC.Coverage" content="Coverage" />

{* Standard Metatags *}
<meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="content-language" content="{$meta.language}" />
<meta name="author" content="{$meta.author}" />
<meta http-equiv="reply-to" content="{$meta.email}" />
<meta name="description" content="{$meta.description}" />
<meta name="keywords" content="{$meta.keywords}" />

{* Favicon Include *}
<link rel="shortcut icon" href="{$www_root_tpl}/images/favicon.ico" />
<link rel="icon" href="{$www_root_tpl}/images/animated_favicon.gif" type="image/gif" />

{* Inserts from index.php *}
<link rel="stylesheet" type="text/css" href="{$css}" />
<script src="{$javascript}" type="application/javascript"></script>
{if isset($additional_head)} {$additional_head} {/if}
{if isset($redirect)} {$redirect} {/if}

{dhtml_calendar_init src="`$www_root_tpl_core`/javascript/jscalendar/calendar.js"
					 setup_src="`$www_root_tpl_core`/javascript/jscalendar/calendar-setup.js"
					 lang="`$www_root_tpl_core`/javascript/jscalendar/lang/calendar-de.js"
					 css="`$www_root_tpl_core`/javascript/jscalendar/calendar-accessible.css"}

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

<script type="application/javascript" src="{$www_root_tpl_core}/javascript/prototype/prototype.js"></script>
<script type="application/javascript" src="{$www_root_tpl_core}/javascript/scriptaculous/scriptaculous.js"></script>

{* set title - and apply -breadcrumb title="1"- to it *}
<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
{* display cache time as comment *}
<!-- page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"} -->
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
{* Breadcrumbs Navigation *}
{include file='tools/breadcrumbs.tpl'}
<div id="box">
	<div id="left">
	    {* Modulcall name="modulname" func="function" *}
		{mod name="account" func="login"}
		<br />
		<h3>{translate}Calendar{/translate}</h3>

		<div style="float: right; margin-top: 5px; margin-bottom: 1em; margin-right: 5px;" id="calendar-container"></div>

        <div id="dateInfotext">dateInfotext</div>
		<div id="test-div">dateinfo-div</div>
		<ul id="Liste"></ul>
		  {literal}
    		<script type="application/javascript">
                  // <![CDATA[
                        function dump(arr,level) {
                        var dumped_text = "";
                        if(!level) level = 0;

                        //The padding given at the beginning of the line.
                        var level_padding = "";
                        for(var j=0;j<level+1;j++) level_padding += "    ";

                        if(typeof(arr) == 'object') { //Array/Hashes/Objects
                         for(var item in arr) {
                          var value = arr[item];

                          if(typeof(value) == 'object') { //If it is an array,
                           dumped_text += level_padding + "'" + item + "' ...\n";
                           dumped_text += dump(value,level+1);
                          } else {
                           dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
                          }
                         }
                        } else { //Stings/Chars/Numbers etc.
                         dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
                        }
                        return dumped_text;
                        }
              // define the initial dateInfo array:
              dataInfos = Class.create();
                  // ]]>
            </script>

            <script type="application/javascript">
                  // <![CDATA[

            	  // get new dateInfos from database via ajax
            	  function getdateInfos(date)
            	  {
            	    // ok, now get the new data if month changed!
                    var y = date.getFullYear();
                    var m = date.getMonth()+1; // WATCH OUT internally jscalendar uses integer, 0..11
                                               // WE USE JANUARY = 1 ! so we add +1 to all months
                                               // for php processing

           	        var url = 'index.php?mod=calendar&action=ajax_getdateinfos';
                    var pars = '&year=' + y + '&month=' + m;

                    // for xml return testing (calling without output=xml returns json)
                    // var pars = '&year=' + y + '&month=' + m +'&output=xml';

                    var myAjax = new Ajax.Request(url,
                    { method: 'get', parameters: pars, onComplete: getResponse }
                    );
                  };

            	  function getResponse(req)
            	  {
            	    // debug output to id
            	    // document.getElementById('test-div').innerHTML = req.responseText;

                    dataInfos = eval('(' + req.responseText + ')');

                    var o="";
                    for(eventDatum in dataInfos) {
                    	o+=eventDatum+"\n";
                    	for(eventNummer in dataInfos[eventDatum]) {
                    		o+=eventNummer+"\n";
                    		for(eigenschaft in dataInfos[eventDatum][eventNummer]) {
                    			o+=eigenschaft+": "+dataInfos[eventDatum][eventNummer][eigenschaft]+"\n";
                    		}
                    		o+="\n";
                    	}
                     }
                     alert(dump(dataInfos));
                  }

                  // output Date + Infotext to separte field
                  // to the day-field
                  // or to an tooltip
                  function outputDateandInfotext(calendar)
                  {
                      var preview = document.getElementById("dateInfotext");
                      if (preview)
                      {
                        preview.innerHTML = calendar.date.print('%a, %b %d, %Y');
                      }
                  };

            	   // jscalendar-params[dateText]
                   // this is called for every day (d) to assign a Text
                   // if d == 1 call ajax update for new dateInfo-array
                   function getDateText(date, d)
                   {
                     // ajax call
                     if (d == 1)
                     {
                        getdateInfos(date);
                        alert(dump(dataInfos));
                       //alert(dataInfos);
                     };

                     // assign text to all day divs
                     if( dataInfos[date.print("%Y%m%d")] )
                        var inf = dataInfos[date.print("%Y%m%d")]["1"]["eventname"];
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
             	      var inf = dataInfos[calendar.date.print("%Y%m%d")];
             	      if (inf) {
            	        window.status += ".  Additional info: " + inf;
             	      }
             	    }
             	  };

                  //
             	  function dateIsSpecial(year, month, day)
                  {
                    var combined = year + month + day;
                    var m = dataInfos[combined];
                    if (m == undefined) { return false; } else { return true; }
                  };

                  // returns true if dateIsSpecial
                  function dateIsSpecial(year, month, day)
                  {
                    // check array -> year
                    var y = SPECIAL_DAYS[year]; if (!y) return false;
                    // check array -> year+month
                    var m = SPECIAL_DAYS[year][month]; if (!m) return false;
                    // check every day in month
                    for (var i in m) if (m[i] == day) return true;
                    return false;
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
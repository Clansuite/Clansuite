/* set up browser checks */

// check browsers
var ua		= navigator.userAgent;
var opera	= /opera [56789]|opera\/[56789]/i.test(ua);
var ie		= !opera && /msie [56789]/i.test(ua);		// preventing opera to be identified as ie
var moz		= !opera && /mozilla\/[56789]/i.test(ua);	// preventing opera to be identified as mz
/* end browser checks */
/* Do includes */

if (window.pathToRoot == null)
	pathToRoot = "/";

if (ie)
	document.write('<script type="text/javascript" src="' + pathToRoot + 'dhtml/cssexpr/cssexpr.js"><\/script>');
document.write('<link type="text/css" rel="StyleSheet" href="' + pathToRoot + 'webfxlayout.css">' +
	'<link type="text/css" rel="StyleSheet" href="' + pathToRoot + 'dhtml/xmenu/xmenu.css"><\/script>' +
	'<script type="text/javascript" src="' + pathToRoot + 'dhtml/xmenu/xmenu.js"><\/script>' +
	'<script type="text/javascript" src="' + pathToRoot + 'index.pl?action=menu"><\/script>');

// later
// webfxMenuImagePath = pathToRoot + "images/";


/* end includes */



webfxLayout = {
	writeTitle		:	function (s, s2) {
		if (s2 == null)
			s2 = "WebFX - What you never thought possible!";

		document.write("<div id='webfx-title-background'></div>" +
			"<h1 id='webfx-title'>" + s + "</h1>" +
			"<span id='webfx-sub-title'>" + s2 + "</span>");
	},
	writeMainTitle	:	function () {
		this.writeTitle("WebFX", "What you never thought possible!");
	},
	writeTopMenuBar		:	function () {
		document.write("<div id='webfx-menu-bar-1'></div>" +
			"<div id='webfx-menu-bar-2'></div>" +
			"<div id='webfx-menu-bar'>");// div is closed in writeBottomMenuBar
	},
	writeBottomMenuBar	:	function () {
		document.write("</div><div id='webfx-menu-bar-3'></div>" +
			"<div id='webfx-menu-bar-4'></div><div id='webfx-menu-bar-5'></div>");
	},
	writeMenu			:	function () {
		if (ie || moz || opera) {
			if (ie)
				simplifyCSSExpression();
			webfxMenuImagePath = pathToRoot + "images/";
			this.writeTopMenuBar();
			document.write(webfxMenuBar);
			this.writeBottomMenuBar();
		}
		else {
			document.write(
				"<div id='webfx-menu-bar'>&nbsp;" +
				"<a href='/'>Home</a> " +
				"<a href='" + pathToRoot + "?action=sitemap'>Site Map</a> " +
				"<a href='/webboard'>WebFX Webboard</a> " +
				"</div>" +

				"<p class='warning' style='width: 500px'>" +
				"This site will look much better in a browser that supports " +
				"<a href='http://www.webstandards.org/upgrade/' " +
				"title='Download a browser that complies with Web standards.'>" +
				"web standards</a>, " +
				"but it is accessible to any browser or Internet device." +
				"</p>"
			);
		}
	},
	writeDesignedByEdger	:	function () {
		if (ie && document.body.currentStyle && document.body.currentStyle.writingMode != null)
			// IE55+
			document.write("<div id='webfx-about'>Page designed and maintained by " +
					"<a href='/contact.html#erik'>Erik Arvidsson</a> &amp; " +
					"<a href='/contact.html#emil'>Emil A Eklund</a>.</div>");
	},

	aprilFool:	function ()
	{
		if ( !ie )
			return;
		if (document.location.pathname != '/') { return; }
		if (document.location.search != '') { return; }
		var d = new Date;
		var a1st = new Date(0);
		a1st.setFullYear( d.getFullYear() );
		a1st.setMonth( 3 );
		a1st.setDate( 1 );
		if ( d > a1st && d - a1st <= 24 * 3600 * 1000 )
		{
			var res = confirm( "WebFX no longer supports Internet Explorer.\n\nPlease download " +
					 "a real browser like Mozilla Firefox, Opera 7+ or Safari." );
			if ( !res )
				res = !confirm( "Are you sure you want to continue using Internet Explorer?\n\n" +
						"Your experience will be inferior!" )
			if ( res )
				document.location = "http://mozilla.org/products/firefox/";
		}
	}

};


webfxLayout.aprilFool();

if (ie && window.attachEvent) {
	window.attachEvent("onload", function () {
		var scrollBorderColor	=	"rgb(120,172,255)";
		var scrollFaceColor		=	"rgb(234,242,255)";
		with (document.body.style) {
			scrollbarDarkShadowColor	=	scrollBorderColor;
			scrollbar3dLightColor		=	scrollBorderColor;
			scrollbarArrowColor			=	"black";
			scrollbarBaseColor			=	scrollFaceColor;
			scrollbarFaceColor			=	scrollFaceColor;
			scrollbarHighlightColor		=	scrollFaceColor;
			scrollbarShadowColor		=	scrollFaceColor;
			scrollbarTrackColor			=	"white";
		}
	});
}

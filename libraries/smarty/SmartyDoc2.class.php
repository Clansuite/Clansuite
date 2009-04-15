<?php

/**
 * Project:     SmartyDoc2 - a Plugin for Smarty
 * File:        SmartyDoc2.class.php
 *
 * SmartyDocB helps to create a qualified document using
 * previously collected user information.
 *
 * Requires:    PHP5 and Smarty 2.6.10+
 *
 * License:
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * For questions, help, comments, discussion, etc., please use the
 * project forums at sourceforge:
 * @link http://sourceforge.net/projects/smartydocb/
 *
 * @copyright   vain 2007
 *              brettz9 2006-2008
 *              brainpower, boots 2002-2007
 * @author	    brettz9 <brettz9@yahoo.com>
 * @author      vain    <jakoch@web.de>
 * @author	    boots   <jayboots@yahoo.com>
 * @author	    tdmme   <no2spam@chello.nl>
 * @package     SmartyDocB
 * @license	    LGPL 2.1 license.txt
 *
 * @version     SVN: $Id$
 */

/**
 *  Require the Smarty_Compiler.class
 *  Require the wtplfileaccs Compiler Patch
 */
require('Smarty_Compiler.class.php');
require('Smarty_Compiler_wtplfileaccs.class.php');

/**
 * Class Render_SmartyDoc
 *
 * Main class of the SmartyDocB package extending the functionality of Smarty
 * @package    SmartyDocB
 * @subpackage Render_SmartyDoc
 */

class Render_SmartyDoc extends Smarty
{
    /**
     * Gives file locations when XSL transforms fail
     *
     * @var bool
     */
	public $debug = true;

	/**
	 * If true, will add plain html, head, and body tags automatically if not
	 * specified by doc_info (will also not add if a plain XML document); leaving
	 * it false allows one to manually specify these in the document
	 *
	 * @var bool
	 */
	public $add_openclose = false;

	/**
	 * If true, will override XHTML detection of headers in the event of having
	 * XHTML 1.1 or higher doc_info's
	 *
	 * @var bool
	 */
	public $xhtmldebug = false;
	/**
	 * If true, will add relevant XML headers, and allow stylesheets to be added
	 * (with appropriate attributes) as xsl-stylesheet's rather than link tags (if
	 * not using XHTML, be sure to change add_openclose also to false, so html,
	 * head, and body tags won't be added automatically); if true, be wary of
	 * inline scripts/styles and even other elements like titles getting distorted
	 * in Firefox (as it will serve application/xhtml+xml as it is supposed to)
	 *
	 * @var bool
	 */
	public $xml_plain = false;
	/**
	 * @var bool
	 */
	public $xml_auto_declare = true;

	/**
	 *	This will assume that your XML document should have a standalone
	 *  attribute set to "yes". This can slightly add efficiency to a validating XML
	 *  parser (assuming the benefit is not lost by the extra code required here!)
	 *  as it is not troubled to look for external DTDs. However, don't mark this
	 *  true if you are manually adding external (non-public) DTDs (at least if they
	 *  will change the output of your document, as is likely). Note that even if
	 *  you have this true, the code checks to see if you have any dtd docinfos or
	 *  dtd docraws before adding it (though sometimes the latter are admittedly
	 *  used for "PUBLIC" identifiers which can sometimes get by with standalone yes
	 *  if the net does not need to be accessed).
	 *
	 * @var bool
	 */
	public $assume_standalone = false;

	/**
	 *  If wanting to specify an XML http accept type; preferable to leave false
	 *  where document to be available for human reading: http://www.rfc-
	 *  editor.org/rfc/rfc3023.txt (or set to 'XHTML' if not using a doc_info to do
	 *  so, in order that the code will auto-detect whether application/xhtml+xml
	 *  type, etc. can be served); however, "XML in a Nutshell" recommends using
	 *  application/xml in most cases given that text/xml is supposed to only be
	 *  for documents limited to ASCII.
	 *
	 * @var bool
	 */
	public $application_xml = true;

	/**
	 * If set to true, this will perform a DOM validation (according to whatever
	 * DTD you have in your document) and output the results (if negative) in an array.
	 * Can also be forced to true (even if false here) by setting the following in a template:
	 * {assign var=validateon value=true}
	 * @var bool
	 */
	public $validate = false;
	/**
	 * Process the output filter regardless of whether doc_raws or docinfos
	 * exist in the document (e.g., if taking advantage of SmartyDocB features
	 * strictly through the PHP interface)
	 * Need to fix to use with dtds and not just xsds to allow http, etc. to show in the page HTML code?
	 * @todo where does this belong to?
	 */
	public $use_auto = true; //

	/**
     *	This is used to force the header to text/html even if the document
     *  originally being served was XML. Used when transforming XML into HTML
     *	(Explorer doesn't seem to handle this well otherwise--whether a client-side
     *	or server-side transformation). // Still working on this and not sure I need
     *	this after all (scratches head)
	 */
	public $force_html = false; //
	public $force_css = false;

	/**
	 * Set the Smarty tags to invoke the main functions of SmartyDocB
	 *
	 *  Due to these variables being also used found in the prefilter and
	 *  overridden compiler class, one cannot access these variables meaningfully
	 *  after construction, so changes would need to be made here.
	 *
	 *  You can use the old aliases {doc_raw} and {doc_info} by setting:
	 *  $doc_rawalias = 'doc_raw'; $doc_infoalias = 'doc_info';
	 *
	 * @var    string
	 * @see    __construct()
	 */
	public $doc_rawalias = 'moveto';
	public $doc_infoalias = 'info';

	// Don't put slashes at the end of these
	public $site_root_public = '';
	public $site_root_hidden = '';
	// The following need slashes at the beginning
	public $dr_css_file_src = '/dr_mainstyles.css';

	public $dr_code_file_src = '/dr_mainscripts.js';
	public $dr_xsl_file_src = '/dr_mainxsl.xsl';
	public $dr_xsd_file_src = ''; // Set in constructor via set_rootnode
	public $dr_dtd_file_src = ''; // Set in constructor via set_rootnode
	public $dr_notes_file_src = '/dr_mainnotes.txt';

	public $rootnode = 'html';
	public $xmlns = ''; // 'http://www.w3.org/1999/xhtml'; // To be changed for plain XML

	// These are set via the constructor
	public $dr_css_file;
	public $dr_code_file;
	public $dr_xsl_file;
	public $site_public_root_dir;

	public $root_nns_xsd; // Can specify if adding an XSD noNamespaceSchemaLocation attribute (or do it instead via the template, doc_raws, etc.)

	public $xsd_ns = 'http://www.w3.org/2001/XMLSchema';
	public $xsd_target_ns = 'http://mysites-target-ns.com';
	public $xsd_xmlns = 'http://mysites-ns.com';
	public $xsd_formdefault = 'qualified';
	public $xsd_xml_version = '1.0';

	public $xsd_prefixed;
	public $xsd_postfixed = "\n</xs:schema>";
	public $entity_prefixed; // These may also be used in external dtd subsets/external parameter entity references: http://www.w3.org/TR/REC-xml/#NT-extSubset

	public $xsl_prefixed; // Set in constructor

	public $xsl_postfixed = "\n</xsl:stylesheet>";

	public $dr_notes_pre = '<!-- Notes for this file are taken at: "';
	public $dr_notes_post = " -->\n";
	public $dr_dtd_pre; // Must be set later
	public $dr_code_pre; // Must be set later
	public $dr_code_post = "></script>\n";
	public $dr_css_pre; // Must be set later
	public $dr_css_post = " />\n";
	public $dr_doctype_postpre; // Set later
	public $dr_pi_pre; // Set later
	public $dr_pi_post = '?>';
	public $dr_comments_pre = '<!-- ';
	public $dr_comments_postpre = '';
	public $dr_comments_post = " -->\n";
	public $dr_style_pre; // Set later

	public $dr_doctype_pre;
	public $dr_doctype_post;

	public $dr_head_post;

	public $dr_style_postpre = ">\n\t<!--/*--><![CDATA[/*><!--*/\n";
	public $dr_style_post = "\t/*]]>*/--></style>\n";
	public $dr_style_postpre_xml = ">\n\t<![CDATA[\n";
	public $dr_style_post_xml = "\t]]></style>\n";


	public $dr_script_pre; // Set later
	public $dr_script_postpre = ">\n\t<!--//--><![CDATA[//><!--\n";
	public $dr_script_post = "\t//--><!]]></script>\n";
	public $dr_script_postpre_xml = ">\n\t<![CDATA[\n";
	public $dr_script_post_xml = "\t]]></script>\n";

	public $xform_all = false; // Set to true if want to always perform server-side transforms of XSL, even if the attribute is not specified in the template code
	public $xform_none = false; // Perform no server-side transforms (besides those using the xsl/xml functions/modifiers which are transformed of necessity)
	public $xform_get = true; // If set to true, this will allow server-side XSL to be avoided in favor of client-side (this would allow you in debugging (or if you wanted to share your raw XML and XSL) to view the prexformed data
	public $xform_get_url = 'noxform'; // Change this if you want to enable the get feature for client-side transforms, but want a nonstandard name so that other users cannot (without guessing your name) get access to your raw XML or XSL

	public $common_XHTML_probs = true; // Fixes some common XHTML parsing problems for XSL server-side xform
	public $tidy_on = false;
	public $tidy_for_xslt = true; // If you want to turn Tidy off normally, but be extra sure when doing XSLT transforms (which depend on good XML), you can set $tidy_on to false but have this be true.

	public $strip_whitespace = true; // Will strip excess whitespace if Tidy is not used (Tidy will strip anyhow)
	public $whitespace_get = true; // Specifies whether one can use a $_GET URL to turn comments on
	public $whitespace_get_url = 'stripws';
	public $strip_all_whitespace = false; // You may want to set this to true as you can still allow whitespace to be optionally seen with $whitespace_get and specify a $whitespace_get_url to determine the URL call to control whitespace from the site (and optionally have $show_whitespace_comments turned on so that others visiting your site can optionally view these options (listed within comments at the top of the HTML source code) to have whitespace added back or removed); note however that this removes all extra whitespace regardless of pre blocks.
	public $show_whitespace_comments = true; // Note that if you don't want the feature on at all, you have to set $whitespace_get to false--otherwise the latter must be turned on for the comments to show
	public $strip_all_ws_xhtmlbasic = true;
	public $whitespace_comments = true; // Will put comments at the top of the document (after any XML declaration) to let people know that they can see the whitespace added back or taken away with the right GET url)
	private $whitespace_comment_type;

//	public $xhtmlbasic_xsl = 'http://www.w3.org/2003/04/xhtml1ToBasic.xsl'; // Infinitely faster to copy this to a local file location!

	public $xhtmlbasic_xsl; // Infinitely faster to copy this to a local file location!


	public $xformtobasic_get = true; // Allows transformation into XHTML Basic via a GET request
	public $xformtobasic_url = 'xhtmlbasic1'; // If $xformtobasic_get is true, defines the GET Request name that will trigger the transform

	public $auto_xform_mobile = true; // Automatically transform file into XHTML Basic when detecting mobile user agent devices

	public $Browscap_file; // = '/home/user/Browscap/Browscap.php5';
	public $Browscap_cache_dir; // = '/home/user/Browscap/cache';

	// See also the prefilter for "safe" abbreviations (e.g., search down for [=  )
	// The following should only be changed carefully as their sequence is important (see the prefilter)
	// Note that if the tagc items are changed to single <>'s, any HTML you have will be run through this function--this may be an advantage if you want your styles first transferred into your main site's stylesheet (referencing an id unless you prepend a "e" and or "cl" prefix to the style attribute in which case it may instead reference an element or class (an auto-created class, that is, if none exists)); however, it is not a good idea to use that approach normally, it could also slow things down quite a bit, especially if you have a lot of tags to go through! If you do change it to single <>'s, you will probably also want to keep/set "styles_to_css" below to true as well so that the styles will go into an external file
	public $left_tagdelim = '{{';
	public $right_tagdelim = '}}';
	public $right_tagdelim2 = '/}}'; // Allow this to be in the code too (if one wants it in fact to look like the self-closing tag it represents
	public $left_tagcdelim = '<<';
	public $right_tagcdelim = '>>';
	public $left_tagcclose = '<</'; // This will just be the same as </...>; it is simply to make the template code look consistent
	public $right_tagcclose = '>>';
//	public $left_tagclose = '{{/';  // Didn't really need these (unless making them as opening rather than self-closing tags--but opening would probably be rare)
//	public $right_tagclose = '}}';
	public $styles_to_css = false; // Will convert all tag function uses of style (e.g., istyle) into the css equivalent (especially useful when tag function set to replace all HTML tags, as "style" (or subsequent tweaks such as "clstyle", etc.) will be shuffled off into an external file (you may also wish to consider changing the tagc delimiters above to single <>'s as described in a note above.

	/**
	 * Variable is set via set_rootnode (set via the constructor)
	 * @access protected
	 * @see set_rootnode()
	 */
	protected $dr_xsd_file;

	/**
	 * Variable is set via set_rootnode (set via the constructor)
	 * @access protected
	 * @see set_rootnode()
	 */
	protected $dr_dtd_file;

	public $visible_notes = true; // Be aware that if this is set to true, the URL of your notes will be referenced within the code (albeit as a (publicly visible) comment) unless you add a "hide" attribute (e.g., as "true") to the specific note you don't even want referenced in the notes; you can still keep/set the directory to hidden (see below) so the file is not publicly accessible.
	public $notes_in_hiddendir = true; // If this is false, notes and comments (if they are set to visible--see above) will write to and reference your public url for notes (as opposed to the hidden url which might be hidden outside of a publicly-viewable directory); you could write to a hidden directory yet reference the file in the public code (or have nothing in the public code at all)
	public $encoding = 'UTF-8'; // Used for generating headers
	public $HTTP_ACCEPT = 'text/html';

	public $rewrite_docraw_on = true; // This should be turned off (to false) to gain performance if code within doc_raw (css or code) is not changing but is required for the styles/scripts/etc. to be written (unless turned on by setting the rewrite_docraw_get to true and calling it via a GET request!
	public $rewrite_docraw_get = true; // Lets a URL be specified to choose the value of $rewrite_docraw_on
	public $rewrite_docraw_get_url = 'rewr'; // If you have the rewrite_docraw_get set to 'true', you may want to change this so that other visitors to your site cannot force a rewrite of your CSS on each load with the GET
	public $gzip_output = true; // Tries to gzip the main output if the browser will accept it (and if not already on via the Zlib extension)

	// Note the following will not uncomment the comments within files which are needed to identify which blocks to rewrite on subsequent calls
	public $external_comments = false; // Indicates template source in comments within the main document (surrounding the tag referencing the external content or tags without specific head content)
	public $head_comments = true; // Indicates template source in comments within the main document (surrounding all of the doc_raws targeted for that block)
	public $print_main_comments = false; // Setting this to false will cause individual doc_raw items targeted for the head not to possess comments
	public $comments = true; // Controls placement of external comments, head comments, and main comments (this should be set to false unless you want to turn all on); this is most useful to have on when debugging or when changing CSS
	public $comments_get = true; // Specifies whether one can use a $_GET URL to turn comments on
	public $comments_get_url = 'commentson';
	public $strip_xml_decl = false; // This can remove the resulting XML Declaration from XSL-transformed XML
	public $id_prefix = 'id'; // Used as the base for non-labeled id's
	public $class_prefix = 'cl'; // distinct from Tidy's default 'c'

	public $param_ent_name = 'ents'; // Used for naming the parameter entity that includes entities submitted via a doctype with a file attribute (list of pipe-delimited files)
	public $entity_replace = true; // Replace entities within document and added via doc_raw 'dtd' or 'entity' with their replacement text (though the entities will still be declared)
	public $noextparams = true; // This could be set to false if more browsers start supporting external (parameter) entity references (currently Firefox has a bug even printing one let alone using it) or if one simply wanted to generate the XML for non-web purposes; server-side replacement can still take place if this is set to true.
	public $entity_files = 'main_en.ent|main_zh.ent|main_fr.ent'; // By choosing, 'defaults' as a file type, you will automatically use this file for entities; note, however, that this script will overwrite anything in this file (unlike the doc_raws usually do)--Fix: this should probably be fixed in a future version; note this should be pipe-delimited in the order you want your entity doc_raws(labeled with file=main) to include translations after the entity alias name.
	public $getentparams = 'langu en zh fr'; // If used (it can also be overridden by the get attribute of an entity doc_raw), this should be a space-delimited string, beginning with the $_GET parameter and followed by the language abbreviations the $_GET call will require for a given language--e.g., "language en fr de" would allow "?language=fr". This must be used with a tab-delimited entity doc_raw of language values (the first column of which contains the entity alias used in the document and the other columns  representing a language translation--which should follow the same order of languages as that in this $getentparams after the $_GET parameter name)
	private $extra_ent_comments;
	private $entity_file_toget;

    public $nexthide = null;

//	private $keepapptype; // Set in getdoctypesignature
	private $file_attribs = array('href', 'src', 'file', 'family'); // Brett added the last two to the condition (those to be urlencoded) // Not xmlns, xsi__noNamespaceSchemaLocation, xsi__schemaLocation ?
	private $temp_content = '';
	private $curr_include_tpl = array();
	private static $unique = 0; // This doesn't need to be reset specific to each instance
	private static $class_ctr = 0; // Used by "tag" function
	private static $id_ctr = 0; // Used by "tag" function
	private $internaldoctype = false; // Used by getdoctypesignature
	private $xhtmlbasic = false; // Used by xhtmlbasic_detect
	/**
     * XInclude Variables as used by smarty_block_xinclude
     * Note that these are not implemented yet in browsers
     *
     * @var string
     * @see smarty_block_xinclude()
     */
	public  $xinclude_ns = 'http://www.w3.org/2001/XInclude';
	/**
	 * @var	bool switches xinclude on/off
     */
    private $xincludeset = false;

	/**
	 * This denotes which XHTML (or other prespecified DTD's here) are modular
	 * (i.e., which can accept internal doctypes)
	 *
	 * @var array $modular_xml denotes which XHTML Doctypes can accept internal doctypes
	 * @access private
	 * @static
	 * @see getDoctypeSignature()
	 */
	private static $modular_xml = array('1.1', '1.1+', 'Basic1.1');

	/**
     * DOCTYPES Array
     *
     * @var array $DTDS contains serveral DOCTYPE definitions
     * @access protected
     */

	protected $DTDS = array(
		'HTML' => array(
		'Strict' => array(
			'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">'
			)
		, 'Transitional' => array(
			'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'
			)
		, 'Frameset' => array(
			'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">'
			)
		)
	, 'XHTML' => array(
		'Strict' => array(
			'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'
			)
		, 'Transitional' => array(
			'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
			)
		, 'Frameset' => array(
			'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">'
			)
		, '1.1' => array(
			'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'
			)
		, '1.1+' => array(
			'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0 plus SVG 1.1//EN" "http://www.w3.org/2002/04/xhtml-math-svg/xhtml-math-svg.dtd">'
			)
		, 'Basic1.1' => array(
			'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">'
			)
		)
	);

	/**
     * doc_info_types array
     *
     * Note that most of the attributes below should be legitimate XHTML attributes
     * and most of the keys/elements (besides root and comments/notes) should be
     * the same name as legitimate XHTML elements or tags (e.g., 'script' or
     * 'doctype') or, if for an external doc_raw equivalent, a pseudonym (e.g.,
     * 'code' or 'dtd') (Note: 'xsl' and 'xsd' have no internal doc_raw
     * equivalents, so they are only under the pseudonym and this implementation
     * only has one 'entity' option (though it can be used internally or
     * externally). Also, note that 'comments' is the internal name and 'notes' the
     * external name, though these have no XHTML element equivalent--they are
     * simply comments or referenced text)
     *
     * Note that "rel" is listed in the exclude list of CSS so that it will be
     * excluded when serving genuine XML as application/xhtml+xml (i.e., non-
     * Explorer), but it is in the include list so that, although xml plain mode
     * may be on for some browsers, when it is off, the rel attribute will be
     * included.
     *
     * These are arranged in rough order (besides CSS which is in place for XML,
     * but may appear around style tags if not)
     *
     * @var array $doc_info_types
     * @access protected
     */
	protected $doc_info_types = array(
		'bom' => array(
			'renameto' => null
			, 'optional' => array()
			, 'meta_attribs' => array('tplorig')
			, 'occur_once' => true
		)
		, 'xml' => array(
			'renameto' => 'version'
			, 'optional' => array('encoding', 'standalone')
			, 'defaults' => array()
			, 'occur_once' => true
			, 'meta_attribs' => array('tplorig')
		)
		, 'robots' => array(
			'renameto' => 'index'
			, 'include_in_xml' => array('index', 'follow', 'archive')
			, 'optional' => array('index', 'follow')
			, 'defaults' => array()
			, 'occur_once' => true
			, 'xml_defaults' => array()
			, 'meta_attribs' => array('tplorig')
		)
		, 'pi' => array(
			'renameto' => 'prefix'
			, 'include_in_xml' => array()
			, 'optional' => array()
			, 'defaults' => array()
			, 'xml_defaults' => array()
			, 'doc_raw_target' => 'internal'
			, 'doc_raw_comment_style' => 'c'
			, 'meta_attribs' => array('tplorig', 'prefix', '_content')
		)
		, 'xsl' => array(
			'renameto' => 'href'
			, 'optional' => array('type', 'title', 'media', 'charset', 'alternate')
			, 'defaults' => array('type'=>'text/xsl')
			, 'doc_raw_target' => 'external'
			, 'doc_raw_comment_style' => 'xml'
			, 'doc_raw_prepostfix' => true
			, 'meta_attribs' => array('file', 'xform', 'tplorig', 'key')
		)
		, 'css' => array(
			'renameto' => 'href'
			, 'optional' => array('rel', 'id', 'class', 'dir', 'lang', 'style', 'xml__lang', 'type', 'target', 'rev', 'media', 'hreflang', 'charset', 'title')
			, 'defaults' => array('rel'=>'stylesheet', 'type'=>'text/css', 'media'=>'screen')
			, 'doc_raw_target' => 'external'
			, 'doc_raw_comment_style' => 'c'
			, 'meta_attribs' => array('file', 'tplorig', 'external')
			, 'include_in_xml' => array('rel', 'type', 'title', 'media', 'charset', 'alternate')
			, 'exclude_in_xml' => array('rel')
		)
		, 'notes' => array(
			'renameto' => 'file'
			, 'optional' => array('hide')
			, 'defaults' => array()
			, 'doc_raw_target' => 'external'
			, 'doc_raw_comment_style' => 'variable'
			, 'meta_attribs' => array('file', 'tplorig', 'comment_style')
		)
		, 'comments' => array(
			'renameto' => null
			, 'optional' => array('hide')
			, 'defaults' => array()
			, 'doc_raw_target' => 'internal'
			, 'doc_raw_comment_style' => 'c'
			, 'meta_attribs' => array('tplorig')
		)
		, 'root_pre' => array(
			'renameto' => null
			, 'optional' => array()
			, 'defaults' => array()
			, 'doc_raw_comment_style' => 'xml'
			, 'doc_raw_target' => 'internal'
			, 'meta_attribs' => array('tplorig')
		)
		, 'dtd' => array(
			'renameto' => 'family'
			, 'optional' => array('level', 'root', 'type', 'subtype', 'family')
			, 'defaults' => array('level'=>'Transitional', 'type'=>'PUBLIC')
			, 'doc_raw_target' => 'external'
			, 'doc_raw_comment_style' => 'xml'
			, 'occur_once' => true
			, 'meta_attribs' => array('file', 'tplorig')
		)
		, 'doctype' => array(
			'renameto' => null
			, 'optional' => array('level', 'root', 'type', 'subtype', 'family')
			, 'defaults' => array()
			, 'doc_raw_target' => 'internal'
			, 'doc_raw_comment_style' => 'xml'
			, 'occur_once' => true
			, 'defaults' => array('level'=>'Transitional', 'type'=>'PUBLIC')
			, 'meta_attribs' => array('tplorig', 'file')
		)
		, 'entity' => array(
			'renameto' => 'name'
			, 'include_in_xml' => array('name', 'type', 'param', 'ndata', 'notation_type', 'notation', '_content')
			, 'xml_defaults' => array()
			, 'defaults' => array()
			, 'doc_raw_target' => 'external'
			, 'doc_raw_comment_style' => 'xml'
			, 'doc_raw_prepostfix' => true
			, 'meta_attribs' => array('file', 'tplorig', 'get', 'subtype', 'dummy')
		)
		, 'xsd' => array(
			'renameto' => 'xsi:schemaLocation'
			, 'optional' => array('xmlns__xsi')
			, 'xml_defaults' => array('xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance')
			, 'defaults' => array()
			, 'include_in_xml' => array('xmlns__xsi')
			, 'doc_raw_target' => 'external'
			, 'doc_raw_comment_style' => 'xml'
			, 'doc_raw_prepostfix' => true
			, 'meta_attribs' => array('file', 'xsi:schemaLocation', 'xsi__schemaLocation', 'xsi__noNamespaceSchemaLocation', 'tplorig')
		)
		, 'root' => array(
			'renameto' => null
			, 'optional' => array('xmlns', 'xmlns__xsi')
			, 'defaults' => array()
			, 'occur_once' => true
			, 'meta_attribs' => array('xsi__schemaLocation', 'xsi__noNamespaceSchemaLocation', 'tplorig')
			, 'include_in_xml' => array('xmlns', 'xmlns__xsi')
		)
		, 'html' => array(
			'renameto' => 'xml:lang'
			, 'optional' => array('xmlns', 'dir', 'xml__lang', 'lang', 'id', 'version')
			, 'xml_defaults' => array('xmlns'=>'http://www.w3.org/1999/xhtml')
			, 'occur_once' => true
			, 'meta_attribs' => array('tplorig', 'v')
			, 'include_in_xml' => array('xsi__schemaLocation', 'xsi__noNamespaceSchemaLocation', 'xmlns__xsi', 'xmlns', 'dir', 'xml__lang', 'lang', 'id')
			, 'defaults' => array()
		)
		, 'head' => array(
			'renameto' => null
			, 'optional' => array('profile', 'dir', 'xml__lang', 'lang', 'id')
			, 'defaults' => array()
			, 'doc_raw_comment_style' => 'xml'
			, 'doc_raw_target' => 'internal'
			, 'occur_once' => true
			, 'meta_attribs' => array('tplorig', '_content')
		)
		, 'meta' => array(
			'renameto' => 'name'
			, 'optional' => array('content', 'http_equiv', 'scheme', 'name', 'id', 'lang', 'xml__lang')
			, 'defaults' => array('content'=>'')
			, 'meta_attribs' => array('tplorig')
		)
		, 'title' => array(
			'renameto' => null
			, 'optional' => array('dir', 'lang', 'xml__lang', 'id')
			, 'include_in_xml' => array('dir', 'lang', 'xml__lang')
			, 'defaults' => array()
			, 'occur_once' => true
			, 'meta_attribs' => array('tplorig')
		)
		, 'base' => array(
			'renameto' => 'href'
			, 'optional' => array('target', 'id')
			, 'defaults' => array()
			, 'occur_once' => true
			, 'meta_attribs' => array('tplorig')
		)
		, 'style' => array(
			'renameto' => null
			, 'optional' => array('type', 'media', 'title', 'lang', 'xml__lang', 'id')
			, 'defaults' => array('type'=>'text/css')
			, 'doc_raw_target' => 'internal'
			, 'doc_raw_comment_style' => 'c'
			, 'meta_attribs' => array('tplorig')
		)
		, 'link' => array(
			'renameto' => 'href'
			, 'optional' => array('rel', 'id', 'class', 'dir', 'lang', 'style', 'xml__lang', 'type', 'rev', 'media', 'hreflang', 'charset', 'title', 'xml__space', 'target', 'onclick', 'ondblclick', 'onmousedown', 'onmouseup', 'onmouseover', 'onmousemove', 'onmouseout', 'onkeypress', 'onkeydown', 'onkeyup')
			, 'defaults' => array()
			, 'meta_attribs' => array('tplorig', 'target')
		)
		, 'script' => array(
			'renameto' => null
			, 'optional' => array('type', 'charset', 'defer', 'language')
			, 'defaults' => array('type'=>'text/javascript')
			, 'doc_raw_target' => 'internal'
			, 'doc_raw_comment_style' => 'c'
			, 'meta_attribs' => array('tplorig', 'key')
		)
		, 'code' => array(
			'renameto' => 'src'
			, 'optional' => array('type', 'defer', 'charset', 'language')
			, 'defaults' => array('type'=>'text/javascript')
			, 'doc_raw_target' => 'external'
			, 'doc_raw_comment_style' => 'c'
			, 'meta_attribs' => array('file', 'tplorig')
		)
		, 'head_post' => array(
			'renameto' => null
			, 'optional' => array()
			, 'defaults' => array()
			, 'doc_raw_comment_style' => 'xml'
			, 'doc_raw_target' => 'internal'
			, 'meta_attribs' => array('tplorig')
		)
		, 'body'=> array(
			'renameto' => 'onload'
			, 'optional' => array('onload', 'id', 'class', 'lang', 'dir', 'xml__lang', 'title', 'style', 'onunload', 'onclick', 'ondblclick', 'onmousedown', 'onmouseup', 'onmouseover', 'onmousemove', 'onmouseout', 'onkeypress', 'onkeydown', 'onkeyup', 'xml__space', 'bgcolor', 'background', 'text', 'link', 'vlink', 'alink')
			, 'defaults' => array()
			, 'doc_raw_comment_style' => 'xml'
			, 'doc_raw_target' => 'internal'
			, 'occur_once' => true
			, 'meta_attribs' => array('tplorig')
		)
		, 'body_post'=> array(
			'renameto' => null
			, 'optional' => array()
			, 'defaults' => array()
			, 'doc_raw_comment_style' => 'xml'
			, 'doc_raw_target' => 'internal'
			, 'meta_attribs' => array('tplorig')
		)
		, 'root_post' => array(
			'renameto' => null
			, 'optional' => array()
			, 'defaults' => array()
			, 'doc_raw_comment_style' => 'xml'
			, 'doc_raw_target' => 'internal'
			, 'meta_attribs' => array('tplorig')
		)
	);
	/**
	 * doc_indent sets the level of indention by a specific number of spaces
	 * @var string
	 * @access protected
	 * @see setIndent()
	 */
	protected $doc_indent = '	';
	/**
	 * doc_css_url contains the path to css files
	 * @var string
	 * @access protected
	 * @see setCSSUrl()
	 */
	protected $doc_css_url = '';
	/**
	 * doc_script_url contains the path to script style urls
	 * @var string
	 * @access protected
	 * @see setScriptUrl()
	 */
	protected $doc_script_url = '';

	/**
	 * doc_info, doc_raw and doc_modules array
	 *
	 * @var array
	 * @access private
	 */
	private $doc_info = array();
	private $doc_raw  = array();
	private $doc_modules = array();

    private $avoid_xml_stylesheet = false;

	/**
	 * CONSTRUCTOR
	 *
	 * @param string
	 * @param string
	 */
	public function __construct($doc_infoalias='', $doc_rawalias='')
	{
		// sets the doc_raw alias
		if ($doc_rawalias != '')
		{
			$this->doc_rawalias = $doc_rawalias;
		}

		// sets the doc_infoalias
		if ($doc_infoalias != '')
		{
			$this->doc_infoalias = $doc_infoalias;
		}
		// Currently this just assigns the variable "SCRIPT_NAME"
		$this->Smarty();


		$this->set_rewrite_docraw_get();
		$this->set_comments_get();
		$this->set_whitespace_get();

		// A few variables that needed concatenation
		$this->dr_xsl_pre = '<'.'?xml-stylesheet href="';
		$this->dr_xsl_post = '?'.">\n";
		$this->xsl_prefixed = '<'.'?xml version="1.0"?'.">\n".'<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">'."\n";
		$this->entity_prefixed = '<'.'?xml version="1.0"?'.">\n";

		$this->set_rootnode($this->rootnode);

		foreach($this->doc_info_types as $key => $value)
		{
			if (array_key_exists('doc_raw_target', $value))
			{
				// Populated with those elements which can be targets of doc_raw blocks
				$this->doc_raws_tosearch[] = $key;

				if ($value['doc_raw_target'] === 'external')
				{
					// Populated with those elements which can be targets of doc_raw blocks
					// and shuffled off into external files
					$this->external_doc_raws[] = $key;
				}
			}

			if (isset($value['occur_once']))
			{
				// Populated with those elements which should only occur once
				$this->occur_once[] = $key;
			}
		}

        /**
         * Register Smarty Functions
         *
         * - info
         * - doc_info (alias for info, both accessing the same function)
         * - tag
         * - xslt
         */

		$this->register_function('info',        array($this, 'smarty_function_info'), false);
		$this->register_function('doc_info',    array($this, 'smarty_function_info'), false); // alias
		$this->register_function('tag',         array($this, 'smarty_function_tag'), false);
		$this->register_function('xslt',        array($this, 'smarty_function_xslt'), false);

		/**
		 * Register Smarty Modifiers
		 * - xsl
		 * - xml
		 */

		$this->register_modifier('xsl',         array($this, 'smarty_modifier_xsl'), false);
		$this->register_modifier('xml',         array($this, 'smarty_modifier_xml'), false);

		/**
		 * Register Smarty Blocks
		 *
		 * - xsl
		 * - xml
		 * - moveto
		 * - doc_raw (alias for moveto, both accessing the same function)
		 * - tagc
		 * - cdata
		 * - xinclude
		 *
		 * @todo Check if these could be cached?
		 */

		$this->register_block('xsl',            array($this, 'smarty_block_xsl'), false);
		$this->register_block('xml',            array($this, 'smarty_block_xml'), false);
		$this->register_block('moveto',         array($this, 'smarty_block_moveto'), false);
		$this->register_block('doc_raw',        array($this, 'smarty_block_moveto'), false); // alias
		$this->register_block('tagc',           array($this, 'smarty_block_tagc'), false);
		$this->register_block('cdata',          array($this, 'smarty_block_cdata'), false);
		$this->register_block('xinclude',       array($this, 'smarty_block_xinclude'), false);

		/**
		 * Register Smarty Prefilter
		 */

		$this->register_prefilter(array($this, 'smarty_prefilter_SmartyDoc'));
	}


	/**
	 * PUBLIC API
	 */

	 /**
      * sets rewrite_docraw_on via URL -> $_GET[$this->rewrite_docraw_get_url]
      *
      * @param string
      */
	 public function set_rewrite_docraw_get($new_rewrite_get = null)
	 {
		if (!is_null($new_rewrite_get))
		{
			$this->rewrite_docraw_get = $new_rewrite_get;
		}
		if ($this->rewrite_docraw_get && !empty($_GET[$this->rewrite_docraw_get_url]))
		{
			$this->rewrite_docraw_on = true;
		}
	 }

	/**
      * This sets rewrite_docraw_on variable to true or false
      *
      * @param bool
      */
	 public function set_rewrite_docraw_on($rewrite = true)
	 {
		if ($rewrite)
		{
			$this->rewrite_docraw_on = true;
		}
		else
		{
			$this->rewrite_docraw_on = false;
		}
	}

	/**
     * This sets comments variable to true or false
     *
     * @param bool
     */
	public function set_comments ($comments = true)
	{
		if ($comments)
		{
			$this->comments = true;
		}
		else
		{
			$this->comments = false;
		}
	}

    /**
	 * Sets comments by URL via $_GET
	 *
	 * @param string
	 */
	public function set_comments_get($new_comments_get = null)
	{
		if (!is_null($new_comments_get))
		{
			$this->comments_get = $new_comments_get;
		}
		if ($this->comments_get && !empty($_GET[$this->comments_get_url]))
		{
			$this->comments = true;
		}
	}

	/**
	 * Sets whitespaces by URL via $_GET
	 *
	 * @param string
	 */
	public function set_whitespace_get($new_whitespace_get=null)
	{
		if (!is_null($new_whitespace_get))
		{
			$this->whitespace_get = $new_whitespace_get;
		}
		if ($this->whitespace_get)
		{
			if (empty($_GET[$this->whitespace_get_url]) && $this->show_whitespace_comments)
			{

				if ($this->strip_all_whitespace)
				{
					$this->whitespace_comment_type = 'all';
				}
				elseif ($this->strip_whitespace)
				{
					$this->whitespace_comment_type = 'some';
				}
				else
				{
					$this->whitespace_comment_type = 'none';
				}

			}
			else
			{
				switch($_GET[$this->whitespace_get_url])
				{
					case 'none':
					case '0':
						$this->strip_whitespace = false;
						$this->strip_all_whitespace = false;
						$this->whitespace_comment_type = 'none';
						break;
					case 'all':
						$this->strip_all_whitespace = true;
						$this->whitespace_comment_type = 'all';
						break;
					case '1':
					case 'some':
					default:
						$this->strip_all_whitespace = false;
						$this->strip_whitespace = true;
						$this->whitespace_comment_type = 'some';
						break;
				} // end switch
			}
		}
	} // end function

	/**
	 * This sets xml_plain to $val
	 * calls setContentType
	 * sets headers_ran true
	 * @param bool
	 */
	 public function xml_plain($val = true)
	 {
		$this->xml_plain = $val;
		$this->setContentType();
		$this->headers_ran = true;
	 }

	/**
	 * Set Rewrite_docraw_on to $val
	 * @param bool
	 */
	public function rewrite_docraw_on($val = true)
	{
		$this->rewrite_docraw_on = $val;
	}

	/**
     * This hides the notes directory.
     *
     * @param bool
     */
	public function hidden_notes_dir ($hidden = true)
	{
		if ($hidden || $hidden === 'hidden' || $hidden === 'hide')
		{
			$this->notes_in_hiddendir = true;
			$this->dr_notes_file = $this->site_root_hidden.$this->dr_notes_file_src;
		}
		else
		{
			$this->notes_in_hiddendir = false;
			$this->dr_notes_file = $this->site_root_public.$this->dr_notes_file_src;
		}
	}

	/**
     * If hide_notes(false) makes the notes public and visible in the comments of the output
     *
     * @param bool
     */
	public function hide_notes ($bool = true) {
		if ($bool)
		{
			$this->visible_notes = true;
			$this->hidden_notes_dir(false);
		}
		else
		{
			$this->visible_notes = false;
			$this->hidden_notes_dir();
		}
	}

    /**
     * sets the rootnode element
     *
     * @param string
     */
	public function set_rootnode ($node = 'html')
	{
		$this->rootnode = $node;

		$this->dr_xsd_file_src = '/'.$node.'.xsd';
		$this->dr_dtd_file_src = '/'.$node.'.dtd';
		$this->dr_xsd_public_file = $this->site_public_root_dir.'/'.$node.'.xsd';
		$this->dr_dtd_public_file = $this->site_public_root_dir.'/'.$node.'.dtd';
		$this->dr_xsd_file = $this->site_root_public.'/'.$node.'.xsd';
		$this->dr_dtd_file = $this->site_root_public.'/'.$node.'.dtd';
		$this->dr_entity_file = $this->site_root_public.'/'.$node.'.xml';
		$this->dr_entity_file_src = '/'.$node.'.xml';

		// May wish these to be undefined instead
		$this->dr_css_file = $this->site_root_public.$this->dr_css_file_src;
		$this->dr_code_file = $this->site_root_public.$this->dr_code_file_src;
		$this->dr_xsl_file = $this->site_root_public.$this->dr_xsl_file_src;
		$this->hidden_notes_dir($this->notes_in_hiddendir); // Sets notes dir

		$this->xsd_prefixed = '<'.'?xml version="'.$this->xsd_xml_version.'"?'.">\n".'<xs:schema xmlns:xs="'.$this->xsd_ns.'"targetNamespace="'.$this->xsd_target_ns.'"xmlns="'.$this->xsd_xmlns.'"elementFormDefault="'.$this->xsd_formdefault.'">'."\n";

	}

	/**
     * unregisters the smarty_prefilter_SmartyDoc
     */
	public function no_prefilter()
	{
		unregister_prefilter('smarty_prefilter_SmartyDoc');
	}

	/**
     * Get a certain SmartyDocModule
     *
     * @param string
     */
	public function &getDocModule($smarty_doc_module='')
	{
		$module = $this->loadDocModule($smarty_doc_module);
		return $this->doc_modules[$module];
	}

	/**
     * Loades the SmartyDocModule
     *
     * @param string $smarty_doc_module contains modulename
     */
	public function loadDocModule($smarty_doc_module='')
	{
		if (!isset($smarty_doc_module) && is_string($smarty_doc_module) && strlen($smarty_doc_module)>0) {
			$this->trigger_error('loadDocModule: bad module name.');
		}
		$module = "smarty_docmodule_$smarty_doc_module";
		if (!array_key_exists($module, $this->doc_modules)) {
			require_once $this->_get_plugin_filepath('docmodule', $smarty_doc_module);
			$this->registerDocModule(new $module($this));
		}
		return $module;
	}

	/**
     * Registers DocModule
     *
     * @param class
     */
	public function registerDocModule(ISmartyDocModule $smarty_doc_module)
	{
		$class = get_class($smarty_doc_module);
		if (!array_key_exists($class, $this->doc_modules)) {
			$this->doc_modules[$class] = $smarty_doc_module;
		}
	}

	/**
	 * Clear internally collected document information
	 */
	public function resetDoc()
	{
		$this->doc_info = array();
		$this->doc_raw = array();
	}

	/**
	 * Set the doctype family and level
	 *
	 * @param string $family contains doctype family
	 * @param string $level cotains doctype level
	 */
	public function setDoctype($family='XHTML', $level='Transitional')
	{
		if (isset($family) && !empty($family) && is_string($family)) {
			$this->doc_info['dtd']['family'] = strtoupper($family);
		}
		if (isset($level) && !empty($level) && is_string($level)) {
			$this->doc_info['dtd']['level'] = ucfirst(strtolower($level));
		}
	}

	/**
	 * Get the signature of the currently set doctype
	 *
	 * @param string $docinfo contains the docinfo string
	 * @param string $val contains value
	 *
	 * @return $dtd - mixed doctype signature if available or otherwise null
	 */
	public function getDoctypeSignature($docinfo = 'doc_info', $val = '')
	{
        $docpre  = null;
        $docpost = null;
        $addrtbr = null;

		if ($docinfo === 'doc_info' || $docinfo === $this->doc_infoalias)
		{
			// A docinfo or add_openclose (value for latter will be empty if there is no docinfo (but not a doc_raw))
			$value = $this->doc_info['dtd'];
		}
		else
		{
			$value = $val;
		}
		if (!empty($value))
		{
			// Assuming an uppercase family name to allow lowercase to work in attributes (just using HTML and XHTML right now anyways)
			$family = strtoupper($value['family']);
			$level = ucfirst(strtolower($value['level']));
			$file = $value['file'];
			if ($this->external_comments || $this->comments)
			{
				$tplorig = $value['tplorig'];
				$docpre = '<!-- Begin '.$docinfo.' dtd from template '.$tplorig." -->\n";
				$docpost = '<!-- End '.$docinfo.' dtd from template '.$tplorig." -->\n";
			}
			if ($level == '')
			{
				// Gets the default (e.g., "transitional")
				$level = $this->doc_info_types['dtd']['meta_attribs']['level'];
			}

			// This is an extra attempt to see whether an internal doctype would be possible for an XHTML type
			$try_internal_doctype = false;
			// Used in else clause further below if not first reset in this next 'if'
			$rtbracket = '>';

			// Note that this won't add any internal doctypes unless being served as XML
			if ($this->xml_plain && $this->internaldoctype)
			{
				$try_internal_doctype = true;
				$rtbracket = " [\n".$this->doctype_rawcontent.$this->docpre_doctype.$this->doctype_add.$this->docpost_doctype."\n]>";
			}

			if (array_key_exists($family, $this->DTDS) &&
				 array_key_exists($level, $this->DTDS[$family]))
				{
				$dtd = $docpre.$this->DTDS[$family][$level]['signature'];
				if ($try_internal_doctype && in_array($level, self::$modular_xml))
				{
					// Make way for internal doctype first
					$dtd = rtrim($dtd, '>');
					$addrtbr = $rtbracket;
				}
				// Since they are adding an internal doctype, convert to form which can accept an internal doctype
				elseif ($try_internal_doctype)
				{
					$dtd = rtrim($this->DTDS['XHTML']['1.1']['signature'], '>');
					$addrtbr = $rtbracket;
					$this->application_xml = 'XHTML';
				}
				$dtd .= $addrtbr."\n".$docpost;
			}
			else {
				// only uses uppercase (SYSTEM or PUBLIC identifiers)
				$type = strtoupper($value['type']);

				if(isset($value['subtype']))
				{
				    $subtype = $value['subtype'];
				}

				// If dtd specified a root
				if (!empty($value['root']))
				{
					$root = $value['root'];
				}
				else
				{
					$root = $this->rootnode; // May have been set by 'root' doc_info in addition to the other means (uses default otherwise)
				}

				if ($root === 'html' && $type === 'PUBLIC' && !isset($subtype))
				{
					$type = $this->doc_info_types['dtd']['defaults']['type']; // PUBLIC
					$subtype = '-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd'; // If you're trying a DTD, go for the modular
					$this->application_xml == 'XHTML';
					// It now should be (X)HTML
					$this->set_rootnode('html');
				}
				elseif (!$this->avoid_blanksubtype && $root !== 'html' && $type === 'PUBLIC' && !isset($subtype))
				{
					// If a regular external DTD (but set by default to 'PUBLIC')
					$type = 'SYSTEM';
				}
				elseif ($root !== 'html' && $type === 'PUBLIC' && !isset($subtype))
				{
					$nosubtype = true;
				}

				// For a doctype doc_raw with no subtype specified
				if (isset($nosubtype) && $this->avoid_blanksubtype) {
					$dtd = $docpre.'<!DOCTYPE '.$root;
					$dtd .= $rtbracket;
					$dtd .= "\n".$docpost;
				}
				// If original set but not blank but subtype is blank
				elseif ($subtype == '')
				{
					if ($file == '')
					{
						$file = $this->dr_dtd_public_file;
					}
					$dtd = $docpre.'<!DOCTYPE '.$root.' '.$type.' "'.$file.'"';
					$dtd .= $rtbracket;
					$dtd .= "\n".$docpost;
				}
				else {
					$dtd = $docpre.'<!DOCTYPE '.$root.' '.$type.' "'.$subtype.'"';
					$dtd .= $rtbracket;
					$dtd .= "\n".$docpost;
				}
			}
		}
		// This should not allow internal doctypes since even modular XHTML would not only have an internal doctype
		elseif ($this->add_openclose)
		{
			if ($this->external_comments || $this->comments)
			{
				$docpre = '<!-- Begin auto add_openclose dtd '."-->\n";
				$docpost = '<!-- End auto add_openclose dtd '."-->\n";
			}
			$version = $this->doc_info['html']['v'];
			// If version is specified, add that version as a default
			if (isset($version))
			{
				$dtd = $docpre.$this->DTDS['XHTML'][$version]['signature']."\n".$docpost;
			}
			else
			{
				$dtd = $docpre.$this->DTDS['XHTML']['Transitional']['signature']."\n".$docpost;
			}
		}

		return $dtd;
	}

	/**
	 * Set the default amount of indenting to apply to generated tags
	 * variablename: doc_indent
	 *
	 * @param string $indent contains a certain number of indention spaces
	 */
	public function setIndent($indent='	')
	{
		if (isset($indent) && !empty($indent) && is_string($indent))
		{
			$this->doc_indent = $indent;
		}
	}
	/**
	 * Get the default amount of indenting to apply to generated tags
	 *
	 * @return $this->doc_indent
	 */
	public function getIndent()
	{
		return $this->doc_indent;
	}

	/**
	 * Set the path to append to CSS style urls
	 * variablename: doc_css_url
	 *
	 * @param string $url contains the doc_script_url
	 */
	public function setCSSUrl($url='')
	{
		if (isset($url) && !empty($url) && is_string($url)) {
			$this->doc_css_url = $url;
		}
	}

	/**
	 * Set the path to append to script style urls
	 * variablename: doc_script_url
	 *
	 * @param string $url contains the doc_script_url
	 */
	public function setScriptUrl($url='')
	{
		if (isset($url) && !empty($url) && is_string($url)) {
			$this->doc_script_url = $url;
		}
	}

	/**
	 * Add raw data to be inserted verbatim into the document head
	 *
	 * @param string $content contains the content
	 * @param $key
	 * @param string $target contains the target
	 */
	public function addRawHeader($content='', $key=null, $target='head')
	{
		$this->addRaw($content, $key, $target);
	}

	/**
	 * Add raw data to be inserted verbatim into the document body
	 *
	 * @param string $content contains the content
	 * @param $key
	 * @param string $target contains the target
	 */
	public function addRawContent($content='', $key=null, $target='body')
	{
		$this->addRaw($content, $key, $target);
	}


	/**
	 * Add an item to the doc_info collected information
	 *
	 * @param array $params
	 * @param bool $docraw
	 */
	public function addInfo($params=array(), $docraw = false)
	{

		// The following should be in the occur_once array:'title', 'base', 'xml', 'html', 'root', 'head', 'body', 'doctype', 'dtd';
		if (isset($docraw))
		{
			// Brett removed "is_string($params['key']) &&" from the following condition in order to allow keys which specified a sequence for placement
			$key = (isset($params['key']) && strlen($params['key'])>0)
				? $params['key']
				: null;

			if (isset($params['target'])){ $target = $params['target']; } else { $target = null; }

			if (isset($params['file']))
			{
				$fileparam = $params['file'];
			}
			else
			{
			    $fileparam = 'main';
			}
		}

		foreach ($this->doc_info_types as $allowed=>$rules)
		{
			$element = array();

			// This 'if' follows constraints unless 'root' is the element (assuming it is not the "root" attribute within a 'dtd' or 'doctype')
			// Brett added a condition for doc_raws since they won't necessarily have an allowable parameter (but they may rely on the default attributes and definitely on the meta attribute target)
			if (($docraw && strstr($allowed, $target)) ||
				(isset($params[$allowed]) && array_key_exists($allowed, $params)) ||
				($allowed === 'root' && isset($params['root'])&& !isset($params['dtd']) && !isset($params['doctype'])) ||
				$allowed === 'pi' && isset($params['pi']))
				{
			 	// Allow plain XML to have as many attributes as its call has (besides "root" itself)
			 	if (($allowed === 'pi' && isset($params['pi'])) || ($allowed === 'root' && isset($params['root'])&& !isset($params['dtd']) && !isset($params['doctype'])))
			 	{
					foreach($params as $k=>$v)
					{
						// Don't want 'root' showing up in the attributes
						if ($k !== 'root' && $k !== 'pi')
						{
							$_k = str_replace('_', '-', str_replace('__', ':', $k));
							$element[$_k] = $params[$k];
							if (in_array($attribute, $this->file_attribs))
							{
								$element[$_k] = urlencode($element[$_k]);
							}
						}
					}
				}
				else
				{
					if ($this->xml_plain && isset($rules['include_in_xml']) && is_array($rules['include_in_xml']))
					{
					    $rules2cycle = $rules['include_in_xml'];

						if (isset($rules['xml_defaults']) && is_array($rules['xml_defaults']))
						{
							foreach($this->doc_info_types[$allowed]['xml_defaults'] as $defkey => $default)
							{
								$element[$defkey] = $default;
							}
						}
					}
					else
					{
						$rules2cycle = $rules['optional'];
					}
					$rules2cycle = array_merge($rules2cycle, $rules['meta_attribs']);

					$paramkeys = array_keys($params);

					// If contains unexpected attributes, cycle again to avoid the break below (e.g., if the $allowed doctype was, by a fluke, in the params--e.g., "title" not as a title but as an attribute of some other element)
					foreach ($paramkeys as $paramkey) {
						if (((!$docraw && $paramkey !== $allowed) ||
							($docraw && $params['target'] !== $allowed)) &&
								!in_array($paramkey, $rules2cycle) &&
								!in_array($paramkey, $rules['defaults']))
							{
							// If decide to allow renameto's for docraws, the portion "!docraw &&" of the first condition should be removed
							continue 2;
						}
					}

					foreach ($rules2cycle as $key2 => $attribute)
					{
						$_attribute = str_replace('_', '-', str_replace('__', ':', $attribute));
						if (isset($params[$attribute]) && array_key_exists($attribute, $params))
						{
							$element[$_attribute] = $params[$attribute];
						}
						elseif (array_key_exists($attribute, $params))
						{
							$element[$_attribute] = null;
						}
						elseif (array_key_exists($attribute, $rules['defaults']))
						{
							$element[$_attribute] = $rules['defaults'][$attribute];
						}
						if (in_array($attribute, $this->file_attribs))
						{
							$element[$_attribute] = @urlencode($element[$_attribute]);
						}
					}

				}
				// The renameto portion might also be allowed for docraws (but first follow the notes around the "continue 2" loop above; some other changes would also be necessary
				if (!$docraw)
				{
					$renameto = (is_null($rules['renameto'])) ? '_content' : $rules['renameto'];
					$element[$renameto] = $params[$allowed];
					// Docinfos now also have access to originating template (but won't print this out except in comments)
					if(isset($params['tplorig'])){$element['tplorig'] = $params['tplorig'];}
					if (in_array($allowed, $this->occur_once)) { // Those that occur once
						$this->doc_info[$allowed] = $element;
					}
					else
					{
						$this->doc_info[$allowed][$element[$renameto]] = $element;
					}
				}
				break;
			}
		}
		if ($docraw) {
			// Add file attribute to external ones (and xform to both)
			$element['tplorig'] = $params['tplorig'];

			if (empty($key))
			{
				self::$unique++;
				$id = self::$unique;
				$this->doc_raw[$target][$fileparam][$id] = $element;
			}
			// by specific (renameto) (e.g., the doc_raw referencing that file; otherwise, if as above, the doc_raw variable may be overwritten)
			else
			{
				$this->doc_raw[$target][$fileparam][$key] = $element;
			}
		}
	}

	/**
	 * Get a previously stored raw header data item
	 *
	 * @return mixed raw header item based on key, current header item if key=null otherwise false
	 */
	public function &getDocInfo()
	{
		return $this->doc_info;
	}

	/**
	 * Get a previously stored raw header data item
	 *
	 * @param $key
	 * @param string $target
	 *
	 * @return mixed raw header item based on key, current header item if key=null otherwise false
	 */
	public function getRawHeader($key=null, $target='head')
	{
		return $this->getRaw($key, $target);
	}

	/**
	 * Get a previously stored raw body data item
	 *
	 * @param $key
	 * @param string $target
	 *
	 * @return mixed raw body item based on key, current body item if key=null otherwise false
	 */
	public function getRawContent($key=null, $target='body')
	{
		return $this->getRaw($key, $target);
	}

	/**
	 * Override Smarty::fetch() to ensure that the SmartyDoc outputfilter is not active
	 *
	 * @param $resource_name
	 * @param $cache_id
	 * @param $compile_id
	 * @param bool $display
	 *
	 * @return echoes $output
	 */
	public function fetch($resource_name, $cache_id=null, $compile_id=null, $display=false)
	{
		$outputfilter_loaded = array_key_exists('smarty_outputfilter_SmartyDoc', $this->_plugins['outputfilter']);
		if ($outputfilter_loaded)
		{
			$this->unregister_outputfilter('smarty_outputfilter_SmartyDoc');
		}
		$output = parent::fetch($resource_name, $cache_id, $compile_id);

		if ($outputfilter_loaded)
		{
			$this->register_outputfilter(array($this, 'smarty_outputfilter_SmartyDoc'));
		}
		if (!$display) return $output;
		echo $output;
	}

	/**
	 * Similar to Smarty::display() except that it defers to SmartyDoc::fetchDoc().
 	 *
	 * @param $resource_name
	 * @param $cache_id
	 * @param $compile_id
	 */
	public function displayDoc($resource_name, $cache_id=null, $compile_id=null)
	{
		$this->fetchDoc($resource_name, $cache_id, $compile_id, true);
	}

	private function xhtmlbasic_detect() {
		// This test is necessary here to avoid including scripts, as these are not allowed in XHTML Basic (as with input type file, button, and nested tables)
		if (isset($_GET[$this->xformtobasic_url]) && $_GET[$this->xformtobasic_url] && $this->xformtobasic_get)
		{
			$this->xsl[] = $this->xhtmlbasic_xsl;
			$this->pre_xform[] = true;
			$this->xhtmlbasic = true;
		}
		elseif ($this->auto_xform_mobile && $this->Browscap_file != '' && file_exists($this->Browscap_file))
		{
			//Loads the class (get at http://garetjax.info/projects/browscap/ )
			require $this->Browscap_file;
			//Creates a new browscap object; loads or creates the cache)
			$bc = new Browscap($this->Browscap_cache_dir);
			//Gets information about the current browser's user agent
			$current_browser = $bc->getBrowser();
			if ($current_browser->isMobileDevice)
			{
				$this->xsl[] = $this->xhtmlbasic_xsl;
				$this->pre_xform[] = true;
				$this->xhtmlbasic = true;
			}
	    }

		if ($this->xhtmlbasic) {
			$this->assign('xhtmlbasic_flag', true);
		}
	}

	/**
	 * Produce a complete document as determined by the collected document
	 * information and using the SmartyDoc outputfilter. Clears collected
	 * document information after executing.
	 *
	 * @param $resource_name
	 * @param $cache_id
	 * @param $compile_id
	 * @param bool $display
	 *
	 * @return echoes $output
	 */
	public function fetchDoc($resource_name, $cache_id=null, $compile_id=null, $display=false)
	{

		$this->xhtmlbasic_detect();

		$this->register_outputfilter(array($this, 'smarty_outputfilter_SmartyDoc'));
		if ($this->strip_whitespace)
		{
			// This filter doesn't work for very large files at least within the textarea block (due to use of preg_replace which has apparent size limits)--for my system anything greater than 99996 characters would cause the page to go completely blank
			$this->load_filter('output', 'trimwhitespace');
		}

		$this->setContentType();
		$output = parent::fetch($resource_name, $cache_id, $compile_id);

//		if (!isset($this->headers_ran) || !$this->headers_ran) {

			/* While I was testing, it seemed Explorer needed the following to be forced, though now it is ok (scratches head); still working on this, so don't delete it yet
			if ($this->force_html) {
				$this->HTTP_ACCEPT = 'text/html';
			}
			elseif ($this->force_css) {
				$this->HTTP_ACCEPT = 'text/css';
			}
*/

			header('Content-Script-Type: text/javascript');
			header('Content-Type: '.$this->HTTP_ACCEPT.'; charset='.$this->encoding);
//		}

		$this->unregister_outputfilter('smarty_outputfilter_SmartyDoc');
		$this->resetDoc();

		// zlib.output_compression cannot be used with ob_gzhandler()
		if (extension_loaded('zlib') && isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
			substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') &&
			ini_get('zlib.output_compression')
			)
		{
			// If you want to use this, you will presumably want to set zlib.output_compression_level to a positive value as well
			$ob_enabled = false;
		}
		// PHP documentation states that the former is preferable (if available)
		elseif (extension_loaded('zlib') && isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
		 		  substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')
		 		  && $this->gzip_output)
		{
			//ob_start('ob_gzhandler'); // This caused problems with blank screens (using Windows machine); see also comment at http://cn.php.net/manual/en/function.ob-gzhandler.php
			$ob_enabled = true;
		}
		else
		{
			$ob_enabled = false; // (could add a plain ob_start(), but no point in this script)
		}

		if (!$display)
		{
			return $output;
		}

		echo $output;

		// This surrounding conditional probably isn't necessary
		if ($ob_enabled)
		{
		//	$a = ob_end_clean(); // commenting out since commented above out // problems per http://cn.php.net/ob_gzhandler
        }

	}

	/**
	 * This function sets up the Smarty SmartyDoc Prefilter
	 *
	 * Convert the curly braces temporarily into double brackets, so that
	 * Smarty can parse them (for CSS or Javascript)
	 *
	 */
	public function smarty_prefilter_SmartyDoc($source, &$smarty)
	{
		// Remember when debugging prefilters like this one, you have to require
		// compiling each time or delete the compiled files!!!

		// protect comments temporarily
		if ($this->left_tagcdelim === '<')
		{
			$source = preg_replace('@'.$this->left_tagcdelim.'\!([^'.$this->right_tagcdelim.']*?)'.$this->right_tagcdelim.'@', '[[[!$1!]]]', $source);
			$source = preg_replace('@'.$this->left_tagcdelim.'\?([^'.$this->right_tagcdelim.']*?)'.$this->right_tagcdelim.'@', '[[[?$1?]]]', $source);
			$source = preg_replace('@'.$this->left_tagcdelim.'([^'.$this->right_tagcdelim.']*?)/'.$this->right_tagcdelim.'@', '[[[/$1/]]]', $source);
			$callback00 = create_function('$matches', "return \$matches[1].str_replace(array('<', '>'), array('--docinfobegin--', '--docinfoend--'), \$matches[2]).\$matches[3];");
			$source = preg_replace_callback('@(\{'.$this->doc_infoalias.')([^}]*?)(\})@s', $callback00, $source);
			$callback0 = create_function('$matches', "return \$matches[1].str_replace('<', '--cdata--', \$matches[2]).\$matches[3];");
			$source = preg_replace_callback('@(\{cdata\})([^{]*?)(\{\/cdata\})@s', $callback0, $source);
		}

		/**
		 * Prepare delimiters for insertion into regexps below
		 */

		// The "closing" tag does not need the actual tag name, but since it may be more clear in the template to have it, it can be deleted here through a regexp (instead of later in the next str_replace
		$source = preg_replace('@'.$this->left_tagcclose.'[^'.$this->right_tagcclose.']*?'.$this->right_tagcclose.'@', '{/tagc}', $source);

		//  Didn't really need these (unless making them as opening rather than self-closing tags--but opening would probably be rare)
		// $source = preg_replace('@'.$this->left_tagclose.'([^'.$this->right_tagclose.']*?)'.$this->right_tagclose.'@', '</$1>', $source);

		// Could replace these with regular expresions (e.g., to avoid replacement of the delimiters not meant as delimiters), but would slow things down somewhat
		// The function will probably be most conveniently used with self-closing tags (although self-closing that need styling would be rare) since just using opening tags might be even more rare
		$source = str_replace(
			array(
			$this->left_tagcdelim, $this->right_tagcdelim ,
				$this->left_tagdelim, $this->right_tagdelim2, $this->right_tagdelim,
			),
			array(
				'{tagc e=', '}' ,
				'{tag sc=', '}', '}'
			), $source);

		// protect comments temporarily
		if ($this->left_tagcdelim === '<')
		{
			$source = str_replace(array('[[[!', '!]]]'), array('<!', '>'), $source);
			$source = str_replace(array('[[[?', '?]]]'), array('<?', '>'), $source);
			$source = str_replace(array('[[[/', '/]]]'), array('<', '/>'), $source);
			$source = str_replace('--cdata--', '<', $source);
			$source = str_replace('--docinfobegin--', '<', $source);
			$source = str_replace('--docinfoend--', '>', $source);
		}

		$ldelim = preg_quote($this->left_delimiter, '@');
		$rdelim = preg_quote($this->right_delimiter, '@');

		// Adding a temporary replacement to hide the genuine smarty variables from the other curly brace items
		$source = preg_replace('@'.$ldelim.'\$([^}]*?)'.$rdelim.'@', '-~#-~$1-#~-', $source);
		// Adding a temporary replacement to hide the genuine config variables from the other curly brace items
		$source = preg_replace('@'.$ldelim.'#([^}]*?)'.$rdelim.'@', '--#-~$1--~-', $source);

		// This approach is better than using preg_replace with the e modifier, since preg_replace auto-performs addslashes() which we do not want here
		$callback = create_function('$matches', "return \$matches[1].str_replace(array('{', '}'), array('[[[', ']]]'), \$matches[4]).\$matches[5];");

		$source = preg_replace_callback("@(".$ldelim.$this->doc_infoalias."\s+(style|script)=(['|\"]))([^\\3]*?)(\\3.*?".$rdelim.")@s", $callback, $source);

		// Will search for all allowable doc_raw targets
		$glued_docraws = implode('|', $this->doc_raws_tosearch);

		//	print "@(".$ldelim."doc_raw\s+target=(['|\"]{0,1})(".$glued_docraws.")\\2[^}]*?".$rdelim.")(.*?)(".$ldelim."/doc_raw".$rdelim.")@s";exit;
		//	print htmlentities($source);exit;

		$source = preg_replace_callback("@(".$ldelim.$this->doc_rawalias."\s+target=(['|\"]{0,1})(".$glued_docraws.")\\2[^}]*?".$rdelim.")(.*?)(".$ldelim."/".$this->doc_rawalias.$rdelim.")@s", $callback, $source);

		// Reverting back temporary replacement from above to allow smarty variables to work
		$source = preg_replace('@-~#-~(.*?)-#\~-@s', $this->left_delimiter.'$$1'.$this->right_delimiter, $source);
		// Reverting back temporary replacement from above to allow config variables to work
		$source = preg_replace('@--#-~(.*?)--\~-@s', $this->left_delimiter.'#$1'.$this->right_delimiter, $source);

		$source = str_replace(array('[#', '#]'), array($this->left_delimiter.'#', $this->right_delimiter), $source);
		$source = str_replace(array('[-', '-]'), array($this->left_delimiter.'$', $this->right_delimiter), $source);
		// For Smarty functions
		$source = str_replace(array('[=', '=]'), array($this->left_delimiter, $this->right_delimiter), $source);

		return $source;
	}


	/**
	 * PROTECTED API
	 */

	/**
     * This functions adds Raw content to the doc_raw array.
     *
     * @param $content
     * @param string $key
	 * @param string $target
	 * @param string $file
	 * @access protected
     */
	protected function addRaw($content, $key='', $target='head', $file='main')
	{
		$target = strtolower($target);

		// Set if the target is faulty
		if (!in_array($target, $this->doc_raws_tosearch))
		{
			$target = 'head';
		}

		// Brett removed && is_string($content)
		if (isset($content) && !empty($content))
		{
			if ($file == '')
			{
				$file = 'main';
			}
			if (empty($key))
			{
				self::$unique++;
				$id = self::$unique;
				$this->doc_raw[$target][$file][$id]['_content'] =  $content;
			}
			else
			{
				$this->doc_raw[$target][$file][$key]['_content'] = $content;
			}
		}
	}

	/**
	 * This function gets Raw content from the doc_raw array.
	 *
	 * Note by Brett:
	 * getRaw is now getting an associative array, since the attributes are included.
	 *
	 * @param string $key
	 * @param string $target
	 * @param string $file
	 * @access protected
	 * @return doc_raw[$target][$file][$key]
	 */
	protected function getRaw($key=null, $target='head', $file='main')
	{
		$target = strtolower($target);

		// Set if the target is faulty
		if (!in_array($target, $this->doc_raws_tosearch))
		{
			$target = 'head';
		}

		if (isset($key) && is_string($key) && strlen($key)>0)
		{
			if (!isset($this->doc_raw[$target][$file][$key]))
			{
				return false;
			}
			return $this->doc_raw[$target][$file][$key];
		}
		else
		{
			self::$unique++;
			$id = self::$unique;
			return current($this->doc_raw[$target][$file][$id]);
		}
	}


	/**
	 * SMARTY PLUGIN CALLBACKS
	 */

	/**
	 * Smarty {doc_module} function plugin
	 *
	 * Insert some raw text into the html header from anywhere at anytime
	 *
	 * @return nothing
	 */
	public function smarty_function_doc_module($params, &$smarty)
	{
		if (!isset($params['name']) && is_string($params['name']) && strlen($params['name'])>0) {
			$smarty->trigger_error("doc_module: required 'name' parameter missing.");
		} else {
			$smarty->loadDocModule($params['name']);
		}
	}


    /**
     * prepare_xsl
     *
     * This function is used by a set of XML/XSL smarty modifiers, block functions,
     * and a regular function, to transform XML by XSL
     *
     * @param $xml
     * @param $xsl
     * @param string $prefix
     * @param string $xmlns
     * @param string $xsisl
     * @param string $pre_add
     * @return transformed XML
     */
	public function prepare_xsl($xml, $xsl, $prefix = '', $xmlns = '', $xsisl = '', $pre_add = '')
	{
		// Note that pre_add can only work if converting all of the document's elements to the same one namespace (i.e., no multiple prefixes should be submitted to this function nor should there be any already there if using pre_add (nor any additional xmlns')--otherwise, delimit prefixes/xsisl/xmlns by space and make sure the prefixes match the xmlns order)
		// I wasn't actually able to get the pre_add to work (at least by using an XSL command which referenced the prefix:  <for-each select="goo:catalog/goo:cd"> in it; just set this to '' for now
		if ($xsisl != '')
		{
			$tok = strtok($xsisl, ' ');
			while ($tok !== false)
			{
				$this->extra_xsds[$tok] = $tok; // added $tok as key to prevent duplicates
				$tok = strtok(' ');
			}
		}
		if ($xmlns != '')
		{
			$xmlnss = explode(' ', $xmlns);
			$prefixes = explode(' ', $prefix);
			for ($i = 0, $cnt = count($xmlnss); $i < $cnt; $i++)
			{
				$this->extra_xmlns[$prefixes[$i]] = 'xmlns:'.$prefixes[$i].'="'.$xmlnss[$i].'"';
			}
		}

		// If a file and not XML
		if ((strstr($xml, '/') || strstr($xml, '.')) && !strstr($xml, '<'))
		{
			$xml = file_get_contents($xml);
		}

		$xslproc = new XSLTProcessor;

		// If a file and not XML
		if ((strstr($xml, '/') || strstr($xsl, '.')) && !strstr($xsl, '<'))
		{
			$xsl = file_get_contents($xsl);
		}

		$rootnode = '';
		if (!empty($pre_add) && !strstr($prefix, ' '))
		{
			$xml = preg_replace('@\<(\s*)(([^!/<> ?]+)[^<>]*?)\>@e', '$rootnode = \'<'.$prefix.':$2 xmlns:'.$prefix.'="'.$xmlns.'">\';', $xml, 1); // Only do the first tag (i.e., the root)
			$xml = preg_replace('@\<(\s*)([^!/<>:?][^<>:]*?)\>@s', '<'.$prefix.':$2>', $xml);
			$xml = stripslashes(preg_replace('@\<(\s*)/([^!<>:]*?)\>@s', '</'.$prefix.':$2>', $xml));
		}

		// This and the next str_replace were necessary since Smarty's "capture" feature was creating these echo statements apparently
		$xsl = str_replace(array("<?php echo '<?xml'; ?>version", "<?php echo '?>'; ?>"), array('<?xml version', '?>'), $xsl);

		// Attach the xsl rule
		$xslproc->importStyleSheet(DOMDocument::loadXML($xsl));

		$xml = str_replace(array("<?php echo '<?xml'; ?>-", "<?php echo '<?xml'; ?>version", "<?php echo '?>'; ?>", "<?php echo '?>';  echo '<?xml'; ?>-"), array('<?xml-', '<?xml version', '?>', '?><?xml-'), $xml);

		$xml = $xslproc->transformToXML(DOMDocument::loadXML($xml));

		if (empty($pre_add) && isset($prefix) && !strstr($prefix, ' '))
		{
			$xml = preg_replace('@\<(\s*)([^/<>:?][^<>]*?)\>@s', '<'.$prefix.':$2>', $xml);
			$xml = preg_replace('@\<(\s*)/([^<>:]*?)\>@s', '</'.$prefix.':$2>', $xml);
		}


		return str_replace("<"."?xml version=\"1.0\"?".">", '', $xml);
	}

	/*
	 * Smarty plugin
	 * -------------------------------------------------------------
	 * Type:     modifier
	 * Name:     xsl
	 * Purpose:  Transform the variable using the specified XSL file
	 *									or variable.
	 * Arguments:
	 * 	xsl - xsl filename or variable to use
	 *
	 * Example:  {$xmldoc|xsl:"/path/to/myxsl.xsl"}
	 * Author: Brett Zamir (idea from Richard Bateman's SomeXSLTPlugins)
	 * -------------------------------------------------------------
	 */
	public function smarty_modifier_xsl($xml, $xsl = '')
	{
		return $this->prepare_xsl($xml, $xsl);
	}

	/*
	 * Smarty plugin
	 * -------------------------------------------------------------
	 * Type:     modifier
	 * Name:     xml
	 * Purpose:  Transform the variable using the specified XSL file or variable.
	 * Arguments:
	 * 	xml - xml variable or filename to use
	 *
	 * Example:  {$xsldoc|xml:"/path/to/myxml.xml"}
	 * Author: Brett Zamir (idea from Richard Bateman's SomeXSLTPlugins)
	 * -------------------------------------------------------------
	 */
	public function smarty_modifier_xml($xsl, $xml = '')
	{
		return $this->prepare_xsl($xml, $xsl);
	}

	/*
	 * Smarty plugin
	 * -------------------------------------------------------------
	 * Type:     function
	 * Name:     xslt
	 * Purpose:  Parse XML with XSL to produce output.
	 * Parameters:
	 * 	xml: xml document (string or filename) to parse
	 *  xsl: xsl document (string or filename) to parse
	 *  prefix: Optional argument to add a namespace prefix; takes values 'prexform' or 'postxform' as to at which stage the prefix is added
	 * xslprefix: Optional argument to add namespace prefix to XSL stylesheet before transforming (i.e., "xsl" if your stylesheet doesn't already use it)
	 * Author: Brett Zamir (idea from Richard Bateman's SomeXSLTPlugins)
	 *
	 * -------------------------------------------------------------
	 */
	public function smarty_function_xslt($params, &$smarty)
	{
		$xml = $params['xml'];
		$xsl = $params['xsl'];
		$prefix = $params['prefix'];
		$pre_add = $params['preadd'];
		$xsisl = $params['xsisl'];
		$xmlns = $params['xmlns'];
		$xml = $this->prepare_xsl($xml, $xsl, $prefix, $xmlns, $xsisl, $pre_add);
		return $xml;
	}

	/**
	 * Smarty plugin
	 * -------------------------------------------------------------
	 * File:     block.xsl.php
	 * Type:     block
	 * Name:     xsl
	 * Purpose:  transform XML from variable or file using XSLT inside block
	 * Params:
	 * * "xml" - specify a variable or filename with the XML document
	 * Author:   Brett Zamir (idea from Serge Stepanov's XSLT block function)
	 * -------------------------------------------------------------
	 */
	public function smarty_block_xsl($params, $content, &$smarty, &$repeat)
	{
		if ($content != '')
		{
			$xml = $params['xml'];
			$xsl = $content;
			$prefix = $params['prefix'];
			$pre_add = $params['preadd'];
			$xsisl = $params['xsisl'];
			$xmlns = $params['xmlns'];
			return $this->prepare_xsl($xml, $xsl, $prefix, $xmlns, $xsisl, $pre_add);
		}
	}

	/**
	 * Smarty plugin
	 * -------------------------------------------------------------
	 * File:     block.xml.php
	 * Type:     block
	 * Name:     xml
	 * Purpose:  transform XML inside block from XSL variable or file
	 * Params:
	 * * "xsl" - specify a variable or filename with the XSL document
	 * Author:   Brett Zamir (idea from Serge Stepanov's XSLT block function)
	 * -------------------------------------------------------------
	 */
	public function smarty_block_xml($params, $content, &$smarty, &$repeat)
	{
		if ($content != '')
		{
			$xml = $content;
			$xsl = $params['xsl'];
			$prefix = $params['prefix'];
			$pre_add = $params['preadd'];
			$xsisl = $params['xsisl'];
			$xmlns = $params['xmlns'];
			return $this->prepare_xsl($xml, $xsl, $prefix, $xmlns, $xsisl, $pre_add);
		}
	}

	/**
	 * Smarty {cdata} block plugin
	 *
	 * Escape some raw text as CDATA
	 *
	 * @param $params
     * @param $content
     * @param $smarty
     * @param $repeat
	 * @return nothing
	 */
	public function smarty_block_cdata($params, $content, &$smarty, &$repeat)
	{
		if ($content != '' && $this->xml_plain)
		{
			return "<![CDATA[\n".$content."\n]]>\n";
		}
	}


	/**
	 * Smarty {xinclude} block plugin
	 *
	 * Export some text into a file and then include it via an XInclude reference
	 *
	 * @param $params
     * @param $content
     * @param $smarty
     * @param $repeat
	 * @return nothing
	 */
	public function smarty_block_xinclude($params, $content, &$smarty, &$repeat)
	{
		if ($content != '' && $this->xml_plain)
		{
			$this->xincludeset = true;
			$output = "<xi:include";

			$params['href'] = isset($params['file'])?$params['file']:(isset($params['href'])?$params['href']:null);

			$output .= isset($params['href'])?' href="'.$params['href'].'"':'';
			$output .= isset($params['parse'])?' parse="'.$params['parse'].'"':'';
			$output .= isset($params['xpointer'])?' xpointer="'.$params['xpointer'].'"':'';
			$output .= isset($params['encoding'])?' encoding="'.$params['encoding'].'"':'';
			$output .= isset($params['accept'])?' accept="'.$params['accept'].'"':'';
			$output .= isset($params['accept_language'])?' accept-language="'.$params['accept_language'].'"':'';
			$output .= "/>\n";

			if ($smarty->rewrite_docraw_on && !$this->caching && $this->compile_check)
			{
				$file = isset($params['file'])?$params['file']:$params['href'];
				file_put_contents($file, $content);
			}
			return $output;
		}
	}

	/**
	 * Smarty {moveto} block plugin
	 *
	 * Insert some raw text into the html header from anywhere at anytime
	 *
	 * @param $params
     * @param $content
     * @param $smarty
     * @param $repeat
	 * @return nothing
	 */
	public function smarty_block_moveto($params, $content, &$smarty, &$repeat)
	{
	    $comment_style = null;

		// Might have been set in "tag"
		if (isset($params['tplorig']))
		{
			$smarty->currfileblock = $params['tplorig'];
		}
		if(isset($params['target'])){ $target       = $params['target'];} else { $target = null; }
		if(isset($params['file']))  { $fileparam    = $params['file'];}
		if(isset($params['get']))   { $getparam     = $params['get'];}

		// Brett removed this condition: is_string($params['key']) &&
		$key = (isset($params['key']) && strlen($params['key'])>0)
			? $params['key']
			: null;
		$doc_info_comm_style = @$this->doc_info_types[$target]['doc_raw_comment_style'];

        if(isset($params['comment_style']))   { $comment_style     = strtolower($params['comment_style']);}
		$comments = true;

		$externaldocraw = false;
		if (in_array($target, $this->external_doc_raws))
		{
			// Always, always add comments to the stylesheet/script/etc. since otherwise we cannot auto-replace the correct portion of the file
			$externaldocraw = true;
		}

		// The following checks if the type is potentially variable for comment type, and if so, whether the user specified a non-default type
		// If the type, or the user specifies comments to be hidden, make it hidden
		if (($doc_info_comm_style === 'hidden' || $comment_style === 'hidden') && !$externaldocraw)
		{
			$comment_type = 'none'; // This was added for the sake of notes, but as it could cause problems in external documents as in external documents, it will keep writing additional data; therefore, this 'if' does not occur (though if you want to make comments visible within internal doc_raws, you could label an element as being of comment_style 'hidden'
		}
		elseif ($doc_info_comm_style === 'variable')
		{
			// If user chose a comment style
			if (!empty($comment_style)) {
				$comment_type = $comment_style;
			}
			else {
				$comment_type = 'c';
			}
		}
		else {
			$comment_type = $doc_info_comm_style;
		}

		switch ($comment_type) {
			case 'c':
				$comm_begin = '/*';
				$comm_end = '*/';
				break;
			case 'xml':
				$comm_begin = '<!--';
				$comm_end = '-->';
				break;
			case 'none':
			default:
				$comments = false;
			break;
		} // end switch

		// If this is an entity in which there is only the default tplorig and a file attribute, it must be a tab-delimited contents entity (on the next round when contents are available)
		if ($target === 'entity' && $content == '' && (count($params) === 2 || isset($params['get']) && count($params) <= 3))
		{
			$this->entityparams = $params;
			return;
		}
		elseif ($target === 'entity' && $content != '' && strstr($content, "\t"))
		{
			$content = rtrim($content); // Don't want the last one if it exists
			$content = rtrim($content, ';');

			$cont_arr = explode(';', $content); // Tried \n but troublesome
			array_walk($cont_arr, create_function('&$a', '$a = explode("\t", trim($a));'));

			$content = '';
			$entfiles = array();

			if (is_null($getparam))
			{
				$getparams = explode(' ', $this->getentparams);
			}
			else
			{
				$getparams = explode(' ', $getparam);
			}

			$entget = isset ($_GET[$getparams[0]])?$_GET[$getparams[0]]:null;
			$entgotten = @array_search($entget, $getparams);

			if (!$entgotten) {
				$entgotten = 1;
			}

			// Makes an external parameter entity out of the tab-delimited items (or at least the GET-selected ones if more than one);
			// although all will get saved, only the GET selected file (or default 2nd column if no GET) will have
			// its entities replaced later at the end of the outputfilter
			if (isset($fileparam))
			{
				if ($fileparam === 'defaults')
				{
					// This allows easier portability, as one can simply list the file as 'defaults' and not worry about filenames throughout the template
					$params['file'] = $fileparam = $this->entity_files;
				}

				$entfiles = explode('|', $fileparam);
				$ents = $this->param_ent_name; // 'ents';
				for ($j=1, $count2=count($cont_arr[0]); $j < $count2; $j++)
				{
					for ($i=0, $count=count($cont_arr); $i < $count; $i++)
					{
						$entcontents[$entfiles[$j-1]] .= '<!ENTITY '.$cont_arr[$i][0].' "'.$cont_arr[$i][$j].'">'."\n";
					}
				}

				unset($params['file']);
				$params['param'] = true; // Will allow it to be referenced as a parameter entity
				$params['name'] = $ents;
				$params['type'] = 'SYSTEM';
				$params['subtype'] = $entfiles[$entgotten-1];
				$params['tplorig'] = $this->currfileblock;
				$smarty->addInfo($params, true); // Add attribute info for doc_raw since not called on the first run (due to our need to reset the parameters just above)

				$entfiles_nocurr = $entfiles;
				unset($entfiles_nocurr[$entgotten-1]);
				if ($this->external_comments || $this->comments)
				{
					$this->extra_ent_comments .= '<!-- Additional entity files were created and sent to '.implode(',', $entfiles_nocurr).' -->';
				}

				for ($i=0, $count3=count($entcontents); $i < $count3; $i++)
				{
					// server-side entity replacement // && !$setents
					if ($this->entity_replace && $i === $entgotten-1)
					{
						// Don't need the following three lines since using the entity_file_toget below and replacements will be performed on its contents instead of performing the replacements here
						// preg_match_all('@<!ENTITY\s+([^%"]*?)\s+"([^"]*?)"@', $entcontents[$entfiles[$i]], $matches);
						//$this->extra_ent_repl_nm = array_merge((array) $this->extra_ent_repl_nm, (array) $matches[1]);
						//$this->extra_ent_repl_txt = array_merge((array) $this->extra_ent_repl_txt, (array) $matches[2]);
						$this->entity_file_toget = $entfiles[$i]; // This will allow one to manually edit the file as the whole file contents will be gotten later and searched for replacement entities to apply
					}
					$params['file'] = $entfiles[$i];
					$params['dummy'] = 'dummyvar';
					$this->smarty_block_moveto($params, '', $smarty, $repeat);
					$this->smarty_block_moveto($params, $entcontents[$entfiles[$i]], $smarty, $repeat);
				}
			}
			else
			{
				// Build entities into the internal doctype and optionally replace them in the document
				$j = $entgotten;

				for ($i=0, $count=count($cont_arr); $i < $count; $i++)
				{
					$content .= '<!ENTITY '.$cont_arr[$i][0].' "'.$cont_arr[$i][$j].'">'."\n";
				}
				// Might replace this with check of whole doc_source at end of outputfilter so as to catch entities added via doctype doc_raw
				if ($this->entity_replace) {
					// server-side entity replacement
					preg_match_all('@<!ENTITY\s+([^%"]*?)\s+"([^"]*?)"@', $content, $matches);
					$this->extra_ent_repl_nm = array_merge((array) $this->extra_ent_repl_nm, (array) $matches[1]);
					$this->extra_ent_repl_txt = array_merge((array) $this->extra_ent_repl_txt, (array) $matches[2]);
				}
				$params['target'] = 'doctype';
				$this->smarty_block_moveto($params, $content, $smarty, $repeat);
			}
			return;
		}
		elseif ($target === 'entity' && $content !='')
		{
			// Perform entity replacement here (on non-tab-delimited) since if external will not otherwise be checked
			if (isset($params['param']) && $params['param']) { // If an external parameter entity which contains some entities (but not in tab-delimited shorthand--whether due to the fact it was not submitted in this shorthand or whether the tabs were already converted in the last run (see the 'if' above))
				if ($this->entity_replace && (!isset($params['dummy']))) { // server-side entity replacement if not a parameter entity already set above // Had  || $params['subtype'] === $params['file'] within the dummy check, but not necessary since will be checked later due to file being set above
//					preg_match_all('@<!ENTITY\s+([^%"]*?)\s+"([^"]*?)"@', $content, $matches);
//					$this->extra_ent_repl_nm = array_merge((array) $this->extra_ent_repl_nm, (array) $matches[1]);
//					$this->extra_ent_repl_txt = array_merge((array) $this->extra_ent_repl_txt, (array) $matches[2]);
				}
			}
			// If a regular general external entity
			else
			{
				if ($this->entity_replace && isset($params['name']))
				{
					$this->extra_ext_ent_nm[] = $params['name'];
					$this->extra_ext_ent_txt[] = $content;
				}
				else
				{
					// If just blank data, send it to the internal doctype
					$params['target'] = 'doctype';
					$this->smarty_block_moveto($params, $content, $smarty, $repeat);
				}
			}
		}
		// Perform entity replacement here since if external will not otherwise be checked; might also do this for doctype if not replacing from the whole
		//  doc_source at the end of the outputfilter; Might replace this with getting the file attribute of the dtd here to check at end of outputfilter
		//  for the contents of the file (in case the dtd were manually edited)
		elseif (($target === 'dtd' || $target === 'doctype') && $content !='') {
			// server-side entity replacement
			if ($this->entity_replace && (!isset($params['dummy']) || $params['subtype'] === $params['file']))
			{
				preg_match_all('@<!ENTITY\s+([^%"]*?)\s+"([^"]*?)"@', $content, $matches);
				$this->extra_ent_repl_nm = @array_merge((array) $this->extra_ent_repl_nm, (array) $matches[1]);
				$this->extra_ent_repl_txt = @array_merge((array) $this->extra_ent_repl_txt, (array) $matches[2]);
			}
		}

		// If wanted to allow file parameter on internal, could add condition " || $fileparam" with the in_array test, but would also need to fix things with the target name and in the outputfilter to write the contents to a file (e.g., treat a "script" as a "code" there too)

		if ($smarty->rewrite_docraw_on && !$this->caching && $this->compile_check && in_array($target, $this->external_doc_raws))
		{
			// Replace all doc_raw data of this type from the main external stylesheet (or specific stylesheet)
			//  (if caching off, etc.?--doc_raws are currently not being cached--see the register_block lines)
			if ($this->timer_dr_{$target}[$this->currfileblock][$fileparam] == 0)
			{
				if ($fileparam == '' || $target === 'notes')
				{
					$dr_wkeytarget0 = 'dr_'.$target.'_file';
					$dr_wkeytarget = $this->$dr_wkeytarget0;
				}
				else
				{
					$dr_wkeytarget = $this->site_root_public.'/'.$fileparam;
				}
				$file_contents = @file_get_contents($dr_wkeytarget);

				// Replace auto-generated headers and closings for external XSL/XSD files (from doc_raws)
				if ($this->doc_info_types[$target]['doc_raw_prepostfix'])
				{
					$newprefix = $target.'_prefixed';
					$newprefixed = $this->$newprefix;

					$newpostfix = $target.'_postfixed';

					$file_contents = preg_replace('@'.preg_quote($newprefixed).'@s', '', $file_contents);
					$file_contents = preg_replace('@'.preg_quote($this->$newpostfix).'@s', '', $file_contents);
				}
				$file_contents = preg_replace('@'.preg_quote($comm_begin).' Begin '.$this->doc_rawalias.' '.preg_quote($smarty->currfileblock).' .*?end '.$this->doc_rawalias.' '.preg_quote($comm_end).'\n\n@s', '', $file_contents);
//				print $dr_wkeytarget;exit;

				file_put_contents($dr_wkeytarget, $file_contents);
			}
		}

		if ($externaldocraw || $this->print_main_comments || $this->comments)
		{
			if ($content != '' && ($comments || $externaldocraw))
			{
				$content = $this->temp_content.$content.$comm_begin.' '.$smarty->currfileblock.' end '.$this->doc_rawalias.' '.$comm_end."\n\n";
				// Reset beginning comments
				$this->temp_content = '';
			}
			elseif ($comments || $externaldocraw)
			{
				$this->temp_content = $comm_begin.' Begin '.$this->doc_rawalias.' '.$smarty->currfileblock.' '.$comm_end."\n"; // removed $content at end since blank
				$this->timer_dr_{$target}[$this->currfileblock][$fileparam]++;
			}
			else
			{
				$this->temp_content = ''; // Reset beginning comments
			}
		}

		if ($content != '')
		{
			if (isset($target) && strtolower($target) === 'body')
			{
				$smarty->addRawContent($content, $key, $target);
			}
			elseif(isset($target) && strtolower($target) === 'head')
			{
				$smarty->addRawHeader($content, $key, $target);
			}
			elseif(isset($target) && in_array(strtolower($target), array_keys($this->doc_info_types)))
			{
				@$smarty->addRaw($content, $key, $target, $fileparam);
			}
		}
		elseif ($target === 'entity' && (count($params) === 1))
		{
		/*
			$params['tplorig'] = $this->currfileblock;
			$smarty->addInfo($params, true); // Add attribute info for doc_raws
		*/
		}
		else
		{

//			 if (!isset($params['tplorig'])) { // Might have been set in "tag"
				$params['tplorig'] = $this->currfileblock;
//			}
			// Add attribute info for doc_raws
			$smarty->addInfo($params, true);
		}
	}

	/**
	 * Smarty {info} function plugin
	 *
	 * Insert html header items from anywhere at anytime
	 *
	 * @return nothing
	 * @todo the functionality is doubled ! search for "$repeatno = false;" and look some lines after that
	 */
	public function smarty_function_info($params, &$smarty)
	{
		// Overridden method below will have already added tplorig to this
		$smarty->addInfo($params);

		$this->setContentType();
		/*
		// Adding the following to make the type information available to the templates
		if ($this->HTTP_ACCEPT == '' && !$smarty->keepapptype) {

			/**
			* However, this may be overridden (and thus inconsistent) if relying
			* on a fallback setting in getdoctypesignature to change the application_xml
			* value--e.g., to XHTML (and thus change setcontenttype results which determines
			* the actual header sent)
			*
			*/
			$smarty->assign('http_accept', $this->HTTP_ACCEPT);
		//}


	}

	/**
     * Smarty {tagc} Block function
     *
     * @param $params
     * @param $content
     * @param $smarty
     * @param $repeat
     */
	public function smarty_block_tagc($params, $content, &$smarty, &$repeat)
	{
		if ($content != '')
		{
			$params['_con'] = $content;
			return $this->smarty_function_tag($params, 	$smarty);
		}
	}

	/**
     * Smarty {tag} function
     *
     * @param $params
     * @param $smarty
     *
     * @return $ret
     */
	public function smarty_function_tag($params, &$smarty)
	{
		if ($this->styles_to_css)
		{
			$styl_array = array('ecss'=>'estyle', 'eclcss'=>'eclstyle', 'clcss'=>'clstyle', 'icss'=>'istyle');
			foreach ($styl_array as $att => $val)
			{
				if (!isset($params[$att]) && isset($params[$val]))
				{
					$params[$att] = $params[$val];
				}
				unset($params[$val]);
			}
			if (isset($params['style'])) {
				// Assume a plain style tag is referring to just that id
				$params['icss'] .= $params['style'];
			}
			unset($params['style']);
		}

		$closing = false;
		$selfclose = '';
		$pseudoel = '';
		$pseudoel2 = '';
		$pref = '';
		foreach ($params as $param => $value) {
			switch ($param) {
				case 'c':
					return '</'.$value.'>';
				case '_con':
					$contents = $value;
					unset($params[$param]);
					break;
				case 'comm':
					// Not sure anymore what I was thinking of doing with this...
					$selfclose = '--';
					$commentdata = $value; // No other attributes
					unset($params[$param]);
					break;
				case 'tag':
				case 'e':
					$closing = true;
					$tag = $value;
					unset($params[$param]);
					break;
				case 'sc':
					$selfclose = ' /';
					$tag = $value;
					unset($params[$param]);
					break;
				case 'o':
					$tag = $value;
					unset($params[$param]);
					break;
				case 'estyle':
					if (isset($params['pseudo']))
					{
						$pseudoel = ':'.$params['pseudo'];
					}
					$style .= '__tag__'.$pseudoel.' {'.$value."}\n";
					$params['target'] = 'style';
					unset($params[$param]);
					break;
				case 'eclstyle':
					$pref = '__tag__';
				case 'clstyle':
					if (isset($params['pseudo']))
					{
						$pseudoel2 = ':'.$params['pseudo'];
					}
					$class = $params['class'];
					if (empty($class))
					{
						self::$class_ctr++;
						$class = $this->class_prefix.self::$class_ctr;
					}
					if (isset($attrs['class']))
					{
						// Can have multiple class names if run before
						$attrs['class'] .= ' ';
					}
					$attrs['class'] .= $class;
					$style .= $pref.'.'.$class.$pseudoel2.' {'.$value."}\n";
					$pref = '';
					$params['target'] = 'style';
					break;
				case 'istyle':
					$id = $params['id'];
					if (isset($attrs['id']))
					{
						// If set before by icss, use that id instead
						$id = $attrs['id'];
					}
					elseif (empty($id))
					{
						self::$id_ctr++;
						$id = $this->id_prefix.self::$id_ctr;
					}
					$attrs['id'] = $id; // Overwrite as should have only one id
					$style .= '#'.$id.' {'.$value."}\n";
					$params['target'] = 'style';
					break;
				case 'ecss':
					if (isset($params['pseudo']))
					{
						$pseudoel = ':'.$params['pseudo'];
					}
					$style .= '__tag__'.$pseudoel.' {'.$value."}\n";
					$params['target'] = 'css';
					unset($params[$param]);
					break;
				case 'eclcss':
					$pref = '__tag__';
				case 'clcss':
					if (isset($params['pseudo']))
					{
						$pseudoel2 = ':'.$params['pseudo'];
					}
					$class = $params['class'];
					if (empty($class))
					{
						self::$class_ctr++;
						$class = $this->class_prefix.self::$class_ctr;
					}
					if (isset($attrs['class']))
					{
						// Can have multiple class names if run before
						$attrs['class'] .= ' ';
					}
					$attrs['class'] .= $class;
					$style .= $pref.'.'.$class.$pseudoel2.' {'.$value."}\n";
					$pref = '';
					$params['target'] = 'css';
					break;
				case 'icss':
					$id = $params['id'];
					if (isset($attrs['id']))
					{
						// If set before by istyle, use that id instead
						$id = $attrs['id'];
					}
					elseif (empty($id))
					{
						self::$id_ctr++;
						$id = $this->id_prefix.self::$id_ctr;
					}
					$attrs['id'] = $id; // Overwrite as should have only one id
					$style .= '#'.$id.' {'.$value."}\n";
					$params['target'] = 'css';
					break;
				case 'class':
					if (!isset($params['clstyle']) && !isset($params['eclstyle'])
						&& !isset($params['clcss']) && !isset($params['eclcss']))
					{
						$attrs['class'] = $value;
					}
					break;
				case 'id':
					if (!isset($params['istyle']) && !isset($params['icss']))
					{
						$attrs['id'] = $value;
					}
					break;
				case 'css':
					$param = 'style'; // Thus one can easily convert an "icss" or "clcss", etc. into a "css" for testing templates
					$attrs['style'] = $value;
					unset($params[$param]);
					break;
				case 'styleid':
					$params['id'] = $value;
					break;
				case 'pseudo':
				case 'key':
				case 'file':
				case 'tplorig':
					// We could conceivably add something to tplorig for writing in the file (or head) to indicate that it originated from a "tag" (assuming it is also checked for in the smarty_doc_raw_block function so that rewrites will first remove the old content correctly)
					break;
				default:
					$attrs[$param] = $value;
					unset($params[$param]); // Unset everything (besides styleid (which is converted into an 'id' for the style tag) file (used by css/style if shuffling off to a specific file rather than the site's main file), key (if replacing an earlier item--though this hasn't been implemented as of yet), tplorig (added by the overridden compiler method for the sake of comments indicating the source file of this), 'pseudo' (which defines a pseudo class in conjunction with the right-named class), 'id' and 'class' (which are unset later as they may be needed within a subsequent run of the loop), and newly added target) for the sake of doc_raw; if one wants other attributes to go to the style/css tags (instead of the result tag of this {tag} function), add a "case" for them above along the lines of 'file'--keeping in mind that it would not be good to have attributes which could conceivably be used by the tag(c) elements using this feature--also, the "target" attribute could not be added in this way to the css link, as it is being used by this function to get the styles working in the first place (via doc_raw below)
					break;
			}
		}

		/**
		 * These must be unset (if they exist), outside of the switch
		 */
		unset($params['class']);
		unset($params['pseudo']);
		unset($params['id']);
		unset($params['eclstyle']);
		unset($params['clstyle']);
		unset($params['istyle']);
		unset($params['eclcss']);
		unset($params['clcss']);
		unset($params['icss']);
		$style = str_replace('__tag__', $tag, $style);

		$content = $params['_content'] = $style;
		$repeat = true;
		$repeatno = false;

		$smarty->smarty_block_moveto($params, '', $smarty, $repeat);
		$smarty->smarty_block_moveto($params, $content, $smarty, $repeatno);

		// Adding the following to make the type information available to the templates
		if ($this->HTTP_ACCEPT == '' && !$this->keepapptype)
		{
			$this->setContentType();
			/**
			* However, this may be overridden (and thus inconsistent) if
			* relying on a fallback setting in getdoctypesignature to change the
			* application_xml value--e.g., to XHTML (and thus change
			* setcontenttype results which determines the actual header sent)
			*/
			$smarty->assign('http_accept', $this->HTTP_ACCEPT);
		}

		$finalattrs = '';
		foreach ((array) $attrs as $a=>$v)
		{
			$finalattrs .= " {$a}=\"{$v}\"";
		}

		$close = '';
		if ($closing)
		{
			$close = '</'.$tag.'>';
		}
		$ret = '<'.$tag.$commentdata.$finalattrs.$selfclose.'>'.$contents.$close;

		return $ret;

	}

	/**
	 * setContentType $this->HTTP_ACCEPT
	 *
	 * @access protected
	 */
	public function setContentType()
	{
		// first check if type is XHTML or not and if so, whether the browser accepts application/xhtml+xml content type
		// Assume that a version attribute (used  for auto-setting a doctype for XHTML when no doc_info['dtd'] is set--e.g.,
		// if it is XHTML version 1.1) means it is XHTML

		//print $this->doc_info['dtd']['family'];exit;

		$docinfodtdlevel = isset($this->doc_info['dtd']['level'])?$this->doc_info['dtd']['level']:null;
		// Add this to the beginning?:  $this->doc_info['dtd']['level'] === '1.1+' || $this->doc_info['dtd']['level'] >= '1.1' ||
		if ((!$this->xhtmldebug && (($docinfodtdlevel === '1.1+') || $docinfodtdlevel >= '1.1')) || ($this->xml_plain &&
				($this->application_xml === 'XHTML' ||
					(isset($this->doc_info['dtd']['family']) && $this->doc_info['dtd']['family'] === 'XHTML') ||
					@array_key_exists('v', $this->doc_info['html']))))
		{


			if (!$this->xhtmldebug && ($docinfodtdlevel === '1.1+' || $docinfodtdlevel >= '1.1')) {
				$this->xml_plain = true; // Needed to allow XML attributes on XHTML (e.g., xmlns) to be auto-added even
										// if not specified as such via the API
			}

			if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml'))
			{
				$this->HTTP_ACCEPT = 'application/xhtml+xml';
			}
			/* Explorer seemed to need this when I was testing... (scratches head)
			elseif ($this->force_html) {
				$this->HTTP_ACCEPT = 'text/html';
			}
			elseif ($this->force_css) {
				$this->HTTP_ACCEPT = 'text/css';
			}
			*/
			elseif (stristr($_SERVER['HTTP_ACCEPT'], 'application/xml'))
			{
				$this->HTTP_ACCEPT = 'application/xml';
			}
			elseif (stristr($_SERVER['HTTP_ACCEPT'], 'text/xml'))
			{
				$this->HTTP_ACCEPT = 'text/xml';
			}
			//Send Opera 7.0+ application/xhtml+xml
			elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Opera ') || stristr($_SERVER['HTTP_USER_AGENT'], 'Opera/')) {

				preg_match('@Opera/(\d)@', $_SERVER['HTTP_USER_AGENT'], $matches);
				if (isset($matches[1]) && $matches[1] >= 7) {
					$this->HTTP_ACCEPT = 'application/xhtml+xml';
				}
				else {
					$this->HTTP_ACCEPT = 'text/html';
				}
}
			//Send everyone else text/html (even though XHTML)
			else
			{
				$this->HTTP_ACCEPT = 'text/html';

			}
		}
		elseif ($this->xml_plain && !$this->application_xml)
		{
			$this->HTTP_ACCEPT = 'text/xml';
		}
		elseif ($this->xml_plain && $this->application_xml)
		{
			$this->HTTP_ACCEPT = 'application/xml';
		}
		else
		{
			$this->HTTP_ACCEPT = 'text/html';
		}
	}

	/**
	 * doc_raw_head_build
	 *
	 * @param array $target
	 * @param bool $attribs
	 * @param bool $savecomments
	 *
	 * @return string $doc_source
	 */
	public function doc_raw_head_build ($target, $attribs = false, $savecomments = false)
	{
	    $doc_source = null;
	    $tpl_pre    = null;
	    $tpl_post   = null;
	    $attrs      = null;

		if (isset($this->doc_raw[$target]['main']))
		{
			$targetmeta_attr = str_replace('_', '-', str_replace('__', ':', $this->doc_info_types[$target]['meta_attribs'])); // Prepare for comparison

            $contentcount = null;
            $hidecount = null;
			$rawtoadd = '';
			$attr = '';
			$temppre = 'dr_'.$target.'_pre';
			$temppostpre = 'dr_'.$target.'_postpre';
			$temppost = 'dr_'.$target.'_post';

			// Sort for the sake of manually added keys
			ksort($this->doc_raw[$target]['main']);

			foreach ($this->doc_raw[$target]['main'] as $rawid => $rawkeys)
			{
				foreach ($rawkeys as $a => $v)
				{
					if ($a === '_content')
					{
						if (isset($this->nexthide) && $this->nexthide == false)
						{
							$rawtoadd .= $v."\n";
							unset($this->nexthide);
						}
						$contentcount++;
					}
					elseif ($a === 'hide')
					{
						// Causes the next subsequent comment to be hidden
						$this->nexthide = true;
						$hidecount++;
					}
					elseif ($a === 'tplorig')
					{
						$tpl_list[] = $v;
					}
					elseif ($a === 'prefix')
					{
						$this->pi_prefix = $v;
					}
					elseif ($attribs && !empty($v) && !@in_array($a, $targetmeta_attr))
					{
						$attr[$a] = $v;
					}
				}
			}
			if(isset($this->nexthide)){ unset($this->nexthide); }
			if ($hidecount > 0 && $hidecount === $contentcount)
			{
				// If all content is to be hidden, don't print anything including comments
				return;
			}
			if (!empty($tpl_list))
			{
				$tpl_list = array_unique($tpl_list);
				natcasesort($tpl_list);
				if ($this->head_comments || $this->comments)
				{
					$tpl_pre = '<!-- Begin '.$this->doc_rawalias.' '.$target.' from template(s) '.implode(', ', $tpl_list)." -->\n";
					$tpl_post = '<!-- End '.$this->doc_rawalias.' '.$target.' from template(s) '.implode(', ', $tpl_list)." -->\n";
				}
			}

			if (!empty($attr))
			{
				foreach ($attr as $a => $v)
				{
					if (!empty($v))
					{
						$attrs .= " {$a}=\"{$v}\"";
					}
				}
			}
			if ($savecomments)
			{
				$doc_source .= $this->$temppre.$attrs.$this->$temppostpre;
				$tpltargetpre = $target.'_tpl_pre';
				$tpltargetpost = $target.'_tpl_post';
				$this->$tpltargetpre = $tpl_pre;
				$this->$tpltargetpost = $tpl_post;
			}
			else
			{
				$doc_source .= $tpl_pre.$this->$temppre.$attrs.$this->$temppostpre;
			}
			$raw = str_replace(array('[[[', ']]]'), array('{', '}'), $rawtoadd);
			if ($savecomments)
			{
				$doc_source .= $raw.$this->$temppost;
			}
			else
			{
				$doc_source .= $raw.$this->$temppost.$tpl_post;
			}
			return $doc_source;
		}
	}

	/**
     * doc_raw_build
     *
     * @param array $target
     * @param string $exclude_in_xml
     * @param string $pub_or_hide
     * @param bool $hidden
     * @param bool $print_ext
     * @param bool $both_main_indiv
     */
	public function doc_raw_build ($target, $exclude_in_xml='', $pub_or_hide='public', $hidden = false, $print_ext = true, $both_main_indiv = true)
	{
	    $doc_source = null;

		$targetmeta_attr = str_replace('_', '-', str_replace('__', ':', $this->doc_info_types[$target]['meta_attribs']));
		$ds_attrs_arr = $ds_attrs_arr2 = $rootattrs = array();

		if (isset($this->doc_raw[$target]))
		{
			$main_ext_doc = '';
			$indiv_ext_doc = array();
			$main_attribs = $indiv_attribs = array();

			foreach ($this->doc_raw[$target] as $doc_raw_file => $rawfileparam)
			{
				// Although this may be repeated if there are multiple doc_raws for the same file, this will at least ensure that any numeric keys are put in order
				ksort($this->doc_raw[$target][$doc_raw_file]);
			}

			foreach ($this->doc_raw[$target] as $doc_raw_file => $rawfileparam)
			{
				foreach ($rawfileparam as $rawkey)
				{
					foreach ($rawkey as $a => $v)
					{
//					if ($a === 'key' && $v == 1) {print $rawkey['_content'];exit;}
//					if ($a === 'key' && $v == 2) {print $rawkey['_content'];exit;}

						if (!isset($rawkey['key'])) {$key = 0;}
						else {$key = $rawkey['key'];}

						if (!empty($v))
						{
							if ($a === 'tplorig')
							{
								$tpl_list[$doc_raw_file][] = $v;
							}
							elseif ($a === 'hide')
							{
								$hide[$doc_raw_file] = true; // Make sure comments, etc. don't show up in the main page
								$pub_or_hides[$doc_raw_file] = 'hidden'; // Reference hidden directory
							}
							elseif ($a === 'target' || $a === 'key')
							{
								// Can these be added to meta_attribs array so that this catch is not necessary?
							}
							elseif ($doc_raw_file === 'main' && $a != '_content')
							{
								// Allows duplicate attribute use to be overwritten (will use latest addition)
								$main_attribs[$a] = $v;
							}
							elseif ($a != '_content')
							{
								$indiv_attribs[$doc_raw_file][$a] = $v; // Allows duplicate attribute use to be overwritten (will use latest addition)
								if (($a === 'xform' && $v) || ($target === 'xsl' && $this->xform_all))
								{
									$this->xsl[] = $doc_raw_file;
									$this->pre_xform[] = true;
								}
							}
							else { // i.e., $a === '_content'
								if ($doc_raw_file === 'main')
								{
									$main_ext_doc .= $v; // Took off \n
								}
								elseif ($doc_raw_file === 'head')
								{
									// Placeholder for adding element to head equivalent
								}
								elseif ($doc_raw_file != '')
								{
									$indiv_ext_doc[$doc_raw_file] .= $v;
								}
							}
						}
					}
				}
			}

			if (($this->external_comments || $this->comments) && !empty($tpl_list))
			{
				foreach($tpl_list as $tplfile => $tplitem)
				{
					$tplitem = array_unique($tplitem);
					natcasesort($tplitem);
					$tpl_pre[$tplfile] = '<!-- Begin '.$this->doc_rawalias.' '.$target.' '.implode(', ', $tplitem).' from template '." -->\n";
					$tpl_post[$tplfile] = '<!-- End '.$this->doc_rawalias.' '.$target.' '.implode(', ', $tplitem).' from template '." -->\n";
				}
			}
			$targetprefixed = $target.'_prefixed';
			$targetpostfixed = $target.'_postfixed';
			$tgtprefixed = $this->$targetprefixed;

			if ($this->rewrite_docraw_on && !$this->caching && $this->compile_check && !empty($main_ext_doc))
			{
				$main_ext_doc = str_replace(array('[[[', ']]]'), array('{', '}'), $main_ext_doc);
				$drtargetfile = 'dr_'.$target.'_file';

				$file_contents = @file_get_contents($this->$drtargetfile);
				// prepend so that designer is forced to see effects of cascading of other preexisting main styles on that stylesheet
				file_put_contents($this->$drtargetfile, $tgtprefixed.$main_ext_doc.$file_contents.$this->$targetpostfixed);
			}


			if ($this->rewrite_docraw_on && !$this->caching && $this->compile_check && !empty($indiv_ext_doc))
			{
				foreach ($indiv_ext_doc as $file => $filevalue)
				{
					if ($pub_or_hides[$file] === 'hidden')
					{
						$siterootpuborhide = 'site_root_hidden';
					}
					else
					{
						$siterootpuborhide = 'site_root_'.$pub_or_hide;
					}
					$filevalue = str_replace(array('[[[', ']]]'), array('{', '}'), $filevalue);
					$file_contents = @file_get_contents($file);
					file_put_contents($this->$siterootpuborhide.'/'.$file, $tgtprefixed.$filevalue.$file_contents.$this->$targetpostfixed);
					$xsisls[] = $file;
				}
			}

			$drtargetpre = 'dr_'.$target.'_pre';
			$drtargetpost = 'dr_'.$target.'_post';
			$drtargetfilesrc = 'dr_'.$target.'_file_src';
			// If some content for the main site's file has been added
			if (!empty($main_ext_doc)) {
				if (!$hide['main'] && (!$hidden || $hidden === 'skippre'))
				{
					// Skipped where opening tag should not be created (e.g., 'xsd' filling in the root element's data)
					if ($hidden !== 'skippre' && $print_ext)
					{

						$doc_source .= $tpl_pre['main'].$this->$drtargetpre.$this->$drtargetfilesrc.'"';
					}
					if ($print_ext)
					{
						foreach ($main_attribs as $a => $v)
						{
							if (!empty($v))
							{
								// Not in meta_attribs as a key or value
								if (!@in_array($a, $targetmeta_attr) && !@in_array($a, $this->doc_info_types[$target][$exclude_in_xml]))
								{
									$ds_attrs .= " {$a}=\"{$v}\"";
									if ($target === 'xsd')
									{
										$ds_attrs_arr[$a] = $v;
									}
								}
								elseif ($a === 'xsi:schemaLocation')
								{
									$xsimain = $v;
								}
								elseif ($a === 'xsi:noNamespaceSchemaLocation')
								{
									$xsimain_nns = $v;
								}
								elseif ($a === 'rel' && $v === 'alternate stylesheet')
								{
									$ds_attrs_arr['alternate'] = 'yes';
								}
							}
							if ($target == 'xsl' && ($main_attribs['xform'] || $this->xform_all))
							{
								$this->xsl[] = $this->dr_xsl_file;
								$this->pre_xform[] = true;
							}
						}
						if ($target !== 'xsd')
						{
							$doc_source .= $ds_attrs;
						}
					}
					if ($hidden !== 'skippre' && $print_ext)
					{
						$doc_source .= $this->$drtargetpost.$tpl_post['main'];
					}
				}
			}

			if (($both_main_indiv || empty($main_ext_doc)) && !empty($indiv_ext_doc))
			{
				if (!$hidden || $hidden === 'skippre')
				{
					$keys = array_keys($this->doc_raw[$target]);
					foreach ($keys as $file)
					{
						if ($file != 'main' && !$hide[$file])
						{
					 		if ($hidden !== 'skippre')
					 		{
								$thisdrtargetpre = $this->$drtargetpre;
								if ($print_ext)
								{
									$doc_source .= $tpl_pre[$file].$thisdrtargetpre.$file.'"';
								}
								else
								{
									$targetfiles = $target."_files";
									$this->{$targetfiles}[$file] = $file;
								}
							}
							if ($print_ext && !empty($indiv_attribs))
							{
								$ds_attrs2 = '';
								foreach ($indiv_attribs[$file] as $a => $v)
								{
									if (!@in_array($a, $targetmeta_attr) && !empty($v) && !@in_array($a, $this->doc_info_types[$target][$exclude_in_xml]))
									{
										$ds_attrs2 .= " {$a}=\"{$v}\"";
										if ($target === 'xsd')
										{
											$ds_attrs_arr2[$a] = $v;
										}
									}
									elseif ($a == 'xsi:schemaLocation')
									{
										$xsiindiv[] = $v;
									}
									elseif ($a == 'xsi:noNamespaceSchemaLocation')
									{
										// Overwrite any others, since should only be one
										$xsiindiv_nns = $v;
									}
									elseif ($a === 'rel' && $v === 'alternate stylesheet')
									{
										$ds_attrs2 .= " alternate=\"yes\"";
									}
								}
							}
							if ($target !== 'xsd')
							{
								$doc_source .= $ds_attrs2;
							}
							if ($hidden !== 'skippre' && $print_ext)
							{
								$doc_source .= $this->$drtargetpost.$tpl_post[$file];
							}
						}
					}
				}
			}

			$tplpretarget = 'tpl_pre_'.$target;
			$tplposttarget = 'tpl_post_'.$target;

			$this->$tplpretarget = $tpl_pre; // Store it so it can be used in the outputfilter if needed
			$this->$tplposttarget = $tpl_post; // Store it so it can be used in the outputfilter if needed
		}

		if (($target === 'xsd' || $target === 'root'))
			{
			// && $this->xml_plain (took this condition out since 'html''s attributes still need to be created regardless of whether it is XML plain or not)
			if (isset($this->doc_info['xsd']))
			{
				foreach ($this->doc_info['xsd'] as $filexsd)
					{ // Just treat as if another XSD
					foreach ($filexsd as $a => $v)
					{
						if (!@in_array($a, $targetmeta_attr) && !empty($v) && !@in_array($a, $this->doc_info_types[$target][$exclude_in_xml]))
						{
							$ds_attrs_arr2[$a] = $v;
						}
						elseif ($a == 'xsi:schemaLocation')
						{
							$xsiindiv[] = $v;
						}
						elseif ($a == 'xsi:noNamespaceSchemaLocation')
						{
							// Overwrite any others, since should only be one
							$xsiindiv_nns = $v;
						}
						elseif ($a === 'tplorig')
						{
							if ($this->external_comments || $this->comments)
							{
								$this->docpre_xsdcomm[] = '<!-- Begin '.$this->doc_infoalias.' xsd from template '.$v." -->\n";
								$this->docpost_xsdcomm[] = '<!-- End '.$this->doc_infoalias.' xsd from template '.$v." -->\n";
							}
						}
					}
				}
			}
			$ds_attrs_arr = array_merge($ds_attrs_arr, $ds_attrs_arr2);

			// Array from doc_info['root'] or ['html']
			$rootattrs = @array_merge($ds_attrs_arr, $this->rootattrs);


			// If it works, go with the newer, larger array; otherwise, go on to use the original value
			if ($rootattrs)
			{
				$ds_attrs_arr = $rootattrs;
			}
			foreach ($ds_attrs_arr as $a => $v)
			{
				$doc_source .= " {$a}=\"{$v}\"";
			}
			if (isset($xsimain_nns))
			{
				$doc_source .= ' xsi:noNamespaceSchemaLocation="'.$xsimain_nns.'"'; // There can be only one noNamespaceSchemaLocation
			}
			elseif (isset($xsiindiv_nns))
			{
				$doc_source .= ' xsi:noNamespaceSchemaLocation="'.$xsiindiv_nns.'"';
			}
			// if set by root doc_info (cannot be set by individual xsd's, since there can only be one)
			elseif (isset($this->root_nns_xsd)) {
				$doc_source .= ' xsi:noNamespaceSchemaLocation="'.$this->root_nns_xsd.'"';
			}

			if (!empty($main_ext_doc) || !empty($indiv_ext_doc) || isset($xsisls) || isset($this->extra_xsds) || isset($this->doc_info['xsd']))
			{
				$doc_source .= ' xsi:schemaLocation="';
				if (!empty($main_ext_doc))
				{
					if ($xsimain)
					{
						// If the templater specified a xsi:schemaLocation attribute, use that instead
						$doc_source .= $xsimain.' ';
					}
					// Main XSD should be automatic (the attribute for schemaLocation is for additional ones)
					$doc_source .= $this->dr_xsd_public_file;
				}
				if (!empty($indiv_ext_doc) || isset($xsiindiv))
				{
					if (!empty($main_ext_doc))
					{
						$doc_source .= ' ';
					}
					$doc_source .= implode(' ', $xsiindiv);
				}
				if (isset($xsisls)) {
					if (!empty($main_ext_doc) || !empty($indiv_ext_doc))
					{
						$doc_source .= ' ';
					}
					$doc_source .= implode(' ', $xsisls);
				}
				if (isset($this->extra_xsds))
				{
					if (!empty($main_ext_doc) || !empty($indiv_ext_doc) || isset($xsisls))
					{
						$doc_source .= ' ';
					}
					$doc_source .= implode(' ', $this->extra_xsds);

					if ((!isset($this->doc_info['xsd']) || !isset($this->doc_raw['xsd'])) && !array_key_exists('xmlns:xsi', $this->doc_info['root']))
					{
						$doc_source .= '" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance';
					}
				}
				$doc_source .= '"';
			}
		}

		return $doc_source;
	}


	/**
	 * SmartyDoc outputfilter plugin
	 *
	 * Create qualified document using previously collected user information
	 *
	 * @param $source
	 * @param $smarty
	 *
	 * @return string document
	 */
	public function smarty_outputfilter_SmartyDoc($source, &$smarty)
	{
        $doc_source     = null;

		if (!empty($smarty->doc_info) || !empty($smarty->doc_raw) || $this->use_auto)
		{
			if (isset($this->headers_ran) && !$this->headers_ran)
			{
				// Get content type here so can detect whether to serve stylesheets, etc. in XML style given that some browsers that don't support this style.
				$this->setContentType();
			}
			$indent = $smarty->getIndent();
			$_doc_info  =& $smarty->doc_info;

			if ($this->xml_plain && $this->HTTP_ACCEPT === 'application/xhtml+xml' || $this->HTTP_ACCEPT === 'application/xml' || $this->HTTP_ACCEPT === 'text/xml')
			{
				$this->dr_script_postpre = $this->dr_script_postpre_xml;
				$this->dr_script_post = $this->dr_script_post_xml;
				$this->dr_style_postpre = $this->dr_style_postpre_xml;
				$this->dr_style_post = $this->dr_style_post_xml;
			}

			 // Override add_openclose if this is clearly a plain XML document (e.g., a DTD but with no family)
			if ( isset($_doc_info['dtd']['family']) and isset($_doc_info['dtd']['type']) and
			     ($_doc_info['dtd']['family'] == '' OR $_doc_info['dtd']['family'] == 'XML')
			   )
			{
				$this->add_openclose = false;
			}

			// process modules
			$module_content = array('head_pre'=>'', 'head_post'=>'', 'body_pre'=>'', 'body_post'=>'');

			foreach ($this->doc_modules as $module)
			{
				$module->onDocStart();

				$mod_content = $module->onHeadStart();
				if (!empty($mod_content))
				{
					$module_content['head_pre'] .= "\n" . $mod_content . "\n";
				}

				$mod_content = $module->onHeadEnd();
				if (!empty($mod_content))
				{
					$module_content['head_post'] .= "\n" . $mod_content . "\n";
				}
				$mod_content = $module->onBodyStart();
				if (!empty($mod_content))
				{
					$module_content['body_pre'] .= "\n" . $mod_content . "\n";
				}
				$mod_content = $module->onBodyEnd();
				if (!empty($mod_content))
				{
					$module_content['body_post'] .= "\n" . $mod_content . "\n";
				}
				$mod_content = $module->onDocEnd();
			}


			$extcomments = ($this->external_comments || $this->comments);

			if (isset($_doc_info['bom']))
			{
				$doc_source .= $_doc_info['bom'][_content];
			}

			// process 'xml' (XML Declaration) doc info
			if (isset($_doc_info['xml']) && $this->HTTP_ACCEPT !== 'text/html')
			{
				foreach ($_doc_info['xml'] as $a=>$v)
				{
					// Version should be first
					if ($a == 'version')
					{
						$docadd = " {$a}=\"{$v}\"";
					}
					elseif ($a === 'tplorig')
					{
						if ($extcomments)
						{
							// Only a "post" item, since XML Declaration should not have whitespace or any content before it
							$docpost_xml = '<!-- End above doc_info from template '.$v." -->\n";
						}
					}
					elseif (!empty($v))
					{
						$docadd2 .= " {$a}=\"{$v}\"";
					}
				}
				if ($this->assume_standalone && !isset($_doc_info['xml']['standalone']) && !isset($_doc_info['dtd']) && !isset($this->doc_raw['dtd']))
				{
					$docadd2 .= ' standalone="yes"';
				}
				$doc_source .= '<'.'?xml'.$docadd.$docadd2;
				$doc_source .= '?'.">\n".$docpost_xml;
			}
			elseif ($this->xml_auto_declare && $this->xml_plain && $this->HTTP_ACCEPT !== 'text/html')
			{
				$doc_source .= '<'.'?xml version="1.0"';
				if ($this->assume_standalone)
				{
					$doc_source .= ' standalone="yes"';
				}
				$doc_source .= '?'.">\n";
				if ($extcomments)
				{
					$doc_source .= '<!-- End above auto add_openclose xml -->'."\n";
				}
			}
			if ($this->show_whitespace_comments && isset($this->whitespace_comment_type))
			{
				$ws_geturl = $this->whitespace_get_url;
				switch ($this->whitespace_comment_type)
				{
					case 'all':
						$doc_source .= '<!-- You can remove just some whitespace by setting "'.$ws_geturl.'" in the URL equal to "some" or remove no whitespace by setting it to "none" -->'."\n";
						break;
					case 'some':
						$doc_source .= '<!-- You can remove all whitespace entirely by setting "'.$ws_geturl.'" in the URL equal to "all" or remove no whitespace by setting it to "none" -->'."\n";
						break;
					case 'none':
						$doc_source .= '<!-- You can remove just some whitespace by setting "'.$ws_geturl.'" in the URL equal to "some" or remove all whitespace by setting it to "all" -->'."\n";
						break;
				}
			}

			if (isset($_doc_info['robots']) && $this->xml_plain)
			{
				foreach ($_doc_info['robots'] as $a=>$v)
				{
					if ($a === 'tplorig')
					{
						if ($extcomments)
						{
							$docpre_robots = '<!-- Begin '.$this->doc_infoalias.' robots from template '.$v." -->\n";
							$docpost_robots = '<!-- End '.$this->doc_infoalias.' robots from template '.$v." -->\n";
						}
					}
					// Place index first (not sure if its necessary, but it seems to be convention)
					elseif ($a === 'index')
					{
						$doc_src_robots1 = "{$a}=\"{$v}\"";
					}
					elseif (!empty($v))
					{
						$doc_src_robots2 .= " {$a}=\"{$v}\"";
					}
				}
				$doc_source .= $docpre_robots.'<'.'?robots '.$doc_src_robots1.$doc_src_robots2.'?'.">\n".$docpost_robots;
			}

			// process 'pi' (processing instruction) doc info
			if (isset($_doc_info['pi']) && $this->xml_plain)
			{
				foreach ($_doc_info['pi'] as $pi)
				{
					$doc_src_pi = '';
					$doc_src_picontent = '';
					$docpiprefix = '';
					foreach ($pi as $a=>$v)
					{
						if ($a === 'tplorig')
						{
							if ($extcomments)
							{
								$docpre_pi = '<!-- Begin '.$this->doc_infoalias.' pi from template '.$v." -->\n";
								$docpost_pi = '<!-- End '.$this->doc_infoalias.' pi from template '.$v." -->\n";
							}
						}
						elseif ($a === 'prefix')
						{
							$docpiprefix = $v;
						}
						elseif ($a === '-content')
						{
							$doc_src_picontent = $v;
						}
						elseif (!empty($v))
						{
							$doc_src_pi .= " {$a}=\"{$v}\"";
						}
					}
					if ($doc_src_picontent != '')
					{
						$doc_src_picontent = ' '.$doc_src_picontent;
					}
					$doc_source .= $docpre_pi.'<'.'?'.$docpiprefix.$doc_src_pi.$doc_src_picontent.'?'.">\n".$docpost_pi;
				}
			}
			if ($this->xml_plain && isset($this->doc_raw['pi']['main']))
			{
				// Note that the following could not be merged with doc_raw_head_build, as the latter assumed that independent items would be merged into one (and for processing instructions, there could be more than one)
				$this->dr_pi_pre = ' ';
				ksort($this->doc_raw['pi']['main']);
				foreach ($this->doc_raw['pi']['main'] as $rawid => $rawkeys)
				{
					$pi_attrs = '';
					foreach ($rawkeys as $a => $v)
					{
						if ($a === '_content')
						{
							$v = str_replace(array('[[[', ']]]'), array('{', '}'), $v);
							$doc_source .= $v."\n?>\n".$pi_tpl_post;
							$pi_tpl_post = '';
							continue 2;
						}
						elseif ($a === 'tplorig')
						{
							if ($this->head_comments || $this->comments)
							{
								$pi_tpl_pre = '<!-- Begin '.$this->doc_rawalias.' pi from template '.$v." -->\n";
								$pi_tpl_post = '<!-- End '.$this->doc_rawalias.' pi from template '.$v." -->\n";
							}
						}
						elseif ($a === 'prefix')
						{
							$pi_prefix = $v;
						}
						elseif ($attribs && !empty($v) && !@in_array($a, $targetmeta_attr))
						{
							$pi_attrs .= " {$a}=\"{$v}\"";
						}
					}
					if ($pi_attrs == '')
					{
						$pi_attrs = ' ';
					}
					$doc_source .= $pi_tpl_pre.'<?'.$pi_prefix.$pi_attrs; // Closed above
				}
			}

			// process 'xsl' (xml-stylesheet) doc info
			if (isset($_doc_info['xsl']))
			{
				$targetmeta_attr = str_replace('_', '-', str_replace('__', ':', $this->doc_info_types['xsl']['meta_attribs'])); // Prepare for comparison
				foreach ($_doc_info['xsl'] as $stylesheet)
				{
					$doc_src_xsl = '';
					foreach ($stylesheet as $a=>$v)
					{
						if (!@in_array($a, $targetmeta_attr) && !empty($v))
						{
							$doc_src_xsl .= " {$a}=\"{$v}\"";
						}
						elseif ($a === 'tplorig')
						{
							if ($extcomments)
							{
								$docpre_xsl = '<!-- Begin '.$this->doc_infoalias.' xsl from template '.$v." -->\n";
								$docpost_xsl = '<!-- End '.$this->doc_infoalias.' xsl from template '.$v." -->\n";
							}
						}
					}
					$this->xsl[] = $stylesheet['href']; // for XSL processing below
					if (!$this->xform_all)
					{
						if ($stylesheet['xform'] === 'true')
						{
							$stylesheet['xform'] = true;
						}
						$this->pre_xform[] = $stylesheet['xform']; // for XSL processing below
					}
					else
					{
						$this->pre_xform[] = true;
					}
					$doc_source .= $docpre_xsl.'<'.'?xml-stylesheet'.$doc_src_xsl.'?'.">\n".$docpost_xsl;
				}
			}

			// Per http://www.w3.org/TR/xhtml1/#C_14 , internal style elements in XHTML should be referenced by an xml-stylesheet referring to their id
			$styleidcount = 0;
			if ($this->xml_plain && $this->HTTP_ACCEPT !== 'text/html')
			{
				if (isset($this->doc_raw['style']['main']))
				{
					ksort($this->doc_raw['style']['main']);
					foreach ($this->doc_raw['style']['main'] as &$styleids)
					{
						if ($extcomments)
						{
							$docpre_styleid = '<!-- Begin style with id from template '.$styleids['tplorig'].' -->'."\n";
							$docpost_styleid = '<!-- End style with id from template '.$styleids['tplorig'].' -->'."\n";
						}
						if (isset($styleids['id']))
						{
							$doc_source .= $docpre_styleid.'<'.'?xml-stylesheet href="#'.$styleids['id'].'" type="text/css"?>'."\n".$docpost_styleid;
						}
						elseif (!isset($styleids['_content']))
						{
							$styleidcount++;
							$styleids['id'] = 'style_tag_id'.$styleidcount; // Add the 'id' so that it can be processed below when the style tag is printed
							$doc_source .= $docpre_styleid.'<'.'?xml-stylesheet href="#style_tag_id'.$styleidcount.'" type="text/css"?>'."\n".$docpost_styleid;
						}
					}
				}
				if (isset($_doc_info['style']))
				{
					foreach($_doc_info['style'] as &$distyleids)
					{
						if ($extcomments)
						{
							$docpre2_styleid = '<!-- Begin style with id from template '.$distyleids['tplorig'].' -->'."\n";
							$docpost2_styleid = '<!-- End style with id from template '.$distyleids['tplorig'].' -->'."\n";
						}
						if (isset($distyleids['id']))
						{
							$doc_source .= $docpre2_styleid.'<'.'?xml-stylesheet href="#'.$distyleids['id'].'" type="text/css"?>'."\n".$docpost2_styleid;
						}
						else
						{
							$styleidcount++;
							$distyleids['id'] = 'style_tag_id'.$styleidcount; // Add the 'id' so that it can be processed below when the style tag is printed
							$doc_source .= $docpre2_styleid.'<'.'?xml-stylesheet href="#style_tag_id'.$styleidcount.'" type="text/css"?>'."\n".$docpost2_styleid;
						}
					}
				}
			}


			// Firefox 2.0 has a bug in dealing with ampersands in xml-stylesheet (it expects them unescaped whereas per http://www.w3.org/TR/xml-stylesheet/ they should be escaped)
			if ($this->xml_plain && $this->HTTP_ACCEPT !== 'text/html' && !(strstr($_SERVER['HTTP_USER_AGENT'], 'Firefox/2')) && !$this->avoid_xml_stylesheet)
			{
				$this->dr_css_pre = $this->dr_xsl_pre;
				$this->dr_css_post = $this->dr_xsl_post;
				$doc_source .= $this->doc_raw_build('css', 'exclude_in_xml');


				// process 'css' doc info
				if (isset($_doc_info['css']))
				{
					$targetmeta_attr = str_replace('_', '-', str_replace('__', ':', $this->doc_info_types['css']['meta_attribs'])); // Prepare for comparison
					foreach ($_doc_info['css'] as $link)
					{
						$href = $link['href'];
						if (!(substr($href, 0, 1) == '/' || substr($href, 0, 7) == 'http://'))
						{
							$href = $smarty->doc_css_url . $href;
						}
						unset($link['href']);
						$doc_src_css = '';
						unset($link['external']);
						foreach ($link as $a=>$v)
						{
							if ($a === 'tplorig')
							{
								if ($extcomments)
								{
									$docpre_css = '<!-- Begin '.$this->doc_infoalias.' css from template '.$v." -->\n";
									$docpost_css = '<!-- End '.$this->doc_infoalias.' css from template '.$v." -->\n";
								}
							}
							elseif (!@in_array($a, $targetmeta_attr) && !empty($v) && !@in_array($a, $this->doc_info_types['css']['exclude_in_xml']))
							{
								$doc_src_css .= " {$a}=\"{$v}\"";
							}
						}
						$doc_source .= $docpre_css.'<'.'?xml-stylesheet'." href=\"{$href}\"".$doc_src_css.$this->dr_css_post.$docpost_css;
					}
				}
			}

			$doc_source .= $this->doc_raw_build('xsl');

			// Process 'notes' (not evident in the main file, except optionally as a comment)

			if ($this->notes_in_hiddendir)
			{
				$hiddenfile = 'hidden';
			}
			else
			{
				$hiddenfile = 'public';
			}
			$doc_source .= $this->doc_raw_build('notes', '', $hiddenfile, !$this->visible_notes);

			// process 'comments' doc raw
			if ($this->visible_notes)
			{
				$doc_source .= $this->doc_raw_head_build('comments');
			}

			// process 'notes' doc info
			if ($this->visible_notes && isset($_doc_info['notes']))
			{
				foreach ($_doc_info['notes'] as $note)
				{
					if ($extcomments)
					{
						$docpre_notes = '<!-- Begin '.$this->doc_infoalias.' notes from template '.$note['tplorig']." -->\n";
						$docpost_notes = '<!-- End '.$this->doc_infoalias.' notes from template '.$note['tplorig']." -->\n";
					}
					if (!$note['hide'])
					{
						$doc_source .= $docpre_notes.$this->dr_notes_pre;
						$doc_source .= $note['file'];
						$doc_source .= " -->\n".$docpost_notes;
					}
				}
			}

			// process 'comments' doc info
			if ($this->visible_notes && isset($_doc_info['comments']))
			{
				foreach ($_doc_info['comments'] as $comment)
				{
					if ($extcomments)
					{
						$docpre_comm = '<!-- Begin '.$this->doc_infoalias.' comments from template '.$comment['tplorig']." -->\n";
						$docpost_comm = '<!-- End '.$this->doc_infoalias.' comments from template '.$comment['tplorig']." -->\n";
					}
					if (!$comment['hide'])
					{
						$doc_source .= $docpre_comm."<!-- ";
						$doc_source .= $comment['_content'];
						$doc_source .= " -->\n".$docpost_comm;
					}
				}
			}

			// process doc_raw targetted before the root (no attributes)
			if (isset($this->doc_raw['root_pre']['main']))
			{
				ksort($this->doc_raw['root_pre']['main']);
				foreach ($this->doc_raw['root_pre']['main'] as $rawpre)
				{
					foreach ($rawpre as $a => $v)
					{
						if ($a === '_content')
						{
							$v = str_replace(array('[[[', ']]]'), array('{', '}'), $v);
							$doc_source .= $v."\n";
						}
					}
				}
			}

			// process doc_info targetted before the root (no attributes)
			if (isset($_doc_info['root_pre']))
			{
				foreach ($_doc_info['root_pre'] as $rawpre)
				{
					if ($extcomments)
					{
						$docpre_root_pre = '<!-- Begin '.$this->doc_infoalias.' root_pre from template '.$rawpre['tplorig']." -->\n";
						$docpost_root_pre = '<!-- End '.$this->doc_infoalias.' root_pre from template '.$rawpre['tplorig']." -->\n";
					}
					$doc_source .= $docpre_root_pre.$indent.$rawpre['_content']."\n".$docpost_root_pre;
				}
			}

//			print $this->HTTP_ACCEPT;exit;

			// This block is needed for the next item and subsequent ones
			if (isset($_doc_info['root']['_content']))
			{
				$this->set_rootnode($_doc_info['root']['_content']);
				if (isset($_doc_info['root']['xmlns']))
				{
					$this->xmlns = $_doc_info['root']['xmlns'];
				}
				else
				{
					$this->xmlns = ''; // Override the default XHTML, since probably not using that namespace
				}
			}


			// Prepare entity
			// Note that to get an XML text declaration (e.g., to specify a non-utf-8/non-utf-16 or xml 1.1+ entity), this must be set page-wide through the $this->entity_prefixed variable
			if (isset($_doc_info['entity']) || isset($this->doc_raw['entity']))
			{
				if (isset($this->doc_raw['entity']))
				{
					$this->doc_raw_build('entity', '', 'public', false, false, false); // No printed output, and no need to go through
					foreach ($this->doc_raw['entity'] as $fileentity => $val)
					{
						$paramtrue = false;

						foreach ($val as $elem)
						{
							if (!isset($elem['dummy']))
							{
								$paramentity = $paramname = $paramtype = $paramfilevalue = $paramndata = $ndata = $paramnotationtype = $paramnotation = $docpre_entity = $docpost_entity = '';
								if ($this->noextparams && isset($elem['param']) && ($elem['param'] || strtolower($elem['param']) === 'true'))
								{
									continue;
								}
								foreach ($elem as $a => $v)
								{
									if ($a === '_content')
									{
										continue 2;
									}
									if ($a === 'tplorig')
									{
										if ($extcomments)
										{
											$docpre_entity = '<!-- Begin '.$this->doc_rawalias.' entity from template '.$v." -->\n";
											$docpost_entity = '<!-- End '.$this->doc_rawalias.' entity from template '.$v." -->\n";
										}
									}
									elseif ($a === 'param' && ($v === true || strtolower($v) === 'true'))
									{
										$paramentity = '% ';
										$paramtrue = true;
									}
									elseif ($a === 'name')
									{
										$paramname = $v.' ';
									}
									elseif ($a === 'type')
									{
										$paramtype = strtoupper($v).' ';
									}
									elseif (($a === 'file' || $a === 'subtype') && !empty($v))
									{
										$paramfilevalue = '"'.$v.'"';
									}
									elseif ($a === 'ndata' && !empty($v) && !isset($elem['param']))
									{
										$paramndata = ' NDATA '.$v;
										$ndata = $v;
									}
									elseif ($a === 'notation-type')
									{
										$paramnotationtype = strtoupper($v);
									}
									elseif ($a === 'notation')
									{
										$paramnotation = $v;
									}
								}
								if (empty($elem['file']) && empty($elem['subtype']))
								{
								// This ought to be targeted to the main file
									$paramfilevalue = '"'.$this->dr_entity_file_src.'"';
								}
								$extraentcomm = '';
								if (isset($this->extra_ent_comments))
								{
									$extraentcomm = $this->extra_ent_comments;
								}
								$this->doctype_rawcontent .= $docpre_entity.'<!ENTITY '.$paramentity.$paramname.$paramtype.$paramfilevalue.$paramndata.'>'."\n";
								if ($paramtrue)
								{
									// Note that due to bug https://bugzilla.mozilla.org/show_bug.cgi?id=267350 of Firefox, instantiating this will not only not work in Firefox, but cause subsequent references, etc. in the internal doctype to be ignored; for Firefox now 1/29/2007, this could be safely commented out (and avoid subsequent referencing from failing), but for those actually making it here and trying to use this, it would fail its purpose (unless they added this instantiation elsewhere in the dtd)
									$this->doctype_rawcontent .= '%'.rtrim($paramname, ' ').";\n".$extraentcomm."\n";
								}
								elseif ($paramnotation != '')
								{
									$this->doctype_rawcontent .= '<!NOTATION '.$ndata.' '.$paramnotationtype.' "'.$paramnotation."\">\n";
								}
								$this->doctype_rawcontent .= $docpost_entity;
							}
						}
					}
				}
				if (isset($_doc_info['entity']))
				{
					foreach ($_doc_info['entity'] as $fileentity => $val)
					{
						$paramentity = $paramname = $paramtype = $paramfilevalue = $paramndata = $ndata = $paramnotationtype = $paramnotation = $docpre_entity = $docpost_entity = '';
						foreach ($val as $a => $v)
						{
							if ($a === '-content')
							{
								$paramcontents = '"'.$v.'"';
							}
							elseif ($a === 'tplorig')
							{
								if ($extcomments)
								{
									$docpre_entity = '<!-- Begin '.$this->doc_infoalias.' entity from template '.$v." -->\n";
									$docpost_entity = '<!-- End '.$this->doc_infoalias.' entity from template '.$v." -->\n";
								}
							}
							elseif ($a === 'param' && ($v === true || strtolower($v) === 'true'))
							{
								$paramentity = '% ';
								$paramtrue = true;
							}
							elseif ($a === 'name')
							{
								$paramname = $v.' ';
							}
							elseif ($a === 'type')
							{
								$paramtype = strtoupper($v).' ';
							}
							elseif (($a === 'file' || $a === 'subtype') && !empty($v))
							{
								$paramfilevalue = '"'.$v.'"';
							}
							elseif ($a === 'ndata' && !empty($v) && !isset($elem['param']))
							{
								$paramndata = ' NDATA '.$v;
								$ndata = $v;
							}
							elseif ($a === 'notation-type')
							{
								$paramnotationtype = strtoupper($v);
							}
							elseif ($a === 'notation')
							{
								$paramnotation = $v;
							}
						}

						if (isset($paramcontents))
						{
							$paramfilevalue = $paramcontents;
						}
						elseif (empty($val['file']) && empty($val['subtype']))
						{ // This ought to be targeted to the main file
							$paramfilevalue = '"'.$this->dr_entity_file_src.'"';
						}

						$this->doctype_rawcontent .= $docpre_entity.'<!ENTITY '.$paramentity.$paramname.$paramtype.$paramfilevalue.$paramndata.'>'."\n";
						if ($paramtrue)
						{ // Note that due to bug https://bugzilla.mozilla.org/show_bug.cgi?id=267350 of Firefox, instantiating this will not only not work in Firefox, but cause subsequent references, etc. in the internal doctype to be ignored; for Firefox now 1/29/2007, this could be safely commented out (and avoid subsequent referencing from failing), but for those actually making it here and trying to use this, it would fail its purpose (unless they added this instantiation elsewhere in the dtd)
							$this->doctype_rawcontent .= '%'.rtrim($paramname, ' ').";\n";
						}
						elseif ($paramnotation != '')
						{
							$this->doctype_rawcontent .= '<!NOTATION '.$ndata.' '.$paramnotationtype.' "'.$paramnotation."\">\n";
						}
						$this->doctype_rawcontent .= $docpost_entity;

					}
				}
				$this->internaldoctype = true; // Unnecessary

			}

			// Prepare internal (doctype) docraws
			$this->doctype_rawcontent .= $this->doc_raw_head_build('doctype'); // Add previous to the end in case it added invalid external parameter entity reference instantiation
			if (isset($_doc_info['doctype']))
			{
				$this->internaldoctype = true;
				foreach ($_doc_info['doctype'] as $a=>$v)
				{
					if ($a === 'tplorig')
					{
						if ($extcomments)
						{
							$this->docpre_doctype = '<!-- Begin '.$this->doc_infoalias.' doctype from template '.$v." -->\n";
							$this->docpost_doctype = '<!-- End '.$this->doc_infoalias.' doctype from template '.$v." -->\n";
						}
					}
					elseif ($a === 'root')
					{
						$this->set_rootnode($v);
					}
					elseif ($a === '_content')
					{
						$this->doctype_add = $v;
					}
				}
			}
			elseif ($this->doctype_rawcontent != '')
			{
				$this->internaldoctype = true;
			}



			// Process external (dtd) docraws
			if (isset($this->doc_raw['dtd']) || isset($this->doc_raw['doctype']))
			{
				$this->doc_raw_build('dtd', '', 'public', false, false, false); // No printed output, and no need to go through
				// Could place the above doc_raw_build call after the next nested foreach (after both complete), obviating the need for the exta parameter $both_main_indiv since there'd only be one item in that part of doc_raw, if first do these two lines:
//				unset($this->doc_raw['dtd']);
//				$this->doc_raw['dtd][$file_dtd][$idkey] = $value_dtd;
				$value_dtd = array();
				if (isset($this->doc_raw['dtd']))
				{
					$doctypedtd = $this->doc_raw['dtd'];
				}
				elseif (isset($this->doc_raw['doctype']))
				{
					$doctypedtd = $this->doc_raw['doctype'];
					$this->avoid_blanksubtype = true;
				}
				foreach ($doctypedtd as $file_dtd => $val)
				{
					foreach ($val as $idkey => $elem)
					{
						$value_dtd = array_merge($elem, $value_dtd); // Get all attributes of one doc_raw
					}
					break; // Only use one doc_raw since can only be one dtd
				}
				$doc_source .= $smarty->getDoctypeSignature($this->doc_rawalias, $value_dtd);
			}
			elseif (isset($_doc_info['dtd']) || $this->add_openclose)
			{
				// generate the auto-doctype (external) signature (from doc_info)--in an 'elseif' since can't have two external dtds in a document
				$doc_source .= $smarty->getDoctypeSignature($this->doc_infoalias);
			}
			elseif ($this->xml_plain && (isset($_doc_info['doctype']) || isset($_doc_info['entity']) || isset($this->doc_raw['entity'])))
			{
				// Assume plain XML served as such or otherwise won't render; doc_info doctype might be better moved about with the doc_raw doctype so that default or manually-specified PUBLIC/SYSTEM identifiers, etc. can be detected
				if (isset($_doc_info['doctype']) || !$this->noextparams)
				{
					// This might block out some valid entities, but as entities are not well-supported anyways, it is better to block them than to have an empty DOCTYPE we don't need
					$doc_source .= '<!DOCTYPE '.$this->rootnode." [\n".$this->doctype_rawcontent.$this->docpre_doctype.$this->doctype_add.$this->docpost_doctype."\n]>\n";
				}
			}
			elseif (isset($this->doc_info['html']['v']))
			{
				$doc_source .= $this->DTDS['XHTML'][$this->doc_info['html']['v']]['signature']."\n";
			}

			// This long sequence sets the rootnode of the document (and possibly any xsd's)
			if (($this->rootnode != 'html' && $this->rootnode != '') ||
					isset($this->doc_raw['xsd']) || isset($this->doc_info['xsd']) ||
							isset($_doc_info['root']['_content']) ||
								$this->add_openclose || isset($_doc_info['html'])
							)
			{ // Assumes XML or XHTML

				$doc_source_temp = '<';
/*
				// Add if one adjusts the code to have separate specifier for prefix
				if (isset($_doc_info['root']['prefix'])) {
					$doc_source .= $_doc_info['root']['prefix'].':';
				}
*/
				if ($this->rootnode != '' && !$this->add_openclose)
				{
					$doc_source_temp .= $this->rootnode;
				}
				else
				{
					$doc_source_temp .= 'html';
				}

				if ($this->xmlns != '')
				{
					// Will also be triggered if $_doc_info['root'] exists and has an xmlns value (see above)
					$doc_source_temp .= ' xmlns="'.$this->xmlns.'"';
				}

				if ($this->xincludeset)
				{
					$doc_source_temp .= ' xmlns:xi="'.$this->xinclude_ns.'"';
				}

				if (isset($_doc_info['root']))
				{
					$targetmeta_attr = str_replace('_', '-', str_replace('__', ':', $this->doc_info_types['root']['meta_attribs'])); // Prepare for comparison
					foreach ($_doc_info['root'] as $a=>$v)
					{
						if (!empty($v))
						{
							if ($a != '_content' && $a != 'xmlns' && !@in_array($a, $targetmeta_attr))
							{ //  && $a != 'prefix'
								$this->rootattrs[$a] = $v;
							}
							elseif ($a === 'tplorig')
							{
								if ($extcomments)
								{
									$docpre_root = '<!-- Begin '.$this->doc_infoalias.' root from template '.$v." -->\n";
									$docpost_root = '<!-- End '.$this->doc_infoalias.' root from template '.$v." -->\n";
								}
							}
							elseif ($a === 'xsi:noNamespaceSchemaLocation')
							{
								$this->root_nns_xsd = $v;
							}
							elseif ($a === 'xsi:schemaLocation')
							{
								$this->extra_xsds[$v] = $v;
							}
						}
					}
				}
				else
				{
					// process 'html' doc info
					if (isset($_doc_info['html']))
					{
						foreach ((array) $_doc_info['html'] as $a=>$v)
						{
							if (!empty($v))
							{
								if ($a === 'tplorig')
								{
									if ($extcomments)
									{
										$docpre_root = '<!-- Begin '.$this->doc_infoalias.' html from template '.$v." -->\n";
										$docpost_root = '<!-- End '.$this->doc_infoalias.' html from template '.$v." -->\n";
									}
								}
								elseif ($a === 'xsi:noNamespaceSchemaLocation')
								{
									$this->root_nns_xsd = $v;
									if (!isset($_doc_info['html']['xsi:schemaLocation']))
									{
										$this->rootattrs['xmlns:xsi'] = 'http://www.w3.org/2001/XMLSchema-instance';
									}
								}
								elseif ($a === 'xsi:schemaLocation')
								{
									$this->extra_xsds[$v] = $v;
								}
								elseif ($a !== 'v' && ($a !== 'xmlns' || $this->xmlns == ''))
								{
									// Don't need to avoid xmlns for HTML here (e.g., if the $this->xmlns is blank) since it requires xml_plain to be turned on in order to show up as a default
									// This also means xmlns will not show up as a default (where doc_info['html'] is on) unless served with xml_plain true
									$this->rootattrs[$a] = $v;
								}
							}
						}
					}
					elseif ($this->add_openclose)
					{
						if ($extcomments)
						{
							$docpre_html = '<!-- Begin auto add_openclose html '."-->\n";
							$docpost_html = '<!-- End auto add_openclose html '."-->\n";
						}
						if ( isset($_doc_info['dtd']['family']) && $_doc_info['dtd']['family'] == 'XHTML'
						     or $this->application_xml == 'XHTML'
						   )
						{
							$this->rootattrs['xmlns'] = 'http://www.w3.org/1999/xhtml';
						}
					}
				}
				if (isset($this->extra_xmlns))
				{
					$doc_source_temp .= ' '.implode(' ', $this->extra_xmlns);
				}

				if (isset($this->doc_raw['xsd']) || isset($this->extra_xsds) || isset($_doc_info['xsd']))
				{
					$doc_source_temp .= $this->doc_raw_build('xsd', '', 'public', 'skippre');
				}
				else
				{
					// Changed to allow all (including html) to check; was(isset($_doc_info['root'])) {
					$doc_source_temp .= $this->doc_raw_build('root', '', 'public', 'skippre');
				}

				$doc_source_temp .= ">\n";

				if ($extcomments)
				{
					$doc_source .= $docpre_html.$docpre_root.@implode('', $this->docpre_xsdcomm).@implode("\n", $this->tpl_pre_xsd).$doc_source_temp.@implode("\n", $this->tpl_post_xsd).@implode('', $this->docpost_xsdcomm).$docpost_root.$docpost_html; // Allow any comments to be added around the element
				}
				else
				{
					$doc_source .= $doc_source_temp;
				}
			}

			// Prepare the metas as they need to come first given that the content/http-equiv should be the first child in the head
			// process 'meta' doc info
			if (isset($_doc_info['meta']))
			{
				foreach ($_doc_info['meta'] as $meta)
				{
					$doc_src_meta = '';
					$doc_src_meta0 = '';
					if (isset($meta['http-equiv']))
					{
						// Meta with http-equiv should apparently not be printed when served as application/xhtml+xml per http://www.w3.org/TR/xhtml-media-types/, though I've commented it out now since I'm guessing this may only be for the case of setting a character set (and not for things like redirects):
						/*
						if (!$this->xml_plain)
						{ */
							foreach ($meta as $a=>$v)
							{
								if ($a === 'tplorig')
								{
									if ($extcomments)
									{
										$docpre_meta0 = '<!-- Begin '.$this->doc_infoalias.' meta from template '.$v." -->\n";
										$docpost_meta0 = '<!-- End '.$this->doc_infoalias.' meta from template '.$v." -->\n";
									}
								}
								// Allow for http type (and charset) to be auto-calculated (such as from the Navbarcrumbs class)
								elseif ($a === 'content' && $v === 'http_accept')
								{
									// Note that if trying to serve XHTML as 'application/xhtml+xml' as it is supposed to be (or 'application/xml'), those served as the specified types are not to include an http_equiv: http://www.w3.org/TR/xhtml-media-types/#application-xml
									$doc_src_meta0 .= " {$a}=\"".$this->HTTP_ACCEPT.'; charset='.$this->encoding.'"';
								}
								elseif (!empty($v))
								{
									$doc_src_meta0 .= " {$a}=\"{$v}\"";
								}
							}
						// }
					}
					else
					{
						foreach ($meta as $a=>$v)
						{
							if ($a === 'tplorig')
							{
								if ($extcomments)
								{
									$docpre_meta = '<!-- Begin '.$this->doc_infoalias.' meta from template '.$v." -->\n";
									$docpost_meta = '<!-- End '.$this->doc_infoalias.' meta from template '.$v." -->\n";
								}
							}
							// Allow for http type (and charset) to be auto-calculated (such as from the Navbarcrumbs class)
							elseif ($v === 'http_accept')
							{
							// Note that if trying to serve XHTML as 'application/xhtml+xml' as it is supposed to be (or 'application/xml'), those served as the specified types are not to include an http_equiv: http://www.w3.org/TR/xhtml-media-types/#application-xml
								$doc_src_meta .= " {$a}=\"".$this->HTTP_ACCEPT.'; charset='.$this->encoding.'"';
							}
							elseif (!empty($v))
							{
								$doc_src_meta .= " {$a}=\"{$v}\"";
							}
						}
					}
					if ($doc_src_meta0 != '')
					{
						$doc_source_meta0 .= $docpre_meta0."{$indent}<meta".$doc_src_meta0." />\n".$docpost_meta0; // Ensure http_accept type goes first
					}
					if ($doc_src_meta != '')
					{
						$doc_source_meta .= $docpre_meta."{$indent}<meta".$doc_src_meta." />\n".$docpost_meta;
					}
				}
				$doc_meta = $doc_source_meta0.$doc_source_meta;
			}

            $docpre_base    = null;
            $docpre_head    = null;
            $docpre_title   = null;
            $docpre_body    = null;
            $docpre_html    = null;

            $docpost_base   = null;
            $docpost_head   = null;
            $docpost_title  = null;
            $docpost_body   = null;
            $docpost_html   = null;

            $doc_src_base   = null;

            $doc_meta       = null;

            $head_content   = null;

			// process doc_raw targetted for the beginning of the head
			if (isset($this->doc_raw['head']['main']))
			{
				ksort($this->doc_raw['head']['main']);
				if (!$this->xml_plain || ($this->application_xml === 'XHTML') || ($this->doc_info['dtd']['family'] === 'XHTML') || @array_key_exists('v', $this->doc_info['html']))
				{
					$this->dr_head_pre = '<head';
				}
				if (isset($_doc_info['head']))
				{
				// Allow attributes from doc_infos to be added
					foreach ($_doc_info['head'] as $a=>$v)
					{
						if ($a === 'tplorig')
						{
							if ($extcomments)
							{
								$docpre_head = '<!-- Begin '.$this->doc_infoalias.' head from template '.$v." -->\n";
								$docpost_head = "\n".'<!-- End '.$this->doc_infoalias.' head from template '.$v." -->\n";
							}
						}
						elseif ($a === '_content')
						{
							$head_content = $v;
						}
						elseif (!empty($v))
						{
							$this->dr_head_pre .= " {$a}=\"{$v}\"";
						}
					}
				}
				if (!$this->xml_plain || ($this->application_xml === 'XHTML') || ($this->doc_info['dtd']['family'] === 'XHTML') || @array_key_exists('v', $this->doc_info['html']))
				{
					$this->dr_head_postpre = ">\n".$doc_meta;
					$addheadclose = true;
				}
				$doc_source .= $docpre_head.$this->doc_raw_head_build('head', true).$head_content.$docpost_head;
			}
			// process 'head' doc info
			elseif (isset($_doc_info['head']))
			{
				foreach ($_doc_info['head'] as $a=>$v)
				{
					if ($a === 'tplorig')
					{
						if ($extcomments)
						{
							$docpre_head = '<!-- Begin '.$this->doc_infoalias.' head from template '.$v." -->\n";
							$docpost_head = '<!-- End '.$this->doc_infoalias.' head from template '.$v." -->\n";
						}
					}
					elseif ($a === '_content')
					{
						$head_content = $v;
					}
					elseif (!empty($v))
					{
						$doc_src_head .= " {$a}=\"{$v}\"";
					}
				}
				$doc_source .= $docpre_head.'<head'.$doc_src_head.">\n".$doc_meta.$head_content.$docpost_head;

				$addheadclose = true;
			}

			elseif ($this->add_openclose)
			{
				if ($extcomments)
				{
					$docpre_head = '<!-- Begin auto add_openclose head '."-->\n";
					$docpost_head = '<!-- End auto add_openclose head '."-->\n";
				}
				$doc_source .= $docpre_head."<head>\n".$doc_meta.$docpost_head;
				$addheadclose = true;
			}

			// insert module header-pre content (I placed this after the meta, since meta http-equiv in HTML should come first
			$doc_source .= $module_content['head_pre'];

			if (isset($_doc_info['robots']) && !$this->xml_plain)
			{
				foreach ($_doc_info['robots'] as $a=>$v)
				{
					if ($a === 'tplorig')
					{
						if ($extcomments)
						{
							$docpre_robots = '<!-- Begin '.$this->doc_infoalias.' robots from template '.$v." -->\n";
							$docpost_robots = '<!-- End '.$this->doc_infoalias.' robots from template '.$v." -->\n";
						}
					}
					// Place this first (not sure if its necessary, but it seems to be convention)
					elseif ($a === 'index')
					{
						if ($v === 'yes')
						{
							$doc_src_robots1 = 'index';
						}
						elseif ($v === 'no')
						{
							$doc_src_robots1 = 'noindex';
						}
					}
					elseif ($a === 'follow')
					{
						if ($v === 'yes')
						{
							$doc_src_robots2 = ',follow';
						}
						elseif ($v === 'no')
						{
							$doc_src_robots2 = ',nofollow';
						}
					}
					else
					{
						$doc_src_robots1 = $v; // Allow 'all' or 'none'
					}
				}
				$doc_source .= $docpre_robots.'<meta name="robots" content="'.$doc_src_robots1.$doc_src_robots2."\" />\n".$docpost_robots;
			}
			// process 'base' doc info
			if (isset($_doc_info['base']))
			{
				foreach ($_doc_info['base'] as $a=>$v)
				{
					if ($a === 'tplorig')
					{
						if ($extcomments)
						{
							$docpre_base = '<!-- Begin '.$this->doc_infoalias.' base from template '.$v." -->\n";
							$docpost_base = '<!-- End '.$this->doc_infoalias.' base from template '.$v." -->\n";
						}
					}
					elseif (!empty($v))
					{
						$doc_src_base .= " {$a}=\"{$v}\"";
					}
				}
				$doc_source .= $docpre_base."{$indent}<base".$doc_src_base." />\n".$docpost_base;
			}

			// Firefox 2.0 has a bug in dealing with ampersands in xml-stylesheet (it expects them unescaped whereas per http://www.w3.org/TR/xml-stylesheet/ they should be escaped)
			// If XML plain, should be above as xml-stylesheet
			if (!$this->xml_plain || $this->HTTP_ACCEPT === 'text/html' || (strstr($_SERVER['HTTP_USER_AGENT'], 'Firefox/2')) || $this->avoid_xml_stylesheet)
			{
				$this->dr_css_pre = "{$indent}<link href=\"";
				$doc_source .= $this->doc_raw_build('css');
			}

			$this->dr_style_pre = "{$indent}<style";
			$doc_source .= $this->doc_raw_head_build('style', true);

			// process 'style' doc info
			if (isset($_doc_info['style']))
			{
				$doc_src_style = '';
				foreach ($_doc_info['style'] as $styleattribs)
				{
					foreach ($styleattribs as $a=>$v)
					{
						if ($a === 'tplorig')
						{
							$docpres_style[$v] = $v; // Ensure filenames are not repeated
							$docposts_style[$v] = $v;
						}
						elseif ($a != '_content')
						{
							if (!empty($v))
							{
								$doc_src_style[$a] = " {$a}=\"{$v}\""; // Ensure attributes are not repeated
							}
						}
						else
						{
							if (!empty($v))
							{
								$stylecontent .= $v;
							}
						}
					}
				}
				if ($extcomments)
				{
					$docpre_style = '<!-- Begin '.$this->doc_infoalias.' style from template '.implode(', ', $docpres_style)." -->\n";
					$docpost_style = '<!-- End '.$this->doc_infoalias.' style from template '.implode(', ', $docposts_style)." -->\n";
				}

				$stylecontent = str_replace(array('[[[', ']]]'), array('{', '}'), $stylecontent);
				$doc_source .= $docpre_style."{$indent}<style".implode('', $doc_src_style).$this->dr_style_postpre.$stylecontent."\n".$this->dr_style_post.$docpost_style;
			}

			// process 'css' doc info
			if ((!$this->xml_plain || $this->HTTP_ACCEPT === 'text/html') && isset($_doc_info['css']))
			{
				// Serve plain link tags for CSS if the browser doesn't support XML-style (or if not serving as XML)
				$targetmeta_attr = str_replace('_', '-', str_replace('__', ':', $this->doc_info_types['css']['meta_attribs'])); // Prepare for comparison
				foreach ($_doc_info['css'] as $link)
				{
					$href = $link['href'];
					if (!(substr($href, 0, 1) == '/' || substr($href, 0, 7) == 'http://'))
					{
						$href = $smarty->doc_css_url . $href;
					}
					unset($link['href']);
					$doc_src_css = '';
					if ($link['external'] === true && isset($link['external']))
					{
						unset($link['external']);
						foreach ($link as $a=>$v)
						{
							if ($a === 'tplorig')
							{
								if ($extcomments)
								{
									$docpre_css = '<!-- Begin '.$this->doc_infoalias.' css from template '.$v." -->\n";
									$docpost_css = '<!-- End '.$this->doc_infoalias.' css from template '.$v." -->\n";
								}
							}
							elseif (!@in_array($a, $targetmeta_attr) && !empty($v))
							{
								$doc_src_css .= " {$a}=\"{$v}\"";
							}
						}
						$doc_source .= $docpre_css."{$indent}<link".$doc_src_css." href=\"{$href}\" />\n".$docpost_css;
					}
					else
					{
						unset($link['external']);
						unset($link['title']);
						unset($link['rel']);
						foreach ($link as $a=>$v)
						{
							if (!@in_array($a, $targetmeta_attr) && !empty($v))
							{
								$doc_src_css .= " {$a}=\"{$v}\"";
							}
							elseif ($a === 'tplorig')
							{
								if ($extcomments)
								{
									$docpre_css = '<!-- Begin '.$this->doc_infoalias.' css from template '.$v." -->\n";
									$docpost_css = '<!-- End '.$this->doc_infoalias.' css from template '.$v." -->\n";
								}
							}
						}
						$doc_source .= $docpre_css."{$indent}<style".$doc_src_css;
						$doc_source .= $this->dr_style_postpre;
						$doc_source .= "@import \"{$href}\";\n";
						$doc_source .= $this->dr_style_post;
						$doc_source .= $docpost_css;
					}
				}
			}

			// process 'link' doc info
			if (isset($_doc_info['link']))
			{
				foreach ($_doc_info['link'] as $link)
				{
					$doc_src_link = '';
					foreach ($link as $a=>$v)
					{
						if ($a === 'tplorig')
						{
							if ($extcomments)
							{
								$docpre_link = '<!-- Begin '.$this->doc_infoalias.' link from template '.$v." -->\n";
								$docpost_link = '<!-- End '.$this->doc_infoalias.' link from template '.$v." -->\n";
							}
						}
						elseif ($v === 'http_accept')
						{
							// Allow for http type to be auto-calculated (such as from the Navbarcrumbs class)
							$doc_src_link .= " {$a}=\"{$this->HTTP_ACCEPT}\"";
						}
						elseif (!empty($v))
						{
							$doc_src_link .= " {$a}=\"{$v}\"";
						}
					}
					$doc_source .= $docpre_link."{$indent}<link".$doc_src_link." />\n".$docpost_link;
				}
			}

			// XHTML Basic shouldn't have scripts
			if (!$this->xhtmlbasic) {
				$this->dr_code_pre = "{$indent}<script src=\"";









				$doc_source .= $this->doc_raw_build('code');

				// process doc_raw targetted for the script tag
				$this->dr_script_pre = "{$indent}<script";
				$doc_source .= $this->doc_raw_head_build('script', true);

				// process 'script' doc info
				if (isset($_doc_info['script']))
				{
					$doc_src_script = '';
					foreach ($_doc_info['script'] as $scriptattribs)
					{
						foreach ($scriptattribs as $a=>$v)
						{
							if ($a === 'tplorig')
							{
								$docpres_script[$v] = $v; // Ensure filenames are not repeated
								$docposts_script[$v] = $v;
							}
							elseif ($a != '_content')
							{
								if (!empty($v))
								{
									$doc_src_script[$a] .= " {$a}=\"{$v}\""; // Ensure attributes are not repeated
								}
							}
							else
							{
								if (!empty($v))
								{
									$scriptcontent .= $v;
								}
							}
						}
					}
					if ($extcomments)
					{
						$docpre_script = '<!-- Begin '.$this->doc_infoalias.' script from template '.implode(', ', $docpres_script)." -->\n";
						$docpost_script = '<!-- End '.$this->doc_infoalias.' script from template '.implode(', ', $docposts_script)." -->\n";
					}

					$scriptcontent = str_replace(array('[[[', ']]]'), array('{', '}'), $scriptcontent);
					$doc_source .= $docpre_script."{$indent}<script".implode('', $doc_src_script).$this->dr_script_postpre.$scriptcontent."\n".$this->dr_script_post.$docpost_script;
				}

				// process 'code' doc info
				if (isset($_doc_info['code']))
				{
					$targetmeta_attr = str_replace('_', '-', str_replace('__', ':', $this->doc_info_types['code']['meta_attribs'])); // Prepare for comparison
					foreach ($_doc_info['code'] as $link)
					{
						$src = $link['src'];
						if (!(substr($src, 0, 1) == '/' || substr($src, 0, 7) == 'http://'))
						{
							$src = $smarty->doc_script_url . $src;
						}
						unset($link['src']);
						$doc_src_code = '';
						foreach ($link as $a=>$v) {
							if (!@in_array($a, $targetmeta_attr) && !empty($v))
							{
								$doc_src_code .= " {$a}=\"{$v}\"";
							}
							elseif ($a === 'tplorig')
							{
								if ($extcomments)
								{
									$docpre_code = '<!-- Begin '.$this->doc_infoalias.' code from template '.$v." -->\n";
									$docpost_code = '<!-- End '.$this->doc_infoalias.' code from template '.$v." -->\n";
								}
							}
						}
						$doc_source .= $docpre_code."{$indent}<script".$doc_src_code." src=\"{$src}\"></script>\n".$docpost_code;
					}
				}
			}

			// insert module header-pre content
			$doc_source .= $module_content['head_post'];

			// process doc_raw targetted at the end of the head (no attributes)
			if (isset($this->doc_raw['head_post']['main']))
			{
				ksort($this->doc_raw['head_post']['main']);
				foreach ($this->doc_raw['head_post']['main'] as $rawpre)
				{
					foreach ($rawpre as $a => $v)
					{
						if ($a === '_content')
						{
							$v = str_replace(array('[[[', ']]]'), array('{', '}'), $v);
							$doc_source .= $v."\n";
						}
					}
				}
			}
			// process doc_info targetted at the end of the head (no attributes)
			if (isset($_doc_info['head_post']))
			{
				foreach ($_doc_info['head_post'] as $rawpre)
				{
					if ($extcomments)
					{
						$docpre_head_post2 = '<!-- Begin '.$this->doc_infoalias.' head_post from template '.$rawpre['tplorig']." -->\n";
						$docpost_head_post2 = '<!-- End '.$this->doc_infoalias.' head_post from template '.$rawpre['tplorig']." -->\n";
					}
					$doc_source .= $docpre_head_post2.$indent.$rawpre['_content']."\n".$docpost_head_post2;
				}
			}

			// process 'title' doc info
			if (isset($_doc_info['title']))
			{
				foreach ($_doc_info['title'] as $a=>$v)
				{
					if ($a === 'tplorig')
					{
						if ($extcomments)
						{
							$docpre_title = '<!-- Begin '.$this->doc_infoalias.' title from template '.$v." -->\n";
							$docpost_title = '<!-- End '.$this->doc_infoalias.' title from template '.$v." -->\n";
						}
					}
					elseif ($a !== '_content' && !empty($v))
					{
						$doc_src_title .= " {$a}=\"{$v}\"";
					}
				}
				$doc_source .= $docpre_title."{$indent}<title".$doc_src_title.">{$_doc_info['title']['_content']}</title>\n".$docpost_title;
			}
			elseif ($this->add_openclose)
			{
				if ($extcomments) {
					$docpre_title= '<!-- Begin auto add_openclose title '."-->\n";
					$docpost_title = '<!-- End auto add_openclose title '."-->\n";
				}
				$doc_source .= $docpre_title."<title></title>\n".$docpost_title;
			}

			if (isset($addheadclose))
			{ // Set if head raw, head doc_info or add_openclose
				if (isset($extcomments))
				{
					$docpre_head = '<!-- Begin auto add_openclose head '."-->\n";
					$docpost_head = '<!-- End auto add_openclose head '."-->\n";
				}
				$doc_source .= $docpre_head."</head>\n".$docpost_head;
			}
			// process doc_raw targetted for the beginning of the body
			if (isset($this->doc_raw['body']['main']))
			{
				ksort($this->doc_raw['body']['main']);
				if (!$this->xml_plain || ($this->application_xml === 'XHTML') || ($this->doc_info['dtd']['family'] === 'XHTML') || @array_key_exists('v', $this->doc_info['html']))
				{
					$this->dr_body_pre .= '<body';
				}
				if (isset($_doc_info['body']))
				{ // allow attributes from doc_infos to be added
					foreach ($_doc_info['body'] as $a=>$v)
					{
						if ($a === 'tplorig')
						{
							if ($extcomments)
							{
								$docpre_body = '<!-- Begin '.$this->doc_infoalias.' body from template '.$v." -->\n";
								$docpost_body = '<!-- End '.$this->doc_infoalias.' body from template '.$v." -->\n";
							}
						}
						elseif (!empty($v))
						{
							$this->dr_body_pre .= " {$a}=\"{$v}\"";
						}
					}
				}
				if (!$this->xml_plain || ($this->application_xml === 'XHTML') || ($this->doc_info['dtd']['family'] === 'XHTML') || @array_key_exists('v', $this->doc_info['html']))
				{
					$this->dr_body_postpre = ">\n";
				}

				$doc_source .= $docpre_body.$this->doc_raw_head_build('body', true).$docpost_body;
			}
			elseif (isset($_doc_info['body']))
			{ // process 'body' doc info
				foreach ($_doc_info['body'] as $a=>$v)
				{
					if ($a === 'tplorig')
					{
						if ($extcomments)
						{
							$docpre_body = '<!-- Begin '.$this->doc_infoalias.' body from template '.$v." -->\n";
							$docpost_body = '<!-- End '.$this->doc_infoalias.' body from template '.$v." -->\n";
						}
					}
					elseif (!empty($v))
					{
						$doc_src_body .= " {$a}=\"{$v}\"";
					}
				}
				$doc_source .= $docpre_body.'<body'.$doc_src_body.">\n".$docpost_body;
			}
			elseif ($this->add_openclose)
			{
				if ($extcomments)
				{
					$docpre_body = '<!-- Begin auto add_openclose body '."-->\n";
					$docpost_body = '<!-- End auto add_openclose body '."-->\n";
				}
				$doc_source .= $docpre_body."<body>\n".$docpost_body;
			}

			// insert module header-pre content
			$doc_source .= $module_content['body_pre'];

			// add-in the original source
			$doc_source .= "{$source}\n";

			// insert module header-pre content
			$doc_source .= $module_content['body_post'];

			// process doc_raw targetted for the end of the body (no attributes)
			if (isset($this->doc_raw['body_post']['main']))
			{
				ksort($this->doc_raw['body_post']['main']);
				foreach ($this->doc_raw['body_post']['main'] as $rawpre)
				{
					foreach ($rawpre as $a => $v)
					{
						$doc_source .= $indent.$raw['_content']."\n";
						if ($a === '_content')
						{
							$v = str_replace(array('[[[', ']]]'), array('{', '}'), $v);
							$doc_source .= $v."\n";
						}
					}
				}
			}
//print $doc_source;exit;

			// process doc_info targetted for the end of the body (no attributes)
			if (isset($_doc_info['body_post']))
			{
				foreach ($_doc_info['body_post'] as $rawpre)
				{
					if ($extcomments)
					{
						$docpre_body_post2 = '<!-- Begin '.$this->doc_infoalias.' body_post from template '.$rawpre['tplorig']." -->\n";
						$docpost_body_post2 = '<!-- End '.$this->doc_infoalias.' body_post from template '.$rawpre['tplorig']." -->\n";
					}
					$doc_source .= $docpre_body_post2.$indent.$rawpre['_content']."\n".$docpost_body_post2;
				}
			}

			// y'all come back now, y'hear?
			if ($this->add_openclose || isset($_doc_info['body']) || isset($this->doc_raw['body']))
			{
				if ($extcomments)
				{
					$docpre_body = '<!-- Begin auto add_openclose body '."-->\n";
					$docpost_body = '<!-- End auto add_openclose body '."-->\n";
				}

				if (!$this->xml_plain || ($this->application_xml === 'XHTML') || ($this->doc_info['dtd']['family'] === 'XHTML') || @array_key_exists('v', $this->doc_info['html']))
				{
					$doc_source .= $docpre_body."</body>\n".$docpost_body;
				}
			}

			if ($this->add_openclose || isset($_doc_info['html']))
			{
				if ($extcomments)
				{
					$docpre_html = '<!-- Begin auto add_openclose html '."-->\n";
					$docpost_html = '<!-- End auto add_openclose html '."-->\n";
				}
				$doc_source .= $docpre_html.'</html>'.$docpost_html;
			}
			elseif (isset($_doc_info['root']['_content']))
			{
				$doc_source .= $docpre_root.'</';
/*				if (isset($_doc_info['root']['prefix'])) {
					$doc_source .= $_doc_info['root']['prefix'].':';
				}
*/
				$doc_source .= $_doc_info['root']['_content'].'>'.$docpost_root;
			}
			elseif (($this->rootnode != 'html' && $this->rootnode != '') ||
					isset($this->doc_raw['xsd']) || isset($this->doc_info['xsd']))
			{
				$doc_source .= '</';
				$doc_source .= $this->rootnode;
				$doc_source .= '>';
			}


			// process doc_raw targetted before the root (no attributes)
			if (isset($this->doc_raw['root_post']['main']))
			{
				ksort($this->doc_raw['root_post']['main']);
				foreach ($this->doc_raw['root_post']['main'] as $rawpost)
				{
					foreach ($rawpost as $a => $v)
					{
						if ($a === '_content')
						{
							$v = str_replace(array('[[[', ']]]'), array('{', '}'), $v);
							$doc_source .= $v."\n";
						}
					}
				}
			}
			// process doc_info targetted before the root (no attributes)
			if (isset($_doc_info['root_post']))
			{
				foreach ($_doc_info['root_post'] as $rawpost)
				{
					if ($extcomments)
					{
						$docpre_root_post = '<!-- Begin '.$this->doc_infoalias.' root_post from template '.$rawpost['tplorig']." -->\n";
						$docpost_root_post = '<!-- End '.$this->doc_infoalias.' root_post from template '.$rawpost['tplorig']." -->\n";
					}
					$doc_source .= $docpre_root_post.$indent.$rawpost['_content']."\n".$docpost_root_post;
				}
			}

			if (($this->tidy_on || ($this->tidy_for_xslt && @in_array(true, $pre_xform))) && extension_loaded('tidy'))
			{
				$tidy = new tidy;
				if ($this->xml_plain && $_doc_info['dtd']['family'] !== 'XHTML' && $this->application_xml !== 'XHTML')
				{
					$config = array('output-xml' => true, 'char-encoding' => 'utf8');
				}
				else
				{
					$config = array('output-xhtml' => true, 'quote-ampersand' => false, 'quote-nbsp' => false, 'char-encoding' => 'utf8');
				}
				$tidy->parseString($doc_source, $config, 'utf8');
				$tidy->cleanRepair();
				$doc_source = $tidy;
			}


			if (extension_loaded('xsl') && !$this->xform_none && !($this->xform_get && !empty($_GET[$this->xform_get_url])))
			{
				if ($this->common_XHTML_probs)
				{
					$doc_source = str_replace(' & ', ' &amp; ', $doc_source); // This is not caught otherwise
					$doc_source = str_replace('&nbsp;', '&#160;', $doc_source);
				}


				$xslproc = new XSLTProcessor;
				$xslcount = @count($this->xsl);


				for ($i = 0; $i < $xslcount; $i++)
				{
					if ($this->xform_all || $this->pre_xform[$i])
					{ // make sure the template designer in fact wants the stylesheet transformed server-side
						$ss = @DOMDocument::load($this->xsl[$i]);

						if (!$ss)
						{
							print 'Couldn\'t perform XSL server-side xform';
							if ($this->debug)
							{
								print ' as file '.$this->xsl[$i].' couldn\'t be found/opened.'."\n";
							}
							exit;
						}
						elseif ($opened && !$ss)
						{
							print 'Opened but didn\'t load';
							exit;
						}
						elseif ($ss)
						{
							$xslproc->importStyleSheet($ss); // attach the XSL rule
							$xformed = @DOMDocument::loadXML($doc_source);
							if ($xformed === false)
							{
								$doc_source .= '<!-- XML file could not be transformed (against XSL file';
								if ($this->debug)
								{
									$doc_source .= ' '.$this->xsl[$i];
								}
								$doc_source .= '). -->';
								break;
							}

							$doc_source = $xslproc->transformToXML($xformed);

							if ($this->strip_xml_decl)
							{
								$doc_source = str_replace("<"."?xml version=\"1.0\"?".">", '', $doc_source);
							}
						}
					}
					else
					{
						$opened = @fopen($this->xsl[$i], 'r');
						if (!$opened)
						{
							print 'File will not be transformable client-side since the file';
							if ($this->debug)
							{
								print ' '.$this->xsl[$i];
							}
							print ' cannot be found.';
							exit;
						}
					}
				} // end for
			}

			if ($this->strip_all_whitespace || ($this->xhtmlbasic && $this->strip_all_ws_xhtmlbasic))
			{
				$pattern = '@\s+@';
				if ($this->encoding === 'UTF-8') $pattern .= 'u';
				$doc_source = preg_replace($pattern, ' ', $doc_source);
			}


			// Send javascript header

			if ($this->entity_replace)
			{ // server-side entity replacement
				// Besides the file contents of a tab-delimited entity doc_raw, could come from entities in the doctype or dtd doc_raw, a general entity, or a parameter entity that was not submitted as tab-delimited (though not checking within any of these entire files at present for manual changes--besides the tab-delimited ones)

				$ent_file_content = file_get_contents($this->entity_file_toget);
//				print $this->entity_file_toget;exit;
				preg_match_all('@<!ENTITY\s+([^%" ]*?)\s+"([^"]*?)"@', $ent_file_content, $matches0);
				array_walk($matches0[1], create_function('&$matches', '$matches = \'&\'.$matches.\';\';'));
				$doc_source = str_replace($matches0[1], $matches0[2], $doc_source);


				$ent_nms = isset($this->extra_ent_repl_nm)?$this->extra_ent_repl_nm:array();
				$ent_txt = isset($this->extra_ent_repl_txt)?$this->extra_ent_repl_txt:array();

				preg_match_all('@<!ENTITY\s+([^%" ]*?)\s+"([^"]*?)"@', $doc_source, $matches);
//				preg_match_all('@<!ENTITY\s+([^%"]*?)\s+SYSTEM\s+"([^"]*?)"@', $doc_source, $matches2);
			//	preg_match_all('@<!ENTITY\s+%\s+([^%"]*?)\s+SYSTEM\s+"([^"]*?)"@', $doc_source, $matches3); // Could use this to convert external parameter references into internal ones, but would need to add functionality to doc_raw and to the array_merges below

				$nw_arr = @array_merge((array) $ent_nms, (array) $this->extra_ext_ent_nm);

				array_walk($nw_arr, create_function('&$matches', '$matches = \'&\'.$matches.\';\';'));

				//$aaaa = array_merge((array) $ent_txt, (array) $matches[2], (array) $this->extra_ext_ent_txt);

				$doc_source = str_replace($nw_arr, @array_merge((array) $ent_txt, (array) $this->extra_ext_ent_txt), $doc_source);
			}
//print $doc_source;exit;

			if ($this->validate === true or isset($smarty->_tpl_vars['validateon']))
			{
				// Adapted from function on PHP's documentation on Document->validate
				function staticerror($errno, $errstr, $errfile, $errline, $errcontext, $ret = false)
				{
					static $errs = array();
					if ($ret === true)
					{
						return $errs;
					}
					$tag = 'DOMDocument::validate(): ';
					$errs[] = str_replace($tag, '', $errstr);
				}

				// Load a document
				$dom = new DOMDocument;
				$dom->loadXML($doc_source);

				// Set up error handling
				set_error_handler('staticerror');
				$old = ini_set('html_errors', false);

				// Validate
				$dom->validate();

				// Restore error handling
				ini_set('html_errors', $old);
				restore_error_handler();

				// Get errors
				$errs = staticerror(null, null, null, null, null, true);
				if (!empty($errs))
				{
					print 'Validation errors:<pre>';
					print_r($errs);
					print '</pre>';
					exit;
				}
				else
				{
					print 'No validation errors.';
					exit;
				}
			}

			if (isset($_doc_info['code']) || isset($_doc_info['script']) || isset($this->doc_raw['script']) || isset($this->doc_raw['code']))
			{
				header('Content-Script-Type: text/javascript');
			}
			header('Content-Type: '.$this->HTTP_ACCEPT.'; charset='.$this->encoding);
			return $doc_source;
//			$this->headers_ran = true;
		} // end (long) if

		return $source;
	}


	/**
	 * Override function from Smarty calling Smarty_Compiler.class.php (needed
	 * to alter just one line below to instantiate the overriding class--a class
	 * with just two methods we need to override and just five total lines--in
	 * Smarty_Compiler.class.php -- see new class Smarty_Compiler_wtplfileaccs
	 * below)
     */

	function _compile_source($resource_name, &$source_content, &$compiled_content, $cache_include_path=null)
    {
        if (file_exists(SMARTY_DIR . $this->compiler_file))
        {
            require_once(SMARTY_DIR . $this->compiler_file);
        }
    	else
    	{
            // use include_path
            require_once($this->compiler_file);
        }

        $smarty_compiler = new Smarty_Compiler_wtplfileaccs; // Brett alteration

        $smarty_compiler->template_dir      = $this->template_dir;
        $smarty_compiler->compile_dir       = $this->compile_dir;
        $smarty_compiler->plugins_dir       = $this->plugins_dir;
        $smarty_compiler->config_dir        = $this->config_dir;
        $smarty_compiler->force_compile     = $this->force_compile;
        $smarty_compiler->caching           = $this->caching;
        $smarty_compiler->php_handling      = $this->php_handling;
        $smarty_compiler->left_delimiter    = $this->left_delimiter;
        $smarty_compiler->right_delimiter   = $this->right_delimiter;
        $smarty_compiler->_version          = $this->_version;
        $smarty_compiler->security          = $this->security;
        $smarty_compiler->secure_dir        = $this->secure_dir;
        $smarty_compiler->security_settings = $this->security_settings;
        $smarty_compiler->trusted_dir       = $this->trusted_dir;
        $smarty_compiler->use_sub_dirs      = $this->use_sub_dirs;
        $smarty_compiler->_reg_objects      = &$this->_reg_objects;
        $smarty_compiler->_plugins          = &$this->_plugins;
        $smarty_compiler->_tpl_vars         = &$this->_tpl_vars;
        $smarty_compiler->default_modifiers = $this->default_modifiers;
        $smarty_compiler->compile_id        = $this->_compile_id;
        $smarty_compiler->_config            = $this->_config;
        $smarty_compiler->request_use_auto_globals  = $this->request_use_auto_globals;

        if (isset($cache_include_path) && isset($this->_cache_serials[$cache_include_path]))
        {
            $smarty_compiler->_cache_serial = $this->_cache_serials[$cache_include_path];
        }
        $smarty_compiler->_cache_include = $cache_include_path;


        $_results = $smarty_compiler->_compile_file($resource_name, $source_content, $compiled_content);

        if ($smarty_compiler->_cache_serial)
        {
            $this->_cache_include_info = array(
                'cache_serial'=>$smarty_compiler->_cache_serial
                ,'plugins_code'=>$smarty_compiler->_plugins_code
                ,'include_file_path' => $cache_include_path);
        }
    	else
    	{
            $this->_cache_include_info = null;
        }

        return $_results;
    }

} // end class


/**
 * Interface ISmartyDocModule
 * @package    SmartyDocB
 * @subpackage ISmartyDocModule
 * @see class ASmartyDocModule
 */
interface ISmartyDocModule
{
	public function onDocStart();
	public function onDocEnd();
	public function onHeadStart();
	public function onHeadEnd();
	public function onBodyStart();
	public function onBodyEnd();
}

/**
 * Abstraction Class
 *
 * @abstract
 * @package SmartyDocB
 * @subpackage ASmartyDocModule
 */
abstract class ASmartyDocModule implements ISmartyDocModule
{
	protected $smarty;
	public function __construct(SmartyDocType $smarty)
	{
		$this->smarty = $smarty;
	}
	public function onDocStart() {}
	public function onDocEnd() {}
	public function onHeadStart() {}
	public function onHeadEnd() {}
	public function onBodyStart() {}
	public function onBodyEnd() {}

}

/* vim: set expandtab: */
?>
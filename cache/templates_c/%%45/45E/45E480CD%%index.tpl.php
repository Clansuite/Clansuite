<?php /* Smarty version 2.6.25-dev, created on 2009-11-29 02:17:54
         compiled from index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'dateformat', 'index.tpl', 5, false),array('function', 'breadcrumbs', 'index.tpl', 11, false),array('function', 'load_module', 'index.tpl', 95, false),array('block', 't', 'index.tpl', 54, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>

        <!-- This Page was cached on <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('dateformat', true, $_tmp) : smarty_modifier_dateformat($_tmp)); ?>
. -->

        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'clansuite_header_notice.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

        <title><?php echo $this->_tpl_vars['pagetitle']; ?>
 - <?php echo smarty_function_breadcrumbs(array('title' => '1','trail' => $this->_tpl_vars['trail'],'separator' => " &raquo; ",'length' => 30), $this);?>
</title>

    
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
    <meta http-equiv="content-language" content="<?php echo $this->_tpl_vars['meta']['language']; ?>
" />
    <meta name="author" content="<?php echo $this->_tpl_vars['meta']['author']; ?>
" />
    <meta http-equiv="reply-to" content="<?php echo $this->_tpl_vars['meta']['email']; ?>
" />
    <meta name="description" content="<?php echo $this->_tpl_vars['meta']['description']; ?>
" />
    <meta name="keywords" content="<?php echo $this->_tpl_vars['meta']['keywords']; ?>
" />
    <meta name="generator" content="Clansuite - just an eSports CMS" />

    
    <link rel="shortcut icon" href="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/clansuite_logos/Clansuite-Favicon-16.ico" />
    <link rel="icon" href="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/clansuite_logos/Clansuite-Favicon-16.ico" type="image/gif" />

    
    <script src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/javascript/clip.js" type="text/javascript"></script>

    
    <link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['css']; ?>
" />
    <link rel="alternate"  type="application/rss+xml" href="<?php echo $this->_tpl_vars['www_root']; ?>
/cache/photo.rss" title="" id="gallery" />

</head><body>

 <h2 class="oops"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    You shouldn't be able to read this, because this site uses complex stylesheets to
    display the information - your browser doesn't support these new standards. However, all
    is not lost, you can upgrade your browser absolutely free, so please

    UPGRADE NOW to a <a href="http://www.webstandards.org/upgrade/"
    title="Download a browser that complies with Web standards.">
    standards-compliant browser</a>. If you decide against doing so, then
    this and other similar sites will be lost to you. Remember...upgrading is free, and it
    enhances your view of the Web.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_t($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</h2>

<div id="notification" style="display: none;">
    <img src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/ajax/2.gif" style="vertical-align: middle;" alt="Ajax Notification Image"/>
    &nbsp; Wait - while processing your request...
</div>

<script type="text/javascript"></script>
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
    <td height="180" align="center">
        <img alt="Clansuite Header" src="<?php echo $this->_tpl_vars['www_root_themes_core']; ?>
/images/clansuite-header.png" width="760" height="175" />
    </td>
</tr>
</table>

<!-- Main Table -->
<table cellspacing="0" cellpadding="0" width="100%">

<!-- TableHeader + Breadcrumbs -->
<tr class="tr_header">
    <td colspan="3"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'breadcrumbs.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<!-- Middle/Center Part of Table -->
<tr>
    <!-- Left Widget Bar -->
    <td id="left_widget_bar" class="cell1">
        <div class="widget" id="widget_menu"><?php echo smarty_function_load_module(array('name' => 'menu','action' => 'widget_menu'), $this);?>
</div>
        <div class="widget" id="widget_latestnews"><?php echo smarty_function_load_module(array('name' => 'news','action' => 'widget_latestnews','items' => '2'), $this);?>
</div>  
        <div class="widget" id="widget_newscategories_list"><?php echo smarty_function_load_module(array('name' => 'news','action' => 'widget_newscategories_list'), $this);?>
</div>
        <div class="widget" id="widget_newscategories_dropdown"><?php echo smarty_function_load_module(array('name' => 'news','action' => 'widget_newscategories_dropdown'), $this);?>
</div>
        <div class="widget" id="widget_newsfeeds"><?php echo smarty_function_load_module(array('name' => 'news','action' => 'widget_newsfeeds'), $this);?>
</div>    
        <div class="widget" id="widget_newsarchive"><?php echo smarty_function_load_module(array('name' => 'news','action' => 'widget_archive'), $this);?>
</div>
        <div class="widget" id="widget_gallery"><?php echo smarty_function_load_module(array('name' => 'gallery','action' => 'widget_gallery'), $this);?>
</div>
        <div class="widget" id="widget_nextmatches"><?php echo smarty_function_load_module(array('name' => 'matches','action' => 'widget_nextmatches','items' => '3'), $this);?>
</div>
        <div class="widget" id="widget_latestmatches"><?php echo smarty_function_load_module(array('name' => 'matches','action' => 'widget_latestmatches','items' => '3'), $this);?>
</div>
        <div class="widget" id="widget_topmatch"><?php echo smarty_function_load_module(array('name' => 'matches','action' => 'widget_topmatch'), $this);?>
</div>
        <div class="widget" id="widget_shoutbox"><?php echo smarty_function_load_module(array('name' => 'shoutbox','action' => 'widget_shoutbox'), $this);?>
</div>        
    </td>

    <!-- Middle + Center = Main Content -->
    <td class="cell1" width="99%">
        <?php echo $this->_tpl_vars['content']; ?>

    </td>

    <!-- Right Widget Bar -->
    <td id="right_widget_bar" class="cell1">
           <div class="widget" id="widget_login"><?php echo smarty_function_load_module(array('name' => 'account','action' => 'widget_login'), $this);?>
</div>
    
        <div class="widget" id="widget_tsviewer"><?php echo smarty_function_load_module(array('name' => 'teamspeakviewer','action' => 'widget_tsviewer'), $this);?>
</div>
        <div class="widget" id="widget_tsministatus"><?php echo smarty_function_load_module(array('name' => 'teamspeakviewer','action' => 'widget_tsministatus'), $this);?>
</div>
        <div class="widget" id="widget_shockvoiceviewer"><?php echo smarty_function_load_module(array('name' => 'shockvoiceviewer','action' => 'widget_shockvoiceviewer'), $this);?>
</div>
    </td>
</tr>
<tr>
    <!-- Bottom Widget Bar -->
    <td id="bottom_widget_bar" class="cell1" width="100%" colspan="3" align="center" valign="top">
        <div class="widget" id="widget_quotes"><?php echo smarty_function_load_module(array('name' => 'quotes','action' => 'widget_quotes'), $this);?>
</div>
        <div class="widget" id="widget_lastregistered"><?php echo smarty_function_load_module(array('name' => 'users','action' => 'widget_lastregisteredusers'), $this);?>
</div>
        <div class="widget" id="widget_randomuser"><?php echo smarty_function_load_module(array('name' => 'users','action' => 'widget_randomuser'), $this);?>
</div>
        <div class="widget" id="widget_usersonline"><?php echo smarty_function_load_module(array('name' => 'users','action' => 'widget_usersonline'), $this);?>
</div>
        <div class="widget" id="widget_stats"><?php echo smarty_function_load_module(array('name' => 'statistics','action' => 'widget_statistics'), $this);?>
</div>
    </td>
</tr>
</table>

<!-- Footer with Copyright and Theme-Copyright -->
<p style="float:left; text-align:left;">
    <br/> Theme: <?php echo $this->_supers['session']['user']['theme']; ?>
 by </p>

<p style="text-align:right;">
    <br /> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'server_stats.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</p>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'copyright.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


</body>
</html>
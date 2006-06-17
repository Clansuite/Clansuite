<?php /* Smarty version 2.6.13, created on 2006-06-17 04:52:26
         compiled from index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'mod', 'index.tpl', 79, false),)), $this); ?>
<?php $this->_cache_serials['D:/Homepage/clansuite.com/workplace/trunk/core/smarty/templates_c/\%%45^45E^45E480CD%%index.tpl.inc'] = '482dc094f34c2788636733bb157bc202'; ?>
<!-- ==== Start of <?php echo 'index.tpl'; ?>
 ==== -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="content-language" content="<?php echo $this->_tpl_vars['language']; ?>
">
<meta name="author" content="<?php echo $this->_tpl_vars['author']; ?>
">
<meta http-equiv="reply-to" content="<?php echo $this->_tpl_vars['email']; ?>
">
<meta name="description" content="<?php echo $this->_tpl_vars['description']; ?>
">
<meta name="keywords" content="<?php echo $this->_tpl_vars['keywords']; ?>
">
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['www_tpl_root']; ?>
/andreas01.css" media="screen,projection" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['www_tpl_root']; ?>
/print.css" media="print" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['css']; ?>
">
<script src="<?php echo $this->_tpl_vars['javascript']; ?>
" type="text/javascript" language="javascript"></script>
<?php echo $this->_tpl_vars['additional_head']; ?>

<?php echo $this->_tpl_vars['redirect']; ?>

<title><?php echo $this->_tpl_vars['std_page_title']; ?>
 - <?php echo $this->_tpl_vars['mod_page_title']; ?>
</title>
</head>
<body><div id="wrap">

<div id="header">
<h1>andreas01</h1>
<p><strong>"I can see you fly. You are an angel with wings, high above the ground!"</strong><br />(traditional haiku poem)</p>
</div>

<img id="frontphoto" src="<?php echo $this->_tpl_vars['www_tpl_root']; ?>
/images/front.jpg" width="760" height="175" alt="" />

<div id="avmenu">
<h2 class="hide">Menu:</h2>
<ul>
<li><a href="<?php echo $this->_tpl_vars['base_url']; ?>
">Welcome!</a></li>
<li><a href="<?php echo $this->_tpl_vars['base_url']; ?>
">Current events</a></li>
<li><a href="<?php echo $this->_tpl_vars['base_url']; ?>
">Downloads</a></li>
<li><a href="<?php echo $this->_tpl_vars['base_url']; ?>
">Art gallery</a></li>
<li><a href="<?php echo $this->_tpl_vars['base_url']; ?>
">Collections</a></li>
<li><a href="<?php echo $this->_tpl_vars['base_url']; ?>
">Litterature</a></li>
<li><a href="<?php echo $this->_tpl_vars['base_url']; ?>
">Newsletter</a></li>
</ul>

<div class="announce">
<h3>Latest news:</h3>
<p><strong>Nov 28, 2005:</strong><br />
Updated to v1.3, fixed links.</p>
<p><strong>Oct 21, 2005:</strong><br />
Updated to v1.2, with minor adjustments and corrections.</p>
<p><strong>June 25, 2005:</strong><br />
v1.0 released on OSWD.org.</p>
<p class="textright"><a href="#">Read more...</a></p>
</div>

</div>

<div id="extras">
<h3>More info:</h3>
<p>This is the third column, which can be used in many different ways. For example, it can be used for comments, site news, external links, ads or for more navigation links. It is all up to you!</p>

<h3>Links:</h3>
<p>
- <a href="http://andreasviklund.com/templates">Free website templates</a><br />
- <a href="http://openwebdesign.org/">Open Web Design</a><br />
- <a href="http://oswd.org/">OSWD.org</a><br />
- <a href="http://validator.w3.org/check/referer">Valid XHTML</a><br />
- <a href="http://jigsaw.w3.org/css-validator/check/referer">Valid CSS</a>
</p>

<h3>Version:</h3>
<p>andreas01 v1.3</p>
</div>

<div id="content">
<h2>Welcome to andreas01!</h2>

<p>
<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:482dc094f34c2788636733bb157bc202#0}'; };echo modules::get_instant_content(array('name' => 'index','func' => 'index_time','params' => "english|-"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:482dc094f34c2788636733bb157bc202#0}'; };?>
<br>
<?php echo $this->_tpl_vars['content']; ?>

</p>
</div>

<div id="footer">
Copyright &copy; 2005 <a href="http://www.clansuite.com"><span class="copyright"><?php echo $this->_tpl_vars['copyright']; ?>
</span></a>. Design by <a href="http://andreasviklund.com">Andreas Viklund</a>.
</div>

</div>
</body>
</html>
<!-- ==== End of <?php echo 'index.tpl'; ?>
 ==== -->
<?php
/*
  * Titel: 
  * [c]
  * Autor:
  * Layout:
  * Lizenz: 
  *
  * $Revision: $
  * $Source:   $
  *
  * This file is part of Clansuite - http://clansuite.knd-squad.de
  * Copyright (C) 1999 - 2006 Jens-André Koch <jakoch@web.de>
  *
  ** $Id: $
  */

require '../../shared/prepend.php';

$MainPage->assign('title','Clansuite Modul :: Downloads');
$MainPage->display("header.tpl");
?>

<div class="comments">
	<div class="drop-shadow">
		<dl class="border-shadow comments-odd">
		<dt><a class="no-underline" id="#comment1" href="#comment1">1</a> Bob remarks: <br />		
		    <span class="date"><em>#1)</em> On June 10, 2005 07:19 AM</span></dt>
			<dd><p>Bob says "hello" to a css test.</p>
			</dd>
		</dl>
	</div> <!-- end drop-shadow -->
	 
</div> <!-- end comments -->
<?php $MainPage->display("footer.tpl"); ?>
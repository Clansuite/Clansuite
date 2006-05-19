<?php
/*****************************************************************************/
/* Clansuite - just another E-Sport CMS                                      */
/* Copyright (C) 1999 - 2006 Jens-André Koch (jakoch@web.de)                 */
/*                                                                           */
/* Clansuite is free software; you can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by      */
/* the Free Software Foundation; either version 2 of the License, or         */
/* (at your option) any later version.                                       */
/*                                                                           */
/* Clansuite is distributed in the hope that it will be useful,              */
/* but WITHOUT ANY WARRANTY; without even the implied warranty of            */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             */
/* GNU General Public License for more details.                              */
/*                                                                           */
/* You should have received a copy of the GNU General Public License         */
/* along with this program; if not, write to the Free Software               */
/* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA  */
/*****************************************************************************/

require 'shared/prepend.php';
include_header('Stats');
?>
<script src="shared/jscript/toc.js" type="text/javascript"></script>
<div id="body">
    <h1>About</h1>
	 <h2>Version</h2>
    <p>
	    <dl>
		    <di><dt>Clansuite CMS</dt><dd class="first"><a href="http://prdownloads.sourceforge.net/clansuite/clansuite-0.1.0-alpha.zip?download"><?php echo $_CONFIG['version']; ?></a></dd>
	    </di>
		    <di><dt>Author</dt><dd class="first"><?php echo $_CONFIG['author']; ?></dd></dd>
	    </di>
		    <di><dt>Nerd</dt><dd class="first">technically bright but socially inept person</dd>
	    </di>
		    <di><dt>Licence</dt><dd class="first">GPL | 1999 - 2005 </dd>
		 </di>
    </dl>
    </p>
      
    <h2>Credits & Libraries</h2>
    <p>  
     <dl>
    <di>
    <dt>Daniel Morris</dt>
    <dd class="first"><a href="http://cyberai.com/inputfilter/">PHP Input Filter</a></dd>
    <dd>[ 1.2.2 | 10-05-2005| GPL]</dd>
    </di>
    <di>
    <dt>Cezary Tomczak</dt>
    <dd class="first"><a href="http://www.gosu.pl">SugoClan &amp; MyGosuMenu</a></dd>
    <dd>[ 1.2.2 | 10-05-2005| GPL]</dd>
    <dd>* for giving me the motivation to work on this longterm project</dd>
    <dd>* for his rudimentary sugoclan-0.1.0-alpha script with it's nice database and error libraries</dd>
    <dd>* dhmtl menu</dd>
    </di>
    <di>
    <dt>Monte Ohrt, Andrei Zmievski</dt>
    <dd class="first"><a href="http://smarty.php.net/"> </a></dd>
    <dd>[ v2.6.13 | 09.03.2006 | LGPL ]</dd>
    </di>
    <di>
    <dt>modernmethod.com</dt>
    <dd class="first"><a href="http://www.modernmethod.com/sajax/">Sajax - Simple Ajax Toolkit</a></dd>
    <dd>[ v0.11 ]</dd>
    </di>
    <di>
    <dt>Xiang Wei ZHUO</dt>
    <dd class="first"><a href="http://www.zhuo.org/htmlarea/">ImageManager</a></dd>
    <dd>[ v1.0 | 2004-04-01 | htmlArea-license ]</dd>
    </di>
    <di>
    <dt>Peter-Paul Koch</dt>
    <dd class="first"><a href="http://www.quirksmode.org/dom/toc.html">Table Of Contents - Javascript</a></dd>
    <dd><a href="http://jehiah.com/archive/the-holy-grail-of-toc-scripts">modified Version by Jehiah Czebotar</a></dd>
    </di>
    </dl>
    </p>
<?php
include_footer();
?>
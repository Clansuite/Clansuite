<?php header("Content-type: text/plain", TRUE); ?>
<?php
/*
 *  csQuery is a fork of the deprecated gsQuery by Jeremias Reith. 
 *  It's also inspired by gameq, squery, phgstats
 *  and several other projectes like kquery and hlsw. 
 *
 *  csQuery - gameserver query class
 *  Copyright (c) 2005-2006 Jens-André Koch <jakoch@web.de>
 *  http://www.clansuite.com
 *
 *  gsQuery - Querys game servers
 *  Copyright (c) 2002-2004 Jeremias Reith <jr@terragate.net>
 *  http://www.gsQuery.org
 *
 *  This file is part of the e-sport CMS Clansuite.
 *  This file is part of the csQuery gameserver query library.
 *
 *  The csQuery library is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU Lesser General Public
 *  License as published by the Free Software Foundation; either
 *  version 2.1 of the License, or (at your option) any later version.
 *
 *  The csQuery library is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *  Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public
 *  License along with the csQuery library; if not, write to the
 *  Free Software Foundation, Inc.,
 *  59 Temple Place, Suite 330, Boston,
 *  MA  02111-1307  USA
 *
 *  SVN: $Id$
 */

include_once("../csQuery.php"); 

$gameserver=csQuery::createInstance($_GET["protocol"], $_GET["host"], $_GET["queryport"]);
  
if(!$gameserver) {
  echo serialize($gameserver);
  exit(0);
}

$gameserver->query_server(TRUE, TRUE);

echo $gameserver->serialize();

?>

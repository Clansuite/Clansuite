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
 
/**
 *	Clansuite - the e-sport CMS 
 *
 *	@author		Jens-Andre Koch	<jakoch@web.de>
 *	@package	Clansuite
 *	@version	-dev 0.1 -alpha
 * 	$Date$
 * 	$Revision$
 *
 * 	$Id$
 *
 * 	$Log$
 */

include 'shared/prepend.php';

$MainPage->assign('username', $_SESSION['User']['nick'] );
$MainPage->assign('clansuite_version', $_CONFIG['version']);
$MainPage->display("index.tpl");

?>
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

require '../shared/prepend.php'; 

$TITLE = 'Clansuite - Control Center :: TemplateEditor'; 
include 'shared/header.tpl';    // Header einbinden
include 'shared/menuclass.php'; // Adminmenü aus Db holen und einbinden

// MAIN
$_CONFIG['template_dir'] = ROOT.'/admin/';
$Page = new SmarterTemplate( "templateeditor.tpl" );



 # Security ... ?
$file=$_GET['file'];
$file_ending = explode(".", $file);
 $test = end($file_ending);
 $test = strtoupper($test);
 if ($test != "TPL" && !empty($test))
 {
 echo "<font color=ff0000>Versuch eine andere Datei als \".tpl\" zu laden ($test)<br />Scriptstop!<br /></font>";
 exit;
 }
 ###ende security
 
function write_file($url,$value,$del=0,$write='w',$strip=1) {
 if ($del) {
  @unlink ($url);
 }
 @chmod ($url, 0666);
 if ($strip) {
  $value=stripslashes($value);
 }
 if (is_writable($url)) {
  fputs(fopen ($url, $write),$value);
 } 
 else 
{
  echo "Problem beim speichern<br>";
  exit;
}
}

//template öffnen
function get_file($url,$read='r') {
 $a = fread(fopen($url, $read),filesize ($url));
 return $a;
}

//templates auflisten
function templates($dir='') {
 global $_GET, $Page, $filecontent, $f_, $filename;
 $dir = "../templates";
 if (isset($_GET['file']) && $_GET['file'] != "") {
  $filecontent = get_file($dir."/".$_GET['file']);
  $filecontent = htmlspecialchars($filecontent);
  $filename = str_replace(".tpl","",$_GET['file']);
  $f_ = $dir."/".$_GET['file'];
 } else {
  $add = "disabled=\"true\";";
 }
 $handle = opendir($dir);
 $tpl_files = array();
 array_push($tpl_files,'------');
 while ($file = readdir($handle)) {
  if ($file != "." && $file != "..") {
   if(strstr($file,".tpl")) {
    array_push($tpl_files,$file);
   }
  }
 }
 closedir($handle);
 
 
$Page->assign('templates_list', $tpl_files);

//$Page->assign('filecontent', $filecontent);
//$Page->assign('filename', $filename);
$Page->output();
}

// Template speichern
if ($_POST['savetpl']) {
 write_file($_POST['url'],$_POST['content']);
}

templates();

include 'shared/footer.tpl';    // Footer einbinden
?>
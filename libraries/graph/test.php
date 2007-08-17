<html><head>
<title>Advanced Graphing Class Test Program</title>
<style>
body {
	font-family:arial;
	text-align:center;
}
tr,th,td {
	font-family:arial;
	border: 1px solid black;
	border-collapse: collapse;
}
table {
	font-family:arial;
	border: 1px solid black;
	border-collapse: collapse;
	width:80%;
}
</style></head>
<body>
<h3>Advanced Graphing Class Test Program</h3><br>
<h4>Environmental Tests</h4>
<table align=center><tr><th>Test<th></th><th>Result<th></th><th>Resolution</tr>
<?php
error_reporting(0);
report('PHP Version',((int)phpversion()>=5),'This class only works with PHP 5 and above. You can download PHP 5 from php.net.');
report('Safe Mode',!ini_get('safe_mode'),'Safe mode must be disabled to use this class.  Your web hosting company may be at fault.');
report('gd Extension',extension_loaded('gd'),'You must enable the gd extension.  Modify your php.ini file or recompile PHP with this extension.');
$GDArray = gd_info();
$version = ereg_replace('[[:alpha:][:space:]()]+', '', $GDArray['GD Version']);
report('gd Version',(int)($version)>=2,'Your gd extension must be version 2 or above.  Modify your php.ini file or recompile PHP with this extension.');
report('FreeType Support',$GDArray['FreeType Support'],'Your gd extension must support FreeType.  Modify your php.ini file or recompile PHP with this extension.');
?>
</table>
<br>
<h4>File Tests</h4>
<table align=center><tr><th>File<th></th><th>Result<th></th><th>Resolution</tr>
<?php
report('graph.oo.php',file_exists('graph.oo.php'),'All files are available <a href="http://www.zackbloom.org/graph/graph.oo.dl.php">here</a>');
report('arial.ttf',file_exists('arial.ttf'),'All files are available <a href="http://www.zackbloom.org/graph/graph.oo.dl.php">here</a>');
report('wz_jsgraphics.js (optional)',file_exists('wz_jsgraphics.js'),'All files are available <a href="http://www.zackbloom.org/graph/graph.oo.dl.php">here</a>');
?>
</table>
<br>
<h4>Experimental Tests</h4>
<table align=center><tr><th>Test<th></th><th>Result<th></th><th>Time</tr>
<?php
error_reporting(E_ALL);
include 'graph.oo.php';

if(!file_exists('images/tmp'))
	mkdir('images/tmp');

if(file_exists('images/tmp/test1.png'))
	unlink('images/tmp/test1.png');
b1();
$graph = new graph();
$graph->showkey = true;
$graph->type = "bar";
$graph->showxgrid = false;
$graph->colorlist = true;
$graph->key = array('alpha','beta','gamma','delta','pi');
$graph->keyinfo = 1;
$graph->addBulkPoints('4,2,5,3,4');
$graph->graph();
$graph->showGraph('images/tmp/test1.png'); 
report('Bar Graph',file_get_contents('images/tmp/test1.png')==file_get_contents('images/test/test1.png'),false,b2());

if(file_exists('images/tmp/test2.png'))
	unlink('images/tmp/test2.png');
b1();
$graph = new graph();
$graph->setProp("showkey",true);
$graph->setProp("type","pie");
$graph->setProp("showgrid",false);
$graph->setProp("key",array('alpha','beta','gamma','delta','pi'));
$graph->setProp("keywidspc",-50);
$graph->setProp("keyinfo",2);
$graph->addBulkPoints('4,2,5,3,4');
$graph->graph();
$graph->showGraph('images/tmp/test2.png');
report('Pie Graph',file_get_contents('images/tmp/test2.png')==file_get_contents('images/test/test2.png'),false,b2());

if(file_exists('images/tmp/test3.png'))
	unlink('images/tmp/test3.png');
b1();
$graph = new graph();
$graph->addPoint(1,3);
$graph->addPoint(6,4);
$graph->addPoint(2,7);
$graph->addPoint(4,1);
$graph->graph();
$graph->showGraph("images/tmp/test3.png");
report('Simple Line Graph',file_get_contents('images/tmp/test3.png')==file_get_contents('images/test/test3.png'),false,b2());

if(file_exists('images/tmp/test4.png'))
	unlink('images/tmp/test4.png');
b1();
$graph = new graph();
$graph->addBulkPoints('4,2,5,3,4');
$graph->setColor('color',0,'orange');
$graph->setProp('title','Programming - Sleep Relation');
$graph->setProp('titlesize',18);
$graph->setProp('xlabel','Sleep');
$graph->setProp('ylabel','Programming Ability');
$graph->setProp('scale',array('Kinda Tired','Really Tired','Very Tired'));
$graph->graph();
$graph->showGraph("images/tmp/test4.png");
report('Complex Line Graph',file_get_contents('images/tmp/test4.png')==file_get_contents('images/test/test4.png'),false,b2());


if(file_exists('images/tmp/test5.png'))
	unlink('images/tmp/test5.png');
b1();
$graph = new graph();
$graph->setProp('funcinterval',.125);
$graph->graphFunction('x^2',-10,10);
$graph->graphFunction('10sqrt(x)',-10,10,1);
$graph->graphFunction('10sin(x)',-10,10,2);
$graph->setColor('',0,'red');
$graph->setProp('title',"Function Graphing");
$graph->setColor('',2,'green');
$graph->graph();
$graph->showGraph("images/tmp/test5.png"); 
report('Function Graph',file_get_contents('images/tmp/test5.png')==file_get_contents('images/test/test5.png'),false,b2());

?>
</table>
<br><b>Did you run into a problem not checked for by this program? Send me an email: <a href="mailto:god@zackbloom.org">god@zackbloom.org</a>.</b>
<?php
function report($test,$res,$fail,$fa=false){
	echo "<tr><td width=30%>$test<td></td><td width=15%>";
	if($res)
		echo "<font color=green>PASS</font>";
	else
		echo "<font color=red>FAIL</font>";
	if(!$res)
		echo "<td></td><td>$fail";
	elseif($fail==false)
		echo "<td></td><td>$fa";
	else
		echo "<td></td><td>";
	echo "</tr>";
}
function b1(){
	global $t;
	$t = microtime(true);
}
function b2(){
	global $t;
	return round((microtime(true)-$t)*1000,0).'ms';
}
?>
</body></html>
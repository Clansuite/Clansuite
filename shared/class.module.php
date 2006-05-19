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

class Module {
	
	/*
	* 1. Module werden anhand ihrer Configs identifiziert.
	* 2. Diese Funktion scannt alle Unterverzeichnisse des 
	* Modul-Verzeichnisses und liest diese Configs aus.
	* 3. Es gibt 2 Arten von Config-Dateien: 
	* einerseits PHP-basierte und anderseits XML-basierte.
	*
	* @return $module (array)
	*/
		
	function scanforModules(){
	global $Db;
		$baseDir = ROOT .'/module';
		$module = array();
		if ($handle = opendir($baseDir))
		{	$ranking = 1;
			while (false !== ($file = readdir($handle)))
			{
				if($file != '.' && $file != '..' && $file != 'CVS' 
					&& is_dir($baseDir.'/'.$file))
				{
					// 2 Arten von Modul-Configs
					// PHP
					// XML  
					$phpconfigfile = $baseDir.'/'.$file.'/config.php';
					$xmlconfigfile = $baseDir.'/'.$file.'/plugin.'.$file.'.xml';
					echo "<pre>Modul #".$ranking."</pre>";
					
					if(file_exists($phpconfigfile))
					{	//todo: iniset auslesen
						// phpclasses checken
						include $baseDir.'/'.$file.'/config.php';
						#echo '<br><font color=green>Modulname:'.$file.'</font>';
						#echo "<br>id: ".$id;
						#echo "<br>name: ".$name;
						$module[$ranking][name] = $name;
						$module[$ranking][id] = $id; 
						
					}
					elseif(file_exists($xmlconfigfile))
					{	
						echo "<br><font color=green>Modulname:  ".$file.'</font><br>';
						echo "<br>Configfile: ".$xmlconfigfile.'<br>';
						
						$xml = simplexml_load_file($xmlconfigfile);
	 					#print_r($xml);
	 					
	 					#beachte
						#$xml->moduleDescription[0]->id;
	 					#$xml->moduleDescription[0][id];
	 					#echo (string)$xml->moduleDescription[0][id];
	 					#####
	 					
						echo "<br>Comment: ". $xml->moduleDescription->comment;
						$module[$ranking][name] = (string)$xml->moduleDescription->name;
						$module[$ranking][id] = (string)$xml->moduleDescription->id;
					}
					else {  
						echo '<br>Found Moduldirectory but ConfigFile is missing: <font color="red">'.$file.'</font><br>';					
						$missingsettings = '1';
					}
					
					if ($missingsettings == '0') {					
					echo 'DB :',$module[$ranking][name];
					$Db->execute("INSERT INTO " . DB_PREFIX . "module SET m_name = ?", $module[$ranking][name]);
					}
					
					$ranking++;
					$missingsettings = '0';
				}
			}
		}
		ksort($module);
		#foreach($module as $key => $value)
		#{
		#	$return[] = $value;
		#}
		#return $return;
		return $module;
	}

	/*
	* Fügt die Module in die DB ein.
	*
	*
	*/
	
	function updateModules_DB() {
	global $Db;
	
	$modulearray = Module::scanforModules();
	# $module[$ranking][name]
	# $module[$ranking][id]
	
	foreach($modulearray as $key => $value)
	{
	#echo 'key: '.$key;
	#echo 'value: '.$value;
	foreach($value as $vkey => $vvalue)
	{
	#echo 'key: '.$vkey;
	#echo 'value: '.$vvalue;
	#$Db->execute("INSERT INTO " . DB_PREFIX . "module SET m_name = ?", $modul[name]);
	}
	
	}
}

	function addModulestoMenu() { 
	
	//generate menu from arraydata
	//adminmenu
	
	// 1. position des Modul-Buttons ermitteln
	// getRow GET ID WHERE type ?, text = ?", "button", "Module";
	// = ParentID für die Plugins
	
	
	
	//publicmenu
	}

	
	function listActivPlugins() {
	// get all active modules from database
	global $Db;	
	
	$activeModules = $Db->getALL("SELECT * 
				      FROM " . DB_PREFIX . "module 
				      WHERE m_enabled = ?", "1" );
	
	echo $activeModules;
	return $activeModules;
	}

}


?>
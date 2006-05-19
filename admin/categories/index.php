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
require '../../shared/prepend.php';


//SELECT * FROM `" . DB_PREFIX . "category` WHERE 
//`cat_id`, `cat_modulname`, `cat_sortorder`, `cat_name`, `cat_image_url`, `cat_description`

// Main function to update table information
function changeText($sValue) {
    global $Db;
    // decode submitted data
    $sValue_array = explode("~~|~~", $sValue);
    $sCell = explode("#", $sValue_array[1]);
     // strip bad stuff
    $parsedInput = htmlspecialchars($sValue_array[0], ENT_QUOTES);
    // update DB
    if ($sCell[0]) {     
       $Db->execute("UPDATE " . DB_PREFIX . "category SET $sCell[1]= '$parsedInput' WHERE cat_id = '$sCell[0]'");
           }
    // create string to return to the page
    $newText = '<div onclick="editCell(\''.$sValue_array[1].'\', this);">'.$parsedInput.'</div>~~|~~'.$sValue_array[1];
    return $newText;
}
function changeImage($id, $value) {
    global $Db;
    
    // decode submitted data
    //$sValue_array = explode("~~|~~", $sValue);
    //$sCell = explode("#", $sValue_array[1]);
    //$sCell[1] = substr($sCell[1], 4);    // gibt string ohne die ersten 4 chars "File" zurÃ¼ck
    // strip bad stuff
    //$parsedInput = htmlspecialchars($sValue_array[0], ENT_QUOTES);
    //update DB
    //if ($sCell[0]) {                  // cat_image_url
       //$Db->execute("UPDATE " . DB_PREFIX . "category SET $sCell[1]= '$parsedInput' WHERE cat_id = '$sCell[0]'");
       
       $Db->execute("UPDATE " . DB_PREFIX . "category SET cat_image_url= '$value' WHERE cat_id = '$id'");
         //  }
    // create string to return to the page
    //$newText = '<div onclick="editCell(\''.$sValue_array[1].'\', this);">'.$parsedInput.'</div>~~|~~'.$sValue_array[1];
    //return $newText;
}
// sajax
require("../../shared/Sajax.php");
$sajax_request_type = "GET";
sajax_init();
$sajax_debug_mode = 1;
sajax_export("changeText","changeImage");
sajax_handle_client_request();

$TITLE = 'Administration: Categories'; 
include '../shared/header.tpl';    // Header einbinden
include '../shared/menuclass.php'; // Adminmenü aus Db holen und einbinden
?>
<html>
<head>

<script type="text/javascript" src="../shared/imagemanager/assets/dialog.js"></script>
<script type="text/javascript" src="../shared/imagemanager/IMEStandalone.js"></script>
<script type="text/javascript">
//<![CDATA[
<?php sajax_show_javascript(); ?>

    function textChanger_cb(result) {
        var result_array=result.split("~~|~~");
        document.getElementById(result_array[1]).innerHTML = result_array[0];
        Fat.fade_element(result_array[1], 30, 1500, "#EEFCC5", "#FFFFFF")
    }
    
    function parseForm(cellID, inputID) {
        var temp = document.getElementById(inputID).value;
        var obj = /^(\s*)([\W\w]*)(\b\s*$)/;
        if (obj.test(temp)) { temp = temp.replace(obj, '$2'); }
        var obj = /  /g;
        while (temp.match(obj)) { temp = temp.replace(obj, " "); }
        if (temp == " ") { temp = ""; }
        if (! temp) {alert("This field must contain at least one non-whitespace character.");return;}
        var st = document.getElementById(inputID).value + '~~|~~' + cellID;
        document.getElementById(cellID).innerHTML = "<span class=\"update\">Updating...</span>";
        x_changeText(st, textChanger_cb);
        document.getElementById(cellID).style.border = 'none';
    }

    function editCell(id, cellSpan) {
        var inputWidth = (document.getElementById(id).offsetWidth / 7);
        var oldCellSpan = cellSpan.innerHTML;
        document.getElementById(id).innerHTML = "<form name=\"activeForm\" onsubmit=\"parseForm('"+id+"', '"+id+"input');return false;\" style=\"margin:0;\" action=\"\"><input type=\"text\" class=\"dynaInput\" id=\""+id+"input\" size=\""+ inputWidth + "\" onblur=\"parseForm('"+id+"', '"+id+"input');return false;\"><br /><noscript><input value=\"OK\" type=\"submit\"></noscript></form>";
        document.getElementById(id+"input").value = oldCellSpan;
        document.getElementById(id+"input").focus();
        document.getElementById(id).style.background = '#ffc';
        document.getElementById(id).style.border = '1px solid #fc0';
    }
    
   function bgSwitch(ac, td) {
        if       (ac == 'on')	{ td.style.background = '#ffc'; } 
        else if (ac == 'off')	{ td.style.background = '#ffffff'; }
    }   
    
    function parseImage(id,value) {
        var cellID = id + '#cat_image_url';
		  var sti = document.getElementById(id).value + '~~|~~' + cellID;
        x_changeImage(sti);
    }
    
      //Create a new Imanager Manager, needs the directory where the manager is
		//and which language translation to use.
		var manager = new ImageManager('../shared/imagemanager','en');
	   var filename = '';
	   
		//Image Manager wrapper. Simply calls the ImageManager
		ImageSelector = 
		{
			//This is called when the user has selected a file
			//and clicked OK, see popManager in IMEStandalone to 
			//see the parameters returned.
			update : function(params)
			{
				if(this.field && this.field.value != null)
				{  this.field.value = params.f_file; //params.f_url
				   filename = this.field.value;
				}
			},
			//open the Image Manager, updates the textfield
			//value when user has selected a file.
			select: function(textfieldID)
			{
				this.field = document.getElementById(textfieldID);
				manager.popManager(this);	
			}
		}        
    //]]> 
</script>
<link rel="Stylesheet" href="../../shared/style2.css" type="text/css" media="screen" />
<script type="text/javascript" src="../shared/fat.js"></script>    

</head>
<body>

 <h1>Categories</h1>
 <a href="index_normal.php">un-ajax-ified view: Listenansicht der Cats</a>

<div id="datExample">
	<table class="dynatab" border="0">
		<tr class="yellow">
		         <th>#</th>
		        <th>cat_modulname</th>
		        <th>cat_sortorder</th>
		        <th>cat_name</th>
		        <th>cat_image_url<br />Please select an image file.</th>
		        <th>cat_description</th>
		 </tr>
    <?php
    $catlist = $Db->getAll("SELECT cat_id, cat_modulname, cat_sortorder, 
											cat_name, cat_image_url, cat_description
 									FROM " . DB_PREFIX . "category ORDER BY cat_modulname DESC");
 	
				 	// Sajax - Db Insert
					//cellID = this.field.id + '#cat_image_url';
					//var sti = this.field.value + '~~|~~' + cellID;
					
					//x_changeImage(sti);
 	
 	
 	 foreach ($catlist as $cat): 
        echo "<tr>\n\n\t";
        echo "<td>".$cat['cat_id']."</td>\n\t";
        echo '<td class="point" id="'.$cat['cat_id'].'#cat_modulname" onmouseover="bgSwitch(\'on\', this);" onmouseout="bgSwitch(\'off\', this);"><div onclick="editCell(\''.$cat['cat_id'].'#cat_modulname\', this);">'.$cat['cat_modulname'].'</div></td>';
        //echo '<td>'.$cat['cat_sortorder'].'</td>';
        echo "\n\t";
        echo '<td class="point" id="'.$cat['cat_id'].'#cat_image_url" onmouseover="bgSwitch(\'on\', this);" onmouseout="bgSwitch(\'off\', this);"><div onclick="editCell(\''.$cat['cat_id'].'#cat_image_url\', this);">'.$cat['cat_image_url'].'</div></td>';
        echo "\n\t";
        echo '<td class="point" id="'.$cat['cat_id'].'#cat_name" onmouseover="bgSwitch(\'on\', this);" onmouseout="bgSwitch(\'off\', this);"><div onclick="editCell(\''.$cat['cat_id'].'#cat_name\', this);">'.$cat['cat_name'].'</div></td>';
        echo "\n\t";
        echo '<td class="point" id="'.$cat['cat_id'].'#cat_image_url" onmouseover="bgSwitch(\'on\', this);" onmouseout="bgSwitch(\'off\', this);">';
        echo "\n\t";
        echo '<div><form action="" name="activeForm" />
				  <input size=35 type="text" id="File'.$cat['cat_id'].'" onblur="x_changeImage('.$cat['cat_id'].', filename)" class="selectFile" name="File" value="'.$cat['cat_image_url'].'" />
				  <input type="button" name="select" value=" File '.$cat['cat_id'].' " onclick="ImageSelector.select(\''.$cat['cat_id'].'#cat_image_url\');"/>
				  </form></div></td>'; 
        echo '<td>'.$cat['cat_description'].'</td>';
        echo "</tr>\n";
    endforeach; ?>
    
    </table>
 </div>       
</body>
</html>
<?php include '../shared/footer.tpl'; ?>
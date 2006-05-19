<script language="JavaScript">;
function klapptext (id) { 
if (document.getElementById("klapptextRow_" + id).style.display == 'none') { 
document.getElementById("klapptextImg_" + id).src = "images/minus.gif"; 
document.getElementById("klapptextRow_" + id).style.display = ""; 
document.getElementById("klapptextImg_" + id).alt = "Hier klicken um die weiteren Infos zu verstecken"; 
} else { 
document.getElementById("klapptextImg_" + id).src = "images/plus.gif"; 
document.getElementById("klapptextRow_" + id).style.display = "none"; 
document.getElementById("klapptextImg_" + id).alt = "Hier klicken um die weiteren Infos anzuzeigen"; 
} } </script>;
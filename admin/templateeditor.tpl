<?php global $filecontent, $f_; ?>
<h2>Template Editor</h2>

<select name="file" onchange="window.location=('?do=templates&dir=&file='+this.options[this.selectedIndex].value)">
{options:templates_list}</select>

<script language=\"javascript\">
  function preview() {
   var name = document.editor.name.value;
   var content = document.editor.content.value;
   doc = window.open(\"\",\"\",\" width=600, height=600 toolbar=no, statusbar=no, scrollbars=yes\");
   doc.document.write (\"<title> Preview :- \"+name+\".tpl </title>\\n\");
   doc.document.write( content );
  }
 </script>
 
 <table width=60% cellspacing=1 cellpadding=3 border=0><tr><td>
 
 <form method=post action="{PHP_FILE}?do=submit" name="editor">
 
    <table border=0 width=100% align=center>
      <tr>
       <td><b>Templatename :</b></td>
        <input type=hidden name=\"name\" value='<?php echo $filename; ?>'>
                        <td width=80%><b style='font-size:10pt'><?php echo $f_; ?></b>
        <input type=hidden name=url value='<!php echo $f_;!>'>
        </td>
      </tr>
      <tr>
        <td width=80% colspan=\"2\">
        <textarea name=\"content\" rows=\"30\" style='width:100%;font-size: 9pt;font-family: courier new'><?php echo $filecontent; ?></textarea>
        </td>
      </tr>
      <tr>
        <td colspan=2 align=center>
          <input type=submit value='Speichern' name='savetpl'>
          <input type=button value='Vorschau' <!php echo $add; !> onclick=\"javascript:preview()\">
        </td>
      </tr>
    </table>
</form></table>
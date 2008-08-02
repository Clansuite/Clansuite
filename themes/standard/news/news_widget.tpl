{* {$news_widget|var_dump} *}

<!-- News Widget from Standard Theme /-->

<!-- Start News Widget //-->
<div class="container_right">
<div class="container_head">Aktuelle News</div>
<table class="news_widget_info" width="100%">
  <tr>
    <td>Titel</td>
    <td width="70">Datum</td>
  </tr>
</table>
<ul>
 {foreach item=news_widget from=$news_widget}
   <li class="news_widget_row">
     <table class="news_widget_row" width="100%">
       <tr>
        <td class="col1" ><a href="index.php?mod=news">{$news_widget.news_title}</a></td>
        <td class="col2" width="70">{$news_widget.news_added}</td>
       </tr>
     </table>
   </li>
 {/foreach}
 </ul>
</div>
<!-- Ende News Widget //-->
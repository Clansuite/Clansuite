{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$shoutbox_widget|@var_dump}
*} 

<!-- Start Widget Shoutbox from Module Shoutbox //-->

<div class="shoutbox_widget" id="widget_shoutbox" width="100%">

    <h2 class="td_header">{t}Shoutbox{/t}</h2>
{foreach item=shoutbox from=$shoutbox_widget}
<div class="shoutbox_row">
	<div class="shoutbox_head">
		<span class="shoutbox_name">{$shoutbox.name}</span>
		<span class="shoutbox_mail"><a href="mailto:{$shoutbox.mail}"><img src="../../../themes/core/images/icons/email_open_image.png" border="0" /></a></span>
	</div>
	<div class="shoutbox_time_row"><span class="shoutbox_time">am: {$shoutbox.time|date_format:"%d.%m.%y, %H:%M:%S"} Uhr</span></div>
    <div class="shoutbox_msg_row"><span class="shoutbox_msg">{$shoutbox.msg}</span></div>
</div>
{/foreach}
</div>
<!-- End Widget Shoutbox from Module Shoutbox //-->
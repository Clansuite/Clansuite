{move_to target="pre_head_close"}
<script type="text/javascript">
// <![CDATA[
window.onload = function ()
{
  // check to see if firebug is installed and enabled
  if(window.console && window.console.firebug)
  {
    var firebug = document.getElementById('firebug-warning');
    firebug.style.display = 'block';
  }
  /*else
  {
    alert('Firebug is not installed or de-activated.');
  }*/
}
// ]]>
</script>
{/move_to}

<div id="firebug-warning" style="padding-top: 10px; display: none; text-align: center;">
{messagebox level="alert"}
    It appears that <strong>you have firebug enabled</strong>.
    <br/>
    Using firebug with Clansuite will cause a <strong>significant performance degradation</strong>    
{/messagebox}
</div>

<input value="Custom Styled Notice" onclick="$.pnotify({
	pnotify_title: 'Custom Styled Notice',
	pnotify_text: 'I have an additional class that\'s used to give me special styling. I always wanted to be pretty.',
	pnotify_addclass: 'custom',
	pnotify_notice_icon: 'icon picon_32x32_emotes_face-monkey'
});" type="button" class="ui-state-default ui-corner-all" />

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
  /* else { alert('Firebug is not installed or de-activated.'); } */
}
// ]]>
</script>
{/move_to}

<div id="firebug-warning" style="padding-top: 10px; display: none; text-align: center;">
{messagebox level="alert"}
    It appears that <strong>you have firebug enabled</strong>.
    <br/>
    Using firebug with Clansuite will cause a <strong>significant performance degradation</strong>.
    <br/>
    <br />
    <input type="button" class="ui-button ui-widget ui-state-default ui-corner-all"
           value="Uhm, yes - i'm debugging something - so shut up!"
           onclick="$('#firebug-warning').toggle();" />
{/messagebox}
</div>
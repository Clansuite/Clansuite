<script type="text/javascript">
<![CDATA[
    window.onload = function ()
    {
        // check to see if firebug is installed and enabled
        if (window.console && console.firebug)
        {
            var firebug = document.getElementById('firebug');
            firebug.style.display = 'block';
            firebug.innerHTML = 'It appears that <strong>you have firebug enabled</strong>.' +
            'Using firebug with Clansuite will cause a <strong>significant performance degradation</strong>.';
        }
    }
]]>
</script>
{* {$silverlightchart|@var_dump} *}

<div class="ModuleHeading">Silverlight Charts Sample</div>
<div class="ModuleHeadingSmall">Beispiele f&uuml;r die Benutzung des Visifire Silverlight Chart in Clansuite.</div>

<div class="clansuite-container" style="font-family: Verdana, Arial, sans-serif">

<script src="{$www_root}/libraries/visifire/Visifire2.js" type="text/javascript"></script>

    {tabpanel name="Visifire Silverlight Chart"}

        {tabpage name="Date Time Axis"}

        <h2>Date Time Axis</h2>
        <br />
        
    	<div id="VisifireChart0" >

			<script src="{$www_root}/libraries/visifire/samples/DateTimeAxis.js" type="text/javascript"></script>
        
    	</div>


        {/tabpage}

        {tabpage name="First Chart"}

        <h2>First Chart</h2>
        <br/>
        
    	<div id="VisifireChart1" >
			<script src="{$www_root}/libraries/visifire/samples/FirstChart.js" type="text/javascript"></script>     
    	</div>
    	
        {/tabpage}

        {tabpage name="Js Event Handling"}

        <h2>Js Event Handling</h2>
        <br/>
        
        <div id="VisifireChart2" >
			<script src="{$www_root}/libraries/visifire/samples/JsEventHandling.js" type="text/javascript"></script>
    	</div>

        {/tabpage}

        {tabpage name="Live Update"}

        <h2>Live Update</h2>
        <br />
        
        <div id="VisifireChart3" >
			<script src="{$www_root}/libraries/visifire/samples/LiveUpdate.js" type="text/javascript"></script>
    	</div>

        {/tabpage}
        
        {tabpage name="Styles and Animation"}

        <h2>Styles and Animation</h2>
        <br />
        
        <div id="VisifireChart4" >
			<script src="{$www_root}/libraries/visifire/samples/StylesAndAnimation.js" type="text/javascript"></script>
    	</div>

        {/tabpage}

    {/tabpanel}

</div>
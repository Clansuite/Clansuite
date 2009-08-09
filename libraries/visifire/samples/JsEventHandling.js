            // Create Visifire object
            var vChart2 = new Visifire2('libraries/visifire/SL.Visifire.Charts.xap', "MyChart2", 500, 300);

            // vChart1.setLogLevel(0); // If you want to disable logging.

            // Chart Data XML
            var chartXml = '<vc:Chart xmlns:vc="clr-namespace:Visifire.Charts;assembly=SLVisifire.Charts" Width="500" Height="300" Theme="Theme1" AnimationEnabled="True" BorderThickness="2" Background="White" Bevel="True" BorderBrush="Black" View3D="False">'
               + '     <vc:Chart.Titles>'
               + '         <vc:Title Text="Athens 2004 Olympics" FontSize="14"/>'
               + '     </vc:Chart.Titles>'
               + '     <vc:Chart.Series>'
               + '         <vc:DataSeries RenderAs="Column" LabelEnabled="True">'
               + '             <vc:DataSeries.DataPoints>'
               + '                 <vc:DataPoint AxisXLabel="USA" YValue="35"/>'
               + '                 <vc:DataPoint AxisXLabel="China" YValue="32"/>'
               + '                 <vc:DataPoint AxisXLabel="Russia" YValue="27"/>'
               + '                 <vc:DataPoint AxisXLabel="Australia" YValue="17"/>'
               + '                 <vc:DataPoint AxisXLabel="Japan" YValue="16"/>'
               + '             </vc:DataSeries.DataPoints>'
               + '         </vc:DataSeries>'
               + '     </vc:Chart.Series>'
               + ' </vc:Chart>';

            // Set Chart Data XML
            vChart2.setDataXml(chartXml);

            /* 
                On preload event, array of charts is received as event arguments.  
                Events can be attached to chart elements. And required customization can be done here.
            */
            vChart2.preLoad = function(args)
            {   
                var chart = args[0];   // Chart reference.

                /*  
                    Attach an event handler on MouseLeftButtonUp event with the first DataSeries of the first Chart.
                    DataPoint reference is received as event argument.
                */
                chart.Series[0].MouseLeftButtonUp = function(dataPoint)
                {
                    alert("AxisXLabel =" + dataPoint.AxisXLabel + " YValue = " + dataPoint.YValue);
                };
                
                // Attach Events to a Title. Title reference is received as event argument.
                chart.Titles[0].MouseLeftButtonUp = function(title)
                {
                    alert(title.Text);
                }
            }
            
            // Render chart
            vChart2.render("VisifireChart2");
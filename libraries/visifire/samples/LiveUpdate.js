        var chart;              // A global Chart variable
        var dataPointIndex = 0; // Variable for DataPoint Indexing
        
        /*
            Function updates YValue property of a DataPoint
        */
        function updateYValue(newYValue, dataPointIndex)
        {
            if (chart != null)
            {
                try
                {
                    chart.Series[0].DataPoints[dataPointIndex].SetPropertyFromJs("YValue", newYValue);
                }
                catch (e)
                {
                }
            }
        };
        
        /*
            Timer function allows updating chart data with a time interval.
        */
        function timer()
        {   
            // Calculate new YValue for the DataPoint
            var newYValue = Math.abs(Math.random() * 100 - 10 * dataPointIndex);
            
            // Update a YValue property of a DataPoint
            updateYValue(newYValue, dataPointIndex);

            // Update dataPointIndex
            dataPointIndex = (dataPointIndex > 4) ? 0 : dataPointIndex + 1;
            
            // Set timeout for timer() function
            setTimeout(timer, 1000);
        };


            // Create Visifire object
            var vChart3 = new Visifire2('libraries/visifire/SL.Visifire.Charts.xap', "MyChart3", 500, 300);

            // vChart1.setLogLevel(0); // If you want to disable logging.
            
            // Chart XML
            var chartXml = '<vc:Chart xmlns:vc="clr-namespace:Visifire.Charts;assembly=SLVisifire.Charts" Width="500" Height="300" AnimationEnabled="False" BorderThickness="0.5" Background="White" BorderBrush="Black" Padding="3">'
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
            vChart3.setDataXml(chartXml);
 
            /* 
                On loaded event, array of charts is received as event arguments. It fires once the chart is loaded. 
                Events can be attached to chart elements. And required customization can be done here.
            */
            vChart3.loaded = function(args)
            {   
                chart = args[0];   // Chart reference.

                // Start Timer
                timer();
            }
           
            // Render chart
            vChart3.render("VisifireChart3");
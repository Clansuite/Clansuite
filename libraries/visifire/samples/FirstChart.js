            // Create Visifire object
            var vChart1 = new Visifire2('libraries/visifire/SL.Visifire.Charts.xap', "MyChart1", 500, 300);
            
            // vChart1.setLogLevel(0); // If you want to disable logging.
            
            // Chart Data XML
            var chartXml = '<vc:Chart xmlns:vc="clr-namespace:Visifire.Charts;assembly=SLVisifire.Charts" Width="500" Height="300" BorderThickness="0.5" Padding="3"  >'
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
            vChart1.setDataXml(chartXml);

            // Render chart
            vChart1.render("VisifireChart1");
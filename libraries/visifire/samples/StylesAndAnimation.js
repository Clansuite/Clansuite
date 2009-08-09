            // Create Visifire object
            var vChart4 = new Visifire2('libraries/visifire/SL.Visifire.Charts.xap', "MyChart4", 500, 300);

            // vChart1.setLogLevel(0); // If you want to disable logging.
            
            // Chart Style XML
            var styleXml = '<Canvas.Resources>'
            
                + '    <Style  x:Key="TitleStyle" TargetType="vc:Title">'
                + '        <Setter Property="FontColor" Value="Red"></Setter>'
                + '    </Style>'

                + '    <Style  x:Key="ChartStyle" TargetType="vc:Chart">'
                + '        <Setter Property="Background" Value="#dbdbdb"></Setter>'
                + '        <Setter Property="Bevel" Value="True"></Setter>'
                + '        <Setter Property="BorderBrush" Value="Black"></Setter>'
                + '    </Style>'
                
                + '</Canvas.Resources>';
            
            // Chart XML
            var chartXml = styleXml + '<vc:Chart Style="{StaticResource ChartStyle}" xmlns:vc="clr-namespace:Visifire.Charts;assembly=SLVisifire.Charts" Width="500" Height="300" Theme="Theme1" AnimationEnabled="True" BorderThickness="2"  View3D="False">'
               + '     <vc:Chart.Titles>'
               + '         <vc:Title Style="{StaticResource TitleStyle}" Text="Athens 2004 Olympics" FontSize="14"/>'
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
                
               + '         <vc:DataSeries RenderAs="Line" LabelEnabled="True">'
               + '             <vc:DataSeries.DataPoints>'
               + '                 <vc:DataPoint AxisXLabel="USA" YValue="3"/>'
               + '                 <vc:DataPoint AxisXLabel="China" YValue="32"/>'
               + '                 <vc:DataPoint AxisXLabel="Russia" YValue="27"/>'
               + '                 <vc:DataPoint AxisXLabel="Australia" YValue="17"/>'
               + '                 <vc:DataPoint AxisXLabel="Japan" YValue="20"/>'
               + '             </vc:DataSeries.DataPoints>'
               + '         </vc:DataSeries>'
               
               + '     </vc:Chart.Series>'
               + ' </vc:Chart>';
            
            // Set Chart XML
            vChart4.setDataXml(chartXml);
            
            // Render chart
            vChart4.render("VisifireChart4");
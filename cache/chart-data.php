<?php

// generate some random data:

srand((double)microtime()*1000000);

$max = 50;
$data = array();
for( $i=0; $i<12; $i++ )
{
  $data[] = rand(0,$max);
}

// use the chart class to build the chart:
require_once '../libraries/ofc/open-flash-chart.php';
$g = new graph();


// Spoon sales, March 2007
$g->title( 'Spoon sales '. date("Y"), '{font-size: 26px;}' );

$g->set_data( $data );
// label each point with its value
$g->set_x_labels( array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec' ) );

// set the Y max
$g->set_y_max( 60 );
// label every 20 (0,20,40,60)
$g->y_label_steps( 6 );

// display the data
echo $g->render();
?>
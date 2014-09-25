<html>
  <head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">

<?php
$con=mysql_connect("localhost","root","admin") or die("Failed to connect with database!!!!");
mysql_select_db("strom", $con);
$sth = mysql_query("SELECT timestamp, Aussentemp, KesselSoll, KesselIst FROM heizung WHERE timestamp > SUBTIME(NOW(), '1 0:0:0')");
 
$rows = array();
//flag is not needed
$flag = true;
$table = array();
// $table['cols'] = array(
    // array('label' => 'datum', 'type' => 'datetime'),
	// array('label' => 'temp', 'type' => 'number')
// );
// $data[0] = array('timestamp', 'active_power');
//$table[0] = array('timestamp', 'Aussentemp');
$table = array(
    'cols' => array (
        array('label' => 'Date', 'type' => 'datetime'),
        array('label' => 'Aussen', 'type' => 'number'),
        array('label' => 'KesselSoll', 'type' => 'number'),
        array('label' => 'KesselIst', 'type' => 'number')
    ),
    'rows' => array()
);


while($row = mysql_fetch_assoc($sth)) {
	// preg_match('/(\d{4})-(\d{2})-(\d{2})\s(\d{2}):(\d{2}):(\d{2})/', $row['timestamp'], $match);
	// $year = (int) $match[1];
    // $month = (int) $match[2] - 1; // convert to zero-index to match javascript's dates
    // $day = (int) $match[3];
    // $hours = (int) $match[4];
    // $minutes = (int) $match[5];
    // $seconds = (int) $match[6];
	
	$table['rows'][] = array('c' => array(
        array('v' => 'Date(' . date('Y,n,d,H,i,s', strtotime($row['timestamp'])).')'),
        array('v' => floatval($row['Aussentemp'])),
		array('v' => floatval($row['KesselSoll'])),
		array('v' => floatval($row['KesselIst']))
    ));
}
 
// $table['rows'] = $rows;
$jsonTable = json_encode($table);
// echo $jsonTable;
?>
 
    <!--Load the Ajax API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
 
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1', {'packages':['corechart']});
 
    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);
 
    function drawChart() {
 
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(<?=$jsonTable?>);
      var options = {
           title: ' Heizung ',
          height: 600
        };
      // Instantiate and draw our chart, passing in some options.
      // Do not forget to check your div ID
      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
    </script>
  </head>
 
  <body>
    <!--this is the div that will hold the pie chart-->
    <div id="chart_div"></div>
  </body>
</html>
<?php

include "mySQL2JsonDataTable.php";

$con=mysql_connect("localhost","root","admin") or die("Failed to connect with database!!!!");
mysql_select_db("strom", $con);
$date = $_POST['date'];
if($date == ""){
	$result = mysql_query("SELECT timestamp,  active_power as Watt FROM stromzaehler WHERE timestamp > SUBTIME(NOW(), '1 0:0:0')");
}
else{
	$result = mysql_query("SELECT timestamp,  active_power as Watt, zaehlerstand-(SELECT MIN(zaehlerstand) FROM `stromzaehler` WHERE `timestamp` LIKE '" . $date . "%') AS Stand FROM stromzaehler WHERE timestamp like '" . $date . "%'");
}
// SELECT zaehlerstand-(SELECT MAX(zaehlerstand) FROM `stromzaehler` WHERE `timestamp` LIKE '2014-09-23%') FROM `stromzaehler` WHERE `timestamp` LIKE '2014-09-24%'
// echo $date
// print_r ($_POST);
echo GetJSONfrommySQL($result);
?>

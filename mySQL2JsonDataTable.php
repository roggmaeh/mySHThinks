<?php
function GetArrayFromTableColumns($mySQLresult)
{
	$cols = array();
	$fields = mysql_num_fields($mySQLresult);
	for($i=0; $i < $fields; $i++) {
		$name = mysql_field_name($mySQLresult, $i);
		$mySQLtype = mysql_field_type($mySQLresult, $i);
		switch(strtoupper($mySQLtype)) {
			case "INTEGER":
			case "INT":
			case "SMALLINT":
			case "TINYINT":
			case "MEDIUMINT":
			case "BIGINT":
			case "DECIMAL":
			case "NUMERIC":
			case "FLOAT":
			case "DOUBLE":
				$type = "number";
				break;
			case "DATETIME":
				$type = "datetime";
				break;
			default:
				$type = "Value";
		}
		$cols[] = array('label' => $name, 'type' => $type);
	}
	return $cols;
}

function GetmArrayFromTableRows($mySQLresult)
{
	$rows = array();
	$fields = mysql_num_fields($mySQLresult);
	while($myrow = mysql_fetch_assoc($mySQLresult)) {
		$row = array();
		for($i=0; $i < $fields; $i++) {
			$mySQLtype = mysql_field_type($mySQLresult, $i);
			$mySQLfieldname = mysql_field_name($mySQLresult, $i);
			switch(strtoupper($mySQLtype)) {
				case "DATETIME":
					$row[] = array('v' => 'Date(' . date('Y,n,d,H,i,s', strtotime($myrow[$mySQLfieldname])).')');
					break;
				default:
					$row[] = array('v' => $myrow[$mySQLfieldname]);
			}
		}
		$rows[] = array('c' => $row);
	}
	return $rows;
}

function GetJSONfrommySQL($mySQLresult){
	$table = array(
		'cols' => (GetArrayFromTableColumns($mySQLresult)),
		'rows' => (GetmArrayFromTableRows($mySQLresult))
	);
	return json_encode($table);
}

$con=mysql_connect("localhost","root","admin") or die("Failed to connect with database!!!!");
mysql_select_db("strom", $con);
$result = mysql_query("SELECT timestamp, active_power as Watt FROM stromzaehler WHERE timestamp > SUBTIME(NOW(), '1 0:0:0')");
 
echo GetJSONfrommySQL($result);
?>

<?php
  include '../../connection.php';

mysql_set_charset('utf8');
$id = $_GET['id'];

$sql = "SELECT sample_picture
            FROM sample
            WHERE sample_ID = '$id';";

		$result = mysql_query("$sql") or die("<b>Error:</b> Problem on Retrieving Image BLOB<br/>" . mysql_error());
		$row = mysql_fetch_array($result);
		// header("Content-type: " . $row["sample_picture"]);
		header("Content-type: image/jpeg");
        echo $row["sample_picture"];

?>
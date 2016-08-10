<?
include '../../connection.php';
$sampleID = $_GET["id"];

$sql = "SELECT sample_picture
FROM sample
WHERE sample_ID = '$sampleID';";
$picture = mysqli_fetch_row(mysqli_query($link, $sql))[0];


echo"
<!DOCTYPE html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
  	<title>Fraunhofer CCD</title>
</head>
<body>
<img src='".$picture."' width='1000' height='1000' alt='Sample picture' />
</body>
</html>";
?>
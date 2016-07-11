<?php
include '../../connection.php';
session_start();

$sql = "SELECT sample_ID FROM sample ORDER BY sample_picture DESC;";
 


?>

<head>
	<title>Fraunhofer CCD</title>
</head>
<body>
	<?php include '../header.php'; ?>
	<div class='container'>
		<div class='row well well-lg'>
			<form name="frmImage" enctype="multipart/form-data" action="testUploadInsert.php" method="post" class="frmImageUpload">
			<div class='col-md-6'>
				<label>Picture: </label>
				<br>
				<label class="btn btn-default btn-file">Choose File
				<input type="file" id='sample_file' name='sample_file' style='display: none;' onchange='$("#sample_file_name").html($(this).val());'>
				</label>
				<span id="sample_file_name"></span>
			</div> 
			<input type="submit" value="Submit" class="btnSubmit" />
			</form>
			</div>
			<div class='row well well-lg'>

			<?php
			$result = mysqli_query($link, $sql);
	while($row = mysqli_fetch_array($result)) {
	?>
		<p><?php echo $row["sample_ID"]; ?></p>
		<img src="testUploadView.php?id=<?php echo $row["sample_ID"]; ?>" /><br/>
	
<?php		
	}
    mysql_close($conn);
?>
		</div>
	</div>
</body>

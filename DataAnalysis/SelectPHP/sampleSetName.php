<?php
session_start();
include '../../connection.php';

$sampleSetDate = mysqli_real_escape_string($link, $_POST['sampleSetDate']);

$sampleSetNumberSql = "SELECT MAX(MID(sample_set_name, 12,2))
FROM sample_set
WHERE MID(sample_set_name, 5, 6) = '$sampleSetDate';";
$sampleSetNumber = mysqli_fetch_row(mysqli_query($link, $sampleSetNumberSql))[0] + 1;

$samplesOfTheDaySql = "SELECT sample_set_ID, sample_set_name
FROM sample_set
WHERE MID(sample_set_name, 5, 6) = '$sampleSetDate';";
$samplesOfTheDayResult = mysqli_query($link, $samplesOfTheDaySql);

if(mysqli_fetch_row($samplesOfTheDayResult)){
	echo"
	<label>Sets previously initialized on this day:</label>";

	$samplesOfTheDayResult = mysqli_query($link, $samplesOfTheDaySql);
	while($row = mysqli_fetch_row($samplesOfTheDayResult)){
		echo"
		<div class='form-group'>
			<a class='sample_set_name' onclick='setSampleSetIDAndRefresh(".$row[0].")'>".$row[1]."</a>
		</div>";
	}
}

echo"
<div class='form-group'>
	<label>Preview of set name: </label>
	<p class='sample_set_name'>CCD-".$sampleSetDate."-</p><input type='number' id='sample_set_number' name='sample_set_number' class='form-control' style='display: inline-block;' min='1' max='99' value='$sampleSetNumber'></p>
</div>
<div class='form-group'>
	<label>Preview of sample name: </label>
	<p class='sample_set_name'>CCD-".$sampleSetDate."-<p id='sample_set_number_echo' name='sample_set_number_echo' style='display:inline;'>".$sampleSetNumber."</p>-01</p>
</div>
";

?>
<script>
	$('#sample_set_number').on('change', function() {
		var number = $(this).val();
		var padded = ('00' + number).substring(number.length);
		$(this).val(padded)
		$("#sample_set_number_echo").html(padded);

	}).trigger('change');

</script>
<?
mysqli_close($link);
?>
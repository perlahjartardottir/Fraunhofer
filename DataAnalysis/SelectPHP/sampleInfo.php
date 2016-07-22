<?
include '../../connection.php';
session_start();

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);
$_SESSION["sampleID"] = $sampleID;

      $sampleSql = "SELECT sample_name, sample_material, sample_comment
      FROM sample
      WHERE sample_ID = '$sampleID';";
      $sampleResult= mysqli_query($link, $sampleSql);
      $row = mysqli_fetch_row($sampleResult);

      echo "
        <p><strong>Material: </strong>".$row[1]."</p>
        <p><strong>Comment: </strong>".$row[2]."</p>";
?>
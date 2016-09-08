<?
include '../../connection.php';
session_start();

$sampleSetID = mysqli_real_escape_string($link, $_POST["sampleSetID"]);
$_SESSION["sampleSetID"] = $sampleSetID;
$sampleID = $_SESSION["sampleID"];

      $samplesInSetSql = "SELECT sample_ID, sample_name
      FROM sample
      WHERE sample_set_ID = '$sampleSetID'
      ORDER BY sample_ID;";
      $samplesInSetResult = mysqli_query($link, $samplesInSetSql);


      echo"
         <label>Sample: </label>
         <select id='sample_ID' class='form-control' onchange='setSampleIDAndRefresh(this.value)' style='width:auto;'>
            <option value='-1'>Choose a sample</option>";
            while($row = mysqli_fetch_array($samplesInSetResult)){
              echo "<option value='".$row[0]."'>".$row[1]."</option>";
            }
           echo"
          </select>
          <script>
          $('#sample_ID').val(".$sampleID.")
          </script>";

?>
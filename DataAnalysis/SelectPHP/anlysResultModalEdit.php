<?
include '../../connection.php';
session_start();

$sampleID = $_SESSION["sampleID"];
$resID = mysqli_real_escape_string($link, $_POST["resID"]);
$eqPropID = mysqli_real_escape_string($link, $_POST["eqPropID"]);
$maxFileSize = $_SESSION["fileValidation"]["maxSize"];

$propertySql = "SELECT p.anlys_prop_name as propName, e.anlys_eq_name as eqName, a.anlys_eq_prop_unit as unit, a.anlys_param_1 as param1, a.anlys_param_2 as param2, a.anlys_param_3 as param3,
a.anlys_param_1_unit as param1unit, a.anlys_param_2_unit as param2unit, a.anlys_param_3_unit as param3unit, a.anlys_aveg as dispAveg, a.anlys_eq_prop_ID as eqPropID
FROM anlys_property p, anlys_equipment e, anlys_eq_prop a
WHERE a.anlys_eq_ID = e.anlys_eq_ID AND a.anlys_prop_ID = p.anlys_prop_ID
AND a.anlys_eq_prop_ID = '$eqPropID';";
$propertyRow = mysqli_fetch_array(mysqli_query($link, $propertySql));

$resultSql = "SELECT anlys_eq_prop_ID as eqPropID, anlys_res_result as result, anlys_res_comment as comment, anlys_res_1, anlys_res_2, anlys_res_3, prcs_ID as prcsID
FROM anlys_result
WHERE anlys_res_ID = '$resID';";
$resultRow = mysqli_fetch_array(mysqli_query($link, $resultSql));

$anlysFilesSql = "SELECT anlys_res_file_ID, anlys_res_file
FROM anlys_res_file
WHERE anlys_res_ID = '$resID';";
$anlysFilesResult = mysqli_query($link, $anlysFilesSql);

$sampleNameSql = "SELECT sample_name
FROM sample
WHERE sample_ID = '$sampleID';";
$sampleName = mysqli_fetch_row(mysqli_query($link, $sampleNameSql))[0];

$coatingsSql = "SELECT p.prcs_ID, p.prcs_coating
FROM process p
WHERE p.sample_ID = '$sampleID'
ORDER BY p.prcs_ID DESC;";
$coatingsResult = mysqli_query($link, $coatingsSql);

echo"
<div class='modal-dialog'>
  <div class='modal-content '>
    <form role='form' action='../UpdatePHP/editAnlysResult.php' method='post' enctype='multipart/form-data' onsubmit='return anlysResultValidation(".$sampleID.",".$eqPropID.",this)'>
      <div class='modal-header'>
        <div class='col-md-12'>
          <button type='button' id='close_modal' class='btn close glyphicon glyphicon-remove' data-dismiss='modal'></button>
        </div>
        <h3 class='center_heading'>".$propertyRow['propName']." - ".$propertyRow['eqName']."</h3>
        <h5 class='center_heading'>".$sampleName."</h5>
      </div>
      <div class='modal-body'>
        <div id='error_message_edit'></div>
        <input type='hidden' id='anlys_res_ID' name='res_ID' value='".$resID."'>
        <input type='hidden' id='anlys_sample_ID' name='sample_ID' value='".$sampleID."'>
         <input type='hidden' id='eq_prop_ID' name='eq_prop_ID' value=".$eqPropID.">
        <div class='form-group'>
        <label>Layer of coating:</label>
          <select id='coating' name='res_coating_edit' class='form-control'>";
              while($row = mysqli_fetch_row($coatingsResult)){
                if($row[0] == $resultRow['prcsID']){
                  echo "
                    <option value='".$row[0]."' selected >".$row[1]."</option>";
                  // echo"
                  // <option value='audi' selected>Audi</option>";
                }
                else{
                  echo "
                    <option value='".$row[0]."'>".$row[1]."</option>";
                }
              }
        if($resultRow['prcsID'] == NULL){
        echo"
            <option selected>No Coating</option>";
        }
        else{
          echo"
            <option>No Coating</option>";
        }
        echo"
         </select>
        </div>";

        // If we use the anlys_result field
        if($propertyRow['dispAveg'] || $propertyRow['propName'] == 'Adhesion'){
          echo"
          <div class='form-group'>
            <label id='anlys_res_prop_name'>".$propertyRow['propName'];
              if($propertyRow['unit']){
                echo"
                (".$propertyRow['unit'].")";
              }
              echo"
            </label>
            <input type='number' id='anlys_res_result' name='res_res_edit' step='any' value='".$resultRow['result']."' class='form-control'>
          </div>";
        }
        // Display parameters if any
        for($i = 3; $i < 6; $i++){
          if($propertyRow[$i]){
            echo"
            <div class='form-group'>
              <label>".$propertyRow[$i];
            // If parameters has units
                if($propertyRow[$i+3]){
                  echo"
                  (".$propertyRow[$i+3].")";
                }

                echo"
              </label>
              <!-- Param ids from 1-3 -->
              <input type='number' id='anlys_res_param_".($i-2)."' name='res_param_".($i-2)."_edit'value='".$resultRow[$i]."' class='form-control'>
            </div>";
          }
        }
        echo"
        <div class='form-group'>
          <label>Comment</label>
          <textarea id='anlys_res_comment' name='res_comment_edit' class='form-control'>".$resultRow['comment']."</textarea> 
        </div>";

        $fileCounter = 1;
        while($fileRow = mysqli_fetch_row($anlysFilesResult)){
          echo"
          <div class='form-group'>
            <label>File ".$fileCounter.":</label>
            <a href='../DownloadPHP/downloadAnlysFile.php?id=".$fileRow[1]."'>".basename($fileRow[1])."</a>
            <br>
            <label>Delete file:</label>
            <input type='checkbox' id='anlys_file_delete' name='file_delete[]' value='yes'>
            <input type='hidden' id='anlys_file_ID' name='file_ID[]' value='".$fileRow[0]."'>
          </div>";
          $fileCounter++;
        }
        echo"
        <div class='form-group'>
        <label>Add files:</label>
          <input type='file' id='anlys_file_edit' name='anlys_file_edit[]' multiple accept='media_type' style='display:none' onchange='handleFiles(this.files)''>
          <a href='#' id='file_select_edit' class='btn btn-default btn-file'>Browse</a> 
          <div id='file_list_edit'>
            <p>No files selected.</p>
          </div>
        </div>";


        echo"
        <div class='modal-footer'>
          <button type='button' class='btn btn-danger glyphicon glyphicon-trash' onclick='deleteAnlysResult(".$resID.")'></button>
          <button type='submit' class='btn btn-success'>Save</button> 
        </div>
      </form>
    </div>
  </div>
</div>
</div>";

?>
<script>
  document.getElementById('close_modal').onclick = function(){
    modal.style.display = 'none';
  }

      // File uploading.
      // https://developer.mozilla.org/en-US/docs/Using_files_from_web_applications

      window.URL = window.URL || window.webkitURL;

      var fileSelect = document.getElementById("file_select_edit"),
      fileElem = document.getElementById("anlys_file_edit"),
      fileList = document.getElementById("file_list_edit");

      fileSelect.addEventListener("click", function (e) {
        if (fileElem) {
          fileElem.click();
        }
        e.preventDefault(); // prevent navigation to "#"
      }, false);

      function handleFiles(files) {
        if (!files.length) {
          fileList.innerHTML = "<p>No files selected.</p>";
        } else {
          fileList.innerHTML = "";
          var list = document.createElement("ul");
          fileList.appendChild(list);
          for (var i = 0; i < files.length; i++) {
            var li = document.createElement("li");
            list.appendChild(li);

            var info = document.createElement("span");
            if(files[i].size > <?php echo $maxFileSize; ?>){

              var errorMessage = "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
              "Sorry, " + files[i].name + " is too large. The max size is: " + <?php echo strval(number_format($maxFileSize/1000/1000,2)); ?> +" MB.</div>";
               info.innerHTML = files[i].name + " - " + (files[i].size/1000/1000).toFixed(2) + " MB" + errorMessage;
            }
            else{
            info.innerHTML = files[i].name;
            }
            li.appendChild(info);
          }
        }
      }
      
</script>
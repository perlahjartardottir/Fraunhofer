<?php
include '../../connection.php';
session_start();

$sampleSetID = mysqli_real_escape_string($link, $_POST['sampleSetID']);
$_SESSION["sampleSetID"] = $sampleSetID;


$sql = "SELECT sample_ID, sample_name, sample_material, sample_comment
FROM sample
WHERE sample_set_ID = '$sampleSetID';";

$materialsSql = "SELECT DISTINCT(sample_material)
FROM sample;";
$materialsResult = mysqli_query($link, $materialsSql);

$sampleSetNameSql = "SELECT sample_set_name
FROM sample_set
WHERE sample_set_ID = '$sampleSetID';";
$sampleSetName= mysqli_fetch_row(mysqli_query($link, $sampleSetNameSql))[0];

if($sampleSetID !== "-1"){
  echo"
  <div class='row well well-lg'>
    <h3 class='custom_heading'>".$sampleSetName."</h3>
     <table class='table table-responsive' style='width:92%;'>
      <thead>
        <tr>
          <th>Sample name</th>
          <th>Material</th>
          <th>Comment</th>
          <th>Picture</th>
        </tr>
      </thead>
      <tbody>";
        $result = mysqli_query($link, $sql);
        while($row = mysqli_fetch_array($result)){
        echo"
          <tr>
           <td><a onclick='loadAndShowSampleModal(".$row[0].")'>".$row[1]."</a></td>
           <td>".$row[2]."</td>
           <td>".$row[3]."</td>
           <td>Picture</td>
         </tr>";
       }
       echo"
     </tbody>
   </table>
    <button type='button' class='btn btn-primary' onclick=location.href='sampleOverview.php' style='float:right'>Sample Overview</button>
   <button type='button' class='btn btn-primary' onclick=location.href='analyze.php' style='float:right'>Analyze</button>
   <button type='button' class='btn btn-primary' onclick=location.href='process.php' style='float:right'>Process</button>
 </div>
 <div id='sample_modal_edit' class='modal'></div>";
}
echo"
<script>
  var modal = document.getElementById('sample_modal_edit');
  function loadAndShowSampleModal(sampleID){
    loadSampleModalEdit(sampleID);
    modal.style.display = 'block';
  }
  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = 'none';
    }
  }
</script>";

//  // Modal window to edit samples.
//  $allSamplesResult = mysqli_query($link, $sql);
//  while($sampleRow= mysqli_fetch_array($allSamplesResult)){
//   $sampleMaterial = $sampleRow[2];

//   echo"
//   <div class='modal fade' id='".$sampleRow[0]."' tabindex='-1' role='dialog' aria-labelledby='".$sampleRow[0]."' aria-hidden='true'>
//     <div class='modal-dialog'>
//       <div class='modal-content '>
//         <form role='form'>
//           <div class='modal-header'>
//             <div class='col-md-12'>
//               <button type='button' id='close_modal' class='btn close glyphicon glyphicon-remove' data-dismiss='modal'></button>
//             </div>
//             <h3 class='center_heading'>".$sampleRow[1]."</h3>
//           </div>
//           <div class='modal-body'>
//             <div id='error_message'></div>
//             <div class='form-group'>
//               <label>Name</label>
//               <input type='text' id='sample_name' value='".$sampleRow[1]."' class='form-control'>
//             </div>
//             <div class='form-group'>
//         <label>Material: </label>
//         <input list='materials_edit' id='material_edit' class='col-md-12 form-control' value='".$sampleRow[2]."'>
//         <datalist id='materials_edit'>";
//           while($row = mysqli_fetch_array($materialsResult)){
//             echo"<option data-value='".$row[0]."'>".$row[0]."</option>";
//           }
//           echo"
//         </datalist>
//         <input type='hidden' name='material_edit' id='material_edit-hidden'>
//             </div>
//             <div class='form-group'>
//               <label>Comment</label>
//               <textarea id='sample_comment' class='form-control'>".$sampleRow[3]."</textarea> 
//             </div>
//             <div class='modal-footer'>
//               <button type='button' class='btn btn-danger glyphicon glyphicon-trash' onclick='deleteSample(".$sampleRow[0].",this.form)' ></button>
//               <button type='button' class='btn btn-success' onclick='editSample(".$sampleRow[0].",this.form)'>Save</button> 
//             </div>
//           </form>
//         </div>
//       </div>
//     </div>
//   </div>";
//   }
// }

// //Material: So user can both choose from datalist and enter text. 
// echo"
// <script>

// $('#material_edit-hidden').val(document.getElementById('material_edit').value);
// console.log(document.getElementById('material_edit').value);

//   $('input[list]').on('input', function(e) {
//     var input = $(e.target),
//     options = $('#' + input.attr('list') + ' option'),
//     hiddenInput = $('#' + input.attr('id') + '-hidden'),
//     label = input.val();

//     hiddenInput.val(label);

//     for(var i = 0; i < options.length; i++) {
//       var option = options.eq(i);

//       if(option.text() === label) {
//         hiddenInput.val( option.attr('data-value') );
//         break;
//       }
//     }
//   });
//   </script>";
?>

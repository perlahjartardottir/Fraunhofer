<?php
include '../../connection.php';
session_start();
$securityLevel = $_SESSION["securityLevelDA"];

if($securityLevel< 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

// $allSamplesSql = "SELECT sample_ID as ID, sample_name as name, sample_set_ID as setID
// FROM sample;";
$allSamplesSql = "SELECT s.sample_ID as ID, s.sample_name as name, s.sample_set_ID as setID, p.prcs_coating as coating, p.prcs_ID as prcsID
FROM sample s RIGHT JOIN process p ON (s.sample_ID = p.sample_ID)
WHERE s.sample_ID = p.sample_ID
ORDER BY sample_name, p.prcs_ID DESC;";
$allSamplesResult = mysqli_query($link, $allSamplesSql);

?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg col-md-3'>
      <form id='search_samples_form'>
        <h4 class='custom_heading'>Search by:</h4>
<!--         <div class='col-md-12 form-group'>
          <label>Sample Name: </label>
          <input type="text" id='sample_name' class='form-control' onkeyup='displaySearchResults()' />
        </div>

   <div class='col-md-12 form-group'>
          <label>Initialization date from:</label>
          <input type="date" id='begin_date' class='form-control' onchange='displaySearchResults()' />
        </div>
        <div class='col-md-12 form-group'>
          <label>Initialization date to:</label>
          <input type="date" id='end_date' class='form-control' onchange='displaySearchResults()'/>
        </div> -->

        <!-- SEARCH COLUMNS ---->
        <div class='col-md-12 form-group'>
          <label>Coating</label>
          <div>
            <input type="text" id='coating' class='col-md-6 form-control' placeholder='E.g. AlTiN' onkeyup='displaySearchResults()' />
          </div>
        </div>
        <div class='col-md-12 form-group'>
         <label>Thickness</label>
         <div>
          <input type="number" id='min_thickness' class='col-md-6 form-control' placeholder='Min' onkeyup='displaySearchResults()' />
          <input type="number" id='max_thickness' class='col-md-6 form-control'  placeholder='Max' onkeyup='displaySearchResults()' />
        </div>
      </div>
      <div class='col-md-12 form-group'>
        <label id='roughness' class='label_expand' onload='expand(this)' onclick='expand(this)'>Roughness <span class='glyphicon glyphicon-triangle-top'></span></label>
        <div>
          <input type="number" id='min_ra' class='col-md-6 form-control' placeholder='Min Ra' onkeyup='displaySearchResults()' />
          <input type="number" id='max_ra' class='form-control' placeholder='Max Ra' onkeyup='displaySearchResults()' />
          <input type="number" id='min_rz' class='col-md-6 form-control' placeholder='Min Rz' onkeyup='displaySearchResults()' />
          <input type="number" id='max_rz' class='col-md-6 form-control' placeholder='Max Rz' onkeyup='displaySearchResults()' />
        </div>
      </div>
      <div  class='col-md-12 form-group'>
        <label id='adhesion' class='label_expand' onclick='expand(this)'>Adhesion <span class='glyphicon glyphicon-triangle-top'></span></label>
        <div>
          <input type="number" id='min_adhesion' class='col-md-6 form-control' placeholder='Min' onkeyup='displaySearchResults()' />
          <input type="number" id='max_adhesion' class='col-md-6 form-control' placeholder='Max' onkeyup='displaySearchResults()' />
        </div>
      </div>
      <div class='col-md-12 form-group'>
        <label id='contact' class='label_expand'  onclick='expand(this)'>Contact Angle <span class='glyphicon glyphicon-triangle-top'></span></label>
        <div>
          <input type="number" id='min_contact' class='col-md-6 form-control' placeholder='Min' onkeyup='displaySearchResults()' />
          <input type="number" id='max_contact' class='col-md-6 form-control' placeholder='Max' onkeyup='displaySearchResults()' />
        </div>
      </div>
      <div  class='col-md-12 form-group'>
        <label id='friction' class='label_expand' onclick='expand(this)'>Friction <span class='glyphicon glyphicon-triangle-top'></span></label>
        <div>
          <input type="number" id='min_friction' class='col-md-6 form-control' placeholder='Min' onkeyup='displaySearchResults()' />
          <input type="number" id='max_friction' class='col-md-6 form-control' placeholder='Max' onkeyup='displaySearchResults()' />
        </div>
      </div>
      <div  class='col-md-12 form-group'>
        <label id='transmittence' class='label_expand'  onclick='expand(this)'>Transmittence <span class='glyphicon glyphicon-triangle-top'></span></label>
        <div>
          <input type="number" id='min_transmittence' class='col-md-6 form-control' placeholder='Min' onkeyup='displaySearchResults()' />
          <input type="number" id='max_transmittence' class='col-md-6 form-control' placeholder='Max' onkeyup='displaySearchResults()' />
        </div>
      </div>
      <div class='col-md-12 form-group'>
       <label id='wear' class='label_expand' onclick='expand(this)'>Wear Rate <span class='glyphicon glyphicon-triangle-top'></span></label>
       <div>
        <input type="number" id='min_wear' class='col-md-6 form-control' placeholder='Min' onkeyup='displaySearchResults()' />
        <input type="number" id='max_wear' class='col-md-6 form-control' placeholder='Max' onkeyup='displaySearchResults()' />
      </div>
    </div>
    <div class='col-md-12 form-group'>
      <label id='youngs' class='label_expand' onclick='expand(this)'>Young's Modulus<span class='glyphicon glyphicon-triangle-top'></span></label>
      <div>
        <input type="number" id='min_youngs' class='col-md-6 form-control' placeholder='Min' onkeyup='displaySearchResults()' />
        <input type="number" id='max_youngs' class='col-md-6 form-control' placeholder='Max' onkeyup='displaySearchResults()' />
      </div>
    </div>
  </form>
</div>


<!-- SEARCH RESULTS ---->
<div class='col-md-9' style='padding-left: 50px;'>
  <table id='search_table' class='table table-responsive table-striped compact hover' cellspacing='0' width='100%'>
    <thead>
      <tr>
        <th>Sample</th>
        <th>Coating</th>
        <th>Thickness</th>
        <th class='roughness column_hide'>Roughness (Ra)</th>
        <th class='roughness column_hide'>Roughness (Rz)</th>
        <th class='adhesion column_hide'>Adhesion</th>
        <th class='contact column_hide'>C. Angle</th>
        <th class='friction column_hide'>Friction</th>
        <th class='transmittence column_hide'>Transm.</th>
        <th class='wear column_hide'>Wear Rate</th>
        <th class='youngs column_hide'>Young's M.</th>
      </tr>
    </thead>
    <tbody>
      <?
      while($row = mysqli_fetch_array($allSamplesResult)){
        
        $prcsID = $row['prcsID'];
        $thicknessSql = "SELECT TRUNCATE(AVG(r.anlys_res_result), 3) as avegResult, a.anlys_eq_prop_unit
        FROM anlys_result r, anlys_eq_prop a, anlys_property p
        WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND
        a.anlys_prop_ID = p.anlys_prop_ID AND r.prcs_ID = '$prcsID' AND p.anlys_prop_ID = 1
        GROUP BY r.anlys_eq_prop_ID, r.prcs_ID;";
        $thicknessRow = mysqli_fetch_row(mysqli_query($link, $thicknessSql));

        $roughnessSql = "SELECT TRUNCATE(AVG(r.anlys_res_1), 3) as ra, TRUNCATE(AVG(r.anlys_res_2), 3) as rz, a.anlys_param_1_unit, a.anlys_param_2_unit 
        FROM anlys_result r, anlys_eq_prop a, anlys_property p
        WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND
        a.anlys_prop_ID = p.anlys_prop_ID AND r.prcs_ID = '$prcsID' AND p.anlys_prop_ID = 2
        GROUP BY r.anlys_eq_prop_ID, r.prcs_ID;";
        $roughnessRow = mysqli_fetch_row(mysqli_query($link, $roughnessSql));
        
        $adhesionSql = "SELECT TRUNCATE(AVG(r.anlys_res_result), 0) as avegResult, a.anlys_eq_prop_unit
        FROM anlys_result r, anlys_eq_prop a, anlys_property p
        WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND
        a.anlys_prop_ID = p.anlys_prop_ID AND r.prcs_ID = '$prcsID' AND p.anlys_prop_ID = 4
        GROUP BY r.anlys_eq_prop_ID, r.prcs_ID;";
        $adhesionRow = mysqli_fetch_row(mysqli_query($link, $adhesionSql));

        $contactAngleSql = "SELECT TRUNCATE(AVG(r.anlys_res_result), 3) as avegResult, a.anlys_eq_prop_unit
        FROM anlys_result r, anlys_eq_prop a, anlys_property p
        WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND
        a.anlys_prop_ID = p.anlys_prop_ID AND r.prcs_ID = '$prcsID' AND p.anlys_prop_ID = 6
        GROUP BY r.anlys_eq_prop_ID, r.prcs_ID;";
        $contactAngleRow = mysqli_fetch_row(mysqli_query($link, $contactAngleSql));

        $frictionSql = "SELECT TRUNCATE(AVG(r.anlys_res_result), 3) as avegResult, a.anlys_eq_prop_unit
        FROM anlys_result r, anlys_eq_prop a, anlys_property p
        WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND
        a.anlys_prop_ID = p.anlys_prop_ID AND r.prcs_ID = '$prcsID' AND p.anlys_prop_ID = 8
        GROUP BY r.anlys_eq_prop_ID, r.prcs_ID;";
        $frictionRow = mysqli_fetch_row(mysqli_query($link, $frictionSql));

        $transmittenceSql = "SELECT TRUNCATE(AVG(r.anlys_res_result), 3) as avegResult, a.anlys_eq_prop_unit
        FROM anlys_result r, anlys_eq_prop a, anlys_property p
        WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND
        a.anlys_prop_ID = p.anlys_prop_ID AND r.prcs_ID = '$prcsID' AND p.anlys_prop_ID = 9
        GROUP BY r.anlys_eq_prop_ID, r.prcs_ID;";
        $transmittenceRow = mysqli_fetch_row(mysqli_query($link, $transmittenceSql));

        $wearRateSql = "SELECT TRUNCATE(AVG(r.anlys_res_result), 3) as avegResult, a.anlys_eq_prop_unit
        FROM anlys_result r, anlys_eq_prop a, anlys_property p
        WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND
        a.anlys_prop_ID = p.anlys_prop_ID AND r.prcs_ID = '$prcsID' AND p.anlys_prop_ID = 7
        GROUP BY r.anlys_eq_prop_ID, r.prcs_ID;";
        $wearRateRow = mysqli_fetch_row(mysqli_query($link, $wearRateSql));

        $youngsModulusSql = "SELECT TRUNCATE(AVG(r.anlys_res_result), 3) as avegResult, a.anlys_eq_prop_unit
        FROM anlys_result r, anlys_eq_prop a, anlys_property p
        WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND
        a.anlys_prop_ID = p.anlys_prop_ID AND r.prcs_ID = '$prcsID' AND p.anlys_prop_ID = 5
        GROUP BY r.anlys_eq_prop_ID, r.prcs_ID;";
        $youngsModulusRow = mysqli_fetch_row(mysqli_query($link, $youngsModulusSql));


        echo"
        <tr>
          <td><a onclick='loadAndShowSampleModal(".$row[2].",".$row[0].")'>".$row[1]."</a></td>
          <td>".$row['coating']."</td>";

          echo"
          <td>".$thicknessRow[0]." ".$thicknessRow[1]."</td>
          <td class='roughness column_hide'>".$roughnessRow[0]." ".$roughnessRow[2]."</td>
          <td class='roughness column_hide'> ".$roughnessRow[1]." ".$roughnessRow[3]."</td>
          <td class='adhesion column_hide'>".$adhesionRow[0]." ".$adhesionRow[1]."</td>
          <td class='contact column_hide'>".$contactAngleRow[0]." ".$contactAngleRow[1]."</td>
          <td class='friction column_hide'>".$frictionRow[0]." ".$frictionRow[1]."</td>
          <td class='transmittence column_hide'>".$transmittenceRow[0]." ".$transmittenceRow[1]."</td>
          <td class='wear column_hide'>".$wearRateRow[0]." ".$wearRateRow[1]."</td>
          <td class='youngs column_hide'>".$youngsModulusRow[0]." ".$youngsModulusRow[1]."</td>
        </tr>";
      }
      ?>

    </tbody>
  </table>
  <!-- Sample Modals -->
  <div id="sample_modal" class="modal"></div>
</div>

<!-- SearchPHP/searchResults.php -->
<!--   <div class="col-md-8 col-md-offset-1">
    <div id='search_results' class='table table-responsive'>
    </div>
  </div> -->
</div>
<script type="text/javascript">

  function expand(elem){
    var column = elem.id;
    $(elem).next().toggle();
    $(elem).children().toggleClass('glyphicon-triangle-top glyphicon-triangle-bottom');
    $('.'+column).toggleClass('column_display column_hide');
  }

// Custom filtering functions for two values.
// $.fn.dataTable.ext.search is an array of functions which will will be run at table draw time to see if a particular row should be included or not.
// Do this for all input fields.

$.fn.dataTable.ext.search.push(
  function( settings, data, dataIndex ) {
    var min = parseFloat( $('#min_thickness').val(), 10 );
    var max = parseFloat( $('#max_thickness').val(), 10 );
        var value = parseFloat( data[2] ) || 0; // choose column.
        
        if ( ( isNaN( min ) && isNaN( max ) ) ||
         ( isNaN( min ) && value <= max ) ||
         ( min <= value   && isNaN( max ) ) ||
         ( min <= value   && value <= max ) )
        {
          return true;
        }
        return false;
      }
      );

$.fn.dataTable.ext.search.push(
  function( settings, data, dataIndex ) {
    var min = parseFloat( $('#min_ra').val(), 10 );
    var max = parseFloat( $('#max_ra').val(), 10 );
    var value = parseFloat( data[3] ) || 0;
    
    if ( ( isNaN( min ) && isNaN( max ) ) ||
     ( isNaN( min ) && value <= max ) ||
     ( min <= value   && isNaN( max ) ) ||
     ( min <= value   && value <= max ) )
    {
      return true;
    }
    return false;
  }
  );

$.fn.dataTable.ext.search.push(
  function( settings, data, dataIndex ) {
    var min = parseFloat( $('#min_rz').val(), 10 );
    var max = parseFloat( $('#max_rz').val(), 10 );
    var value = parseFloat( data[4] ) || 0;
    
    if ( ( isNaN( min ) && isNaN( max ) ) ||
     ( isNaN( min ) && value <= max ) ||
     ( min <= value   && isNaN( max ) ) ||
     ( min <= value   && value <= max ) )
    {
      return true;
    }
    return false;
  }
  );

$.fn.dataTable.ext.search.push(
  function( settings, data, dataIndex ) {
    var min = parseFloat( $('#min_adhesion').val(), 10 );
    var max = parseFloat( $('#max_adhesion').val(), 10 );
    var value = parseFloat( data[4] ) || 0;
    
    if ( ( isNaN( min ) && isNaN( max ) ) ||
     ( isNaN( min ) && value <= max ) ||
     ( min <= value   && isNaN( max ) ) ||
     ( min <= value   && value <= max ) )
    {
      return true;
    }
    return false;
  }
  );

$.fn.dataTable.ext.search.push(
  function( settings, data, dataIndex ) {
    var min = parseFloat( $('#min_contact').val(), 10 );
    var max = parseFloat( $('#max_contact').val(), 10 );
    var value = parseFloat( data[5] ) || 0;
    
    if ( ( isNaN( min ) && isNaN( max ) ) ||
     ( isNaN( min ) && value <= max ) ||
     ( min <= value   && isNaN( max ) ) ||
     ( min <= value   && value <= max ) )
    {
      return true;
    }
    return false;
  }
  );

$.fn.dataTable.ext.search.push(
  function( settings, data, dataIndex ) {
    var min = parseFloat( $('#min_friction').val(), 10 );
    var max = parseFloat( $('#max_friction').val(), 10 );
    var value = parseFloat( data[6] ) || 0;

    if ( ( isNaN( min ) && isNaN( max ) ) ||
     ( isNaN( min ) && value <= max ) ||
     ( min <= value   && isNaN( max ) ) ||
     ( min <= value   && value <= max ) )
    {
      return true;
    }
    return false;
  }
  );

$.fn.dataTable.ext.search.push(
  function( settings, data, dataIndex ) {
    var min = parseFloat( $('#min_transmittence').val(), 10 );
    var max = parseFloat( $('#max_transmittence').val(), 10 );
    var value = parseFloat( data[7] ) || 0;
    
    if ( ( isNaN( min ) && isNaN( max ) ) ||
     ( isNaN( min ) && value <= max ) ||
     ( min <= value   && isNaN( max ) ) ||
     ( min <= value   && value <= max ) )
    {
      return true;
    }
    return false;
  }
  );

$.fn.dataTable.ext.search.push(
  function( settings, data, dataIndex ) {
    var min = parseFloat( $('#min_wear').val(), 10 );
    var max = parseFloat( $('#max_wear').val(), 10 );
    var value = parseFloat( data[8] ) || 0;
    
    if ( ( isNaN( min ) && isNaN( max ) ) ||
     ( isNaN( min ) && value <= max ) ||
     ( min <= value   && isNaN( max ) ) ||
     ( min <= value   && value <= max ) )
    {
      return true;
    }
    return false;
  }
  );

$.fn.dataTable.ext.search.push(
  function( settings, data, dataIndex ) {
    var min = parseFloat( $('#min_youngs').val(), 10 );
    var max = parseFloat( $('#max_youngs').val(), 10 );
    var value = parseFloat( data[9] ) || 0;
    
    if ( ( isNaN( min ) && isNaN( max ) ) ||
     ( isNaN( min ) && value <= max ) ||
     ( min <= value   && isNaN( max ) ) ||
     ( min <= value   && value <= max ) )
    {
      return true;
    }
    return false;
  }
  );

$(document).ready(function() {

  $('#nav_search').button('toggle');
    // Hide some of the search input fields. 
    expand($('.label_expand'));
    // Create the datatable
    var table = $('#search_table').DataTable({
      'pageLength': 100
    });
    
    // Event listeners for the search box. 

    $('#coating').keyup( function() {
      table.search(this.value);
      table.draw();
    });

    var inputFields = '#min_thickness, #max_thickness, #min_ra, #max_ra, #min_rz, #max_rz, #min_adhesion, #max_adhesion,'
    + '#min_contact, #max_contact,'
    + '#min_friction, #max_friction, #min_transmittence, #max_transmittence, #min_wear, #max_wear,'
    + '#min_youngs, #max_youngs';

    $(inputFields).keyup( function() {
      table.draw();
    });

  });

var modal = document.getElementById('sample_modal');
// Display the modal. 
function loadAndShowSampleModal(sampleSetID,sampleID){
  loadSampleModal(sampleSetID, sampleID);
  modal.style.display = "block";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

</script>
</body>
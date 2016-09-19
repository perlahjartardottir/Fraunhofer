<?php
include '../../connection.php';

$allSamplesSql = "SELECT sample_ID as ID, sample_name as name, sample_set_ID as setID
FROM sample;";
$allSamplesResult = mysqli_query($link, $allSamplesSql);

?>
<table id='search_table' class='table table-responsive table-striped' style='width:100%;'>
    <thead>
    	<tr>
      		<th>Sample</th>
      		<th>Coating</th>
      		<th>Thickness</th>
          <th class='roughness column_hide'>Roughness</th>
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
    	
      // Coating
      $coatingSql = "SELECT prcs_coating
                    FROM process
                    WHERE sample_ID = '$row[0]';";
      $coating = mysqli_fetch_row(mysqli_query($link, $coatingSql))[0];

      // Thickness
      $thicknessSql = "SELECT TRUNCATE(AVG(r.anlys_res_result), 3), a.anlys_eq_prop_unit
              FROM anlys_result r, anlys_eq_prop a
              WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND r.sample_ID = '$row[0]' AND r.anlys_eq_prop_ID IN (SELECT anlys_eq_prop_ID
              FROM anlys_eq_prop
              WHERE anlys_prop_ID = (SELECT anlys_prop_ID
                                    FROM anlys_property
                                    WHERE anlys_prop_name LIKE 'Thickness'))
              GROUP BY r.anlys_eq_prop_ID
              ORDER BY r.anlys_res_ID DESC
              LIMIT 1;";
        $thicknessRow = mysqli_fetch_row(mysqli_query($link, $thicknessSql));


      echo"
    		<tr>
    			<td><a onclick='loadAndShowSampleModal(".$row[2].",".$row[0].")'>".$row[1]."</a></td>
    			<td>".$coating."</td>";

       	echo"
    			<td>".$thicknessRow[0]." ".$thicknessRow[1]."</td>
          <td class='roughness column_hide'>Roughness</td>
          <td class='adhesion column_hide'>Adhesion</td>
          <td class='contact column_hide'>C. Angle</td>
          <td class='friction column_hide'>Friction</td>
          <td class='transmittence column_hide'>Transmittence</td>
          <td class='wear column_hide'>Wear Rate</td>
          <td class='youngs column_hide'>Young's Modulus</td>
    		</tr>";
    }
    ?>

    </tbody>
</table>
<!-- Sample Modals -->
<div id="sample_modal" class="modal"></div>
<script>

$(document).ready(function() {
  var table = $('#search_table').dataTable( {
        'pageLength': 100
  });

})

var modal = document.getElementById('sample_modal');
function loadAndShowSampleModal(sampleSetID,sampleID){
  loadSampleModal(sampleSetID, sampleID);
  modal.style.display = "block";
}
 </script>
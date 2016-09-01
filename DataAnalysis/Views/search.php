<?php
include '../../connection.php';
session_start();
$securityLevel = $_SESSION["securityLevelDA"];

if($securityLevel< 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

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
          <label>Coating: </label>
          <input type="text" id='coating' class='form-control' onkeyup='displaySearchResults()' />
        </div> -->
<!--         <div class='col-md-12 form-group'>
          <label>Initialization date from:</label>
          <input type="date" id='begin_date' class='form-control' onchange='displaySearchResults()' />
        </div>
        <div class='col-md-12 form-group'>
          <label>Initialization date to:</label>
          <input type="date" id='end_date' class='form-control' onchange='displaySearchResults()'/>
        </div> -->
        <div class='col-md-12 form-group'>
          <label>Name</label>
            <div>
              <input type="text" id='name' class='col-md-6 form-control' placeholder='E.g. 161007-01-01' onkeyup='displaySearchResults()' />
            </div>
        </div>
        <div class='col-md-12 form-group'>
          <label>Coating</label>
            <div>
              <input type="text" id='coating' class='col-md-6 form-control' placeholder='E.g. AlTiN' onkeyup='displaySearchResults()' />
            </div>
        </div>
        <div class='col-md-12 form-group'>
          <label>Thickness </label>
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
          <label id='adhesion' class='label_expand' onload='expand(this)' onclick='expand(this)'>Adhesion <span class='glyphicon glyphicon-triangle-top'></span></label>
          <div>
            <input type="number" id='min_adhesion' class='col-md-6 form-control' placeholder='Min' onkeyup='displaySearchResults()' />
            <input type="number" id='max_adhesion' class='col-md-6 form-control' placeholder='Max' onkeyup='displaySearchResults()' />
          </div>
        </div>
        <div class='col-md-12 form-group'>
          <label id='contact' class='label_expand' onload='expand(this)' onclick='expand(this)'>Contact Angle <span class='glyphicon glyphicon-triangle-top'></span></label>
          <div>
            <input type="number" id='min_contact' class='col-md-6 form-control' placeholder='Min' onkeyup='displaySearchResults()' />
            <input type="number" id='max_contact' class='col-md-6 form-control' placeholder='Max' onkeyup='displaySearchResults()' />
          </div>
        </div>
        <div  class='col-md-12 form-group'>
          <label id='friction' class='label_expand' onload='expand(this)' onclick='expand(this)'>Friction <span class='glyphicon glyphicon-triangle-top'></span></label>
          <div>
            <input type="number" id='min_friction' class='col-md-6 form-control' placeholder='Min' onkeyup='displaySearchResults()' />
            <input type="number" id='max_friction' class='col-md-6 form-control' placeholder='Max' onkeyup='displaySearchResults()' />
          </div>
        </div>
        <div  class='col-md-12 form-group'>
          <label id='transmittence' class='label_expand' onload='expand(this)' onclick='expand(this)'>Transmittence <span class='glyphicon glyphicon-triangle-top'></span></label>
          <div>
            <input type="number" id='min_transmittence' class='col-md-6 form-control' placeholder='Min' onkeyup='displaySearchResults()' />
            <input type="number" id='max_transmittence' class='col-md-6 form-control' placeholder='Max' onkeyup='displaySearchResults()' />
          </div>
        </div>
        <div class='col-md-12 form-group'>
         <label id='wear' class='label_expand' onload='expand(this)' onclick='expand(this)'>Wear Rate <span class='glyphicon glyphicon-triangle-top'></span></label>
         <div>
          <input type="number" id='min_wear' class='col-md-6 form-control' placeholder='Min' onkeyup='displaySearchResults()' />
          <input type="number" id='max_wear' class='col-md-6 form-control' placeholder='Max' onkeyup='displaySearchResults()' />
        </div>
      </div>
      <div class='col-md-12 form-group'>
        <label id='youngs' class='label_expand' onload='expand(this)' onclick='expand(this)'>Young's Modulus<span class='glyphicon glyphicon-triangle-top'></span></label>
        <div>
          <input type="number" id='min_youngs' class='col-md-6 form-control' placeholder='Min' onkeyup='displaySearchResults()' />
          <input type="number" id='max_youngs' class='col-md-6 form-control' placeholder='Max' onkeyup='displaySearchResults()' />
        </div>
      </div>
      <input type='hidden' id='now_filtering' value=''/>
      <input type='hidden' id='column_number' value=''/>
    </form>
  </div>

  <!-- SearchPHP/searchResults.php -->
  <div class="col-md-8 col-md-offset-1">
    <div id='search_results' class='table table-responsive'>
    </div>
  </div>
</div>
<script type="text/javascript">

  function expand(elem){
    var column = elem.id;
    $(elem).next().toggle();
    $(elem).children().toggleClass('glyphicon-triangle-top glyphicon-triangle-bottom');
    $('.'+column).toggleClass('column_display column_hide');
  }

  $(document).ready(function(){
    displaySearchResults();
    $('#nav_search').button('toggle');
    expand($('.label_expand'));

    // Event listener to the two range filtering inputs to redraw on input
    $('#min_thickness, #max_thickness').keyup( function() {
        $('#now_filtering').val('thickness');
        $('#column_number').val('2');
        $('#search_table').dataTable().fnFilter();
          
    });
    $('#min_ra, #max_ra, #min_rz, #max_rz').keyup( function() {
        $('#now_filtering').val('thickness');
        $('#now_filtering').val('3');
        // $('#search_table').dataTable().fnFilter();
    });



  });


  /* Custom filtering function */
$.fn.dataTable.ext.search.push(
    function(settings, data, dataIndex) {
        var property = $('#now_filtering').val();
        var column = parseInt($('#column_number').val(), 10);
        var min = parseFloat( $('#min_'+property).val(), 10 );
        var max = parseFloat( $('#max_'+property).val(), 10 );
        var thickness = parseFloat( data[column] ) || 0; // choose column.

        if ( ( isNaN( min ) && isNaN( max ) ) ||
             ( isNaN( min ) && thickness <= max ) ||
             ( min <= thickness   && isNaN( max ) ) ||
             ( min <= thickness   && thickness <= max ) )
        {
            return true;
        }
        return false;
    }
);




</script>
</body>
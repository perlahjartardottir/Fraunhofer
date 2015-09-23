<?php
include '../../connection.php';
$department_name = mysqli_real_escape_string($link, $_POST['department_name']);
$group_by_select = mysqli_real_escape_string($link, $_POST['group_by_select']);

// If we are in the overview view then we want to filter the table after we
// change the value of the cost code
if($group_by_select != ""){
  echo"<script>overview();</script>";
  echo"<label>Cost code: </label>
       <select id='cost_code' class='form-control' onchange='overview();'>";
} else{
  echo"<label>Cost code: </label>
       <select id='cost_code' class='form-control'>";
}
if($department_name == 'PVD'){
  echo "<option value='PVD'>PVD overall</option>
        <option value='P-001'>PVD - K1 (P-001)</option>
        <option value='P-002'>PVD - K2 (P-002)</option>
        <option value='P-003'>PVD - LaserArc (P-003)</option>
        <option value='P-004'>PVD75 (P-004)</option>";
} else if($department_name == 'CVD'){
  echo "<option value='CVD'>CVD overall</option>
        <option value='C-001'>CVD - 915 (C-001)</option>
        <option value='C-002'>CVD - DS I (C-002)</option>
        <option value='C-003'>CVD - DS II (C-003)</option>
        <option value='C-004'>CVD - DS III (C-004)</option>
        <option value='C-005'>CVD - DS IV (C-005)</option>
        <option value='C-006'>CVD - DS V (C-006)</option>
        <option value='C-007'>CVD - DS VI (C-007)</option>
        <option value='C-008'>CVD - HF I (C-008)</option>
        <option value='C-009'>CVD - Etcher (C-009)</option>";
} else if($department_name == 'Infrastructure'){
  echo "<option value='Infrastructure'>Infrastructure overall</option>
        <option value='I-001'>Miele Washer / Dryer / US stations (I-001)</option>
        <option value='I-002'>Tools, work bench, lathe, band saw (I-002)</option>
        <option value='I-003'>Sandblaster (I-003)</option>
        <option value='I-004'>Coborn Lapping Station (I-004)</option>
        <option value='I-005'>Logitech LP50 Incl. GI20 (I-005)</option>
        <option value='I-006'>Cutting Laser (I-006)</option>";
} else if($department_name == 'Analytical'){
  echo "<option value='Analytical'>Analytical overall</option>
        <option value='A-001'>Lawave / microscope / profiler / contact angle (A-001)</option>
        <option value='A-002'>Tribometer / Indenter / Calotte I+II (A-002)</option>
        <option value='A-003'>Electrochemistry / Potentiometer (A-003)</option>
        <option value='A-004'>Balance I+II / Linear encoder (A-004)</option>
        <option value='A-005'>University analysis (SEM, AFM, XPS, Nanoindenter, etc.) (A-005)</option>
        <option value='A-006'>OES / RGA (A-006)</option>
        <option value='A-007'>UV/Vis (A-007)</option>";
} else if($department_name == 'Others'){
  echo "<option value='Others'>Overhead</option>
        <option value='O-001'>Office Supplies (O-001)</option>
        <option value='O-002'>Laboratory Supplies (O-002)</option>
        <option value='O-003'>Computer Network (O-003)</option>
        <option value='O-004'>Cleanroom Charges, Equipment & Supplies (O-004)</option>
        <option value='O-005'>Travel (O-005)</option>
        <option value='O-006'>Parking (permits, fees) (O-006)</option>
        <option value='O-007'>Shipping (O-007)</option>
        <option value='O-008'>Telephone Charges (O-008)</option>
        <option value='O-009'>Marketing (O-009)</option>
        <option value='O-010'>ISO9001 (O-010)</option>
        <option value='O-011'>Apartments/BW&L (students) (O-011)</option>
        <option value='O-012'>Literature (O-012)</option>
        <option value='O-013'>UoM Clean room fees (O-013)</option>";
} else{
  echo"<option value=''>N/A</option>";
}
echo "</select>";
?>

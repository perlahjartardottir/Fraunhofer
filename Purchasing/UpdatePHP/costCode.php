<?php
include '../../connection.php';
$department_name = mysqli_real_escape_string($link, $_POST['department_name']);
echo"<label>Cost code: </label>
     <select id='cost_code' class='form-control'>";
if($department_name == 'PVD'){
  echo "<option value='PVD'>PVD overall</option>
        <option value='P-001'>PVD - K1</option>
        <option value='P-002'>PVD - K2</option>
        <option value='P-003'>PVD - LaserArc</option>
        <option value='P-004'>PVD75</option>";
} else if($department_name == 'CVD'){
  echo "<option value='CVD'>CVD overall</option>
        <option value='C-001'>CVD - 915</option>
        <option value='C-002'>CVD - DS I</option>
        <option value='C-003'>CVD - DS II</option>
        <option value='C-004'>CVD - DS III</option>
        <option value='C-005'>CVD - DS IV</option>
        <option value='C-006'>CVD - DS V</option>
        <option value='C-007'>CVD - DS VI</option>
        <option value='C-008'>CVD - HF I</option>
        <option value='C-009'>CVD - Etcher</option>";
} else if($department_name == 'Infrastructure'){
  echo "<option value='INF'>Infrastructure overall</option>
        <option value='I-001'>Miele Washer / Dryer / US stations</option>
        <option value='I-002'>Tools, work bench, lathe, band saw</option>
        <option value='I-003'>Sandblaster</option>
        <option value='I-004'>Coborn Lapping Station</option>
        <option value='I-005'>Logitech LP50 Incl. GI20</option>
        <option value='I-006'>Cutting Laser</option>";
} else if($department_name == 'Analytical'){
  echo "<option value='ANA'>Analytical overall</option>
        <option value='A-001'>Lawave / microscope / profiler / contact angle</option>
        <option value='A-002'>Tribometer / Indenter / Calotte I+II</option>
        <option value='A-003'>Electrochemistry / Potentiometer</option>
        <option value='A-004'>Balance I+II / Linear encoder</option>
        <option value='A-005'>University analysis (SEM, AFM, XPS, Nanoindenter, etc.)</option>
        <option value='A-006'>OES / RGA</option>
        <option value='A-007'>UV/Vis</option>";
} else if($department_name == 'Others'){
  echo "<option value='OH'>Overhead</option>
        <option value='O-001'>Office Supplies</option>
        <option value='O-002'>Laboratory Supplies</option>
        <option value='O-003'>Computer Network</option>
        <option value='O-004'>Cleanroom Charges, Equipment & Supplies</option>
        <option value='O-005'>Travel</option>
        <option value='O-006'>Parking (permits, fees)</option>
        <option value='O-007'>Shipping</option>
        <option value='O-008'>Telephone Charges</option>
        <option value='O-009'>Marketing</option>
        <option value='O-010'>ISO9001</option>
        <option value='O-011'>Apartments/BW&L (students)</option>
        <option value='O-012'>Literature</option>
        <option value='O-013'>UoM Clean room fees</option>";
} else{
  echo"<option value=''>N/A</option>";
}
echo "</select>";
?>

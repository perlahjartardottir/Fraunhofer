<!doctype html>
<?php
include '../connection.php';
session_start();
//find the current user
$user = $_SESSION["username"];
//find his level of security 
$secsql = "SELECT security_level
FROM employee
WHERE employee_name = '$user'";
$secResult = mysqli_query($link, $secsql);

while($row = mysqli_fetch_array($secResult)){
  $user_sec_lvl = $row[0];
}
if($user_sec_lvl < 4){
  echo "<a href='../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}
?>
<html>
<head>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
  <link href='../css/main.css' rel='stylesheet'>
  
  <script type='text/javascript' src='https://www.google.com/jsapi'></script>
  
</head>
<body>
  <?php include '../header.php'; ?>
  <div style='width:50%;margin: 0 auto;'id="chart_div" style="width: 900px; height: 500px;"></div>
  <?php 
  $customer = array();
  $countCust = array();
  $countSql = "SELECT c.customer_name, count(p.customer_ID)
               FROM pos p, customer c
               WHERE p.customer_ID = c.customer_ID
               GROUP BY p.customer_ID;";
  $countResult = mysqli_query($link, $countSql);
  while($row = mysqli_fetch_array($countResult)){
    $customer[] = $row[0];
    $countCust[] = $row[1];
  }
?>
<script type='text/javascript'>

google.load('visualization', '1', {'packages': ['geochart']});
google.setOnLoadCallback(drawMarkersMap);

function drawMarkersMap() {
  var data = google.visualization.arrayToDataTable([
    ['City',  'Number of pos', 'Customer'],
    ['Santa Clara, CA', <?php echo $countCust[0];?>, { v:<?php echo $countCust[0]; ?>, f: 'Destiny Tools' } ],
    ['Jackson, MI',     <?php echo $countCust[1];?>, { v:<?php echo $countCust[1]; ?>, f: 'Contour Tools' } ],
    ['Wixom, MI',       <?php echo $countCust[2];?>, { v:<?php echo $countCust[2]; ?>, f: 'Sterling Edge' } ],
    ['Cass City, MI',   <?php echo $countCust[3];?>, { v:<?php echo $countCust[3]; ?>, f: 'Wave Tool LLC' } ],
    ['Lenexa, KS',      <?php echo $countCust[4];?>, { v:<?php echo $countCust[4]; ?>, f: 'Kocher & Beck' } ],
    ['Waterford, MI',   <?php echo $countCust[5];?>, { v:<?php echo $countCust[5]; ?>, f: 'H&M Tool Express' } ],
    ]);

  var options = {
    region: 'US',
    displayMode: 'markers',
    enableRegionInteractivity: true,
    resolution: 'provinces',
    colorAxis: {colors: ['green', 'blue']}
  };

  var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
  chart.draw(data, options);
};
</script>
</body>
</html>
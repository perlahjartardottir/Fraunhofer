<!DOCTYPE html>
<html>
<head>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
  <!-- <link href='../css/main.css' rel='stylesheet'> -->
  <link href='../css/print.css' rel='stylesheet'>
  <script type="text/javascript" src='../js/searchScript.js'></script>
</head>
<body>
<h4>Track sheet</h4>
<?php
/*
	This file makes a track sheet from the PO the user was viewing.
*/
session_start();
include '../connection.php';
$q = $_SESSION["po_ID"];

// all the basic info for the header of the printout. TOTAL_WEEKDAYS is a function that calculates the turn around time without weekends
$topsql ="SELECT p.po_number, p.receiving_date, c.customer_name, p.shipping_date, TOTAL_WEEKDAYS(shipping_date, receiving_date), e.employee_name, p.initial_inspection
          FROM customer c, pos p, employee e, employee_pos w
          WHERE c.customer_ID = p.customer_ID
          AND p.po_ID = '$q'
          AND w.po_ID = p.po_ID
          AND e.employee_ID = w.employee_ID;";
$topresult = mysqli_query($link, $topsql);

// the overall price of the PO
$sumSql ="SELECT SUM(ROUND(l.price * l.quantity, 2)) 
          FROM lineitem l
          WHERE l.po_ID = '$q'";
$sumResult = mysqli_query($link, $sumSql);

// the number of tools and number of lineitems
// we can use MAX here to just pick the highest number.
$countSql = "SELECT SUM(quantity), MAX(line_on_po)
             FROM lineitem l
             WHERE l.po_ID = '$q';";
$countresult = mysqli_query($link, $countSql);
while($row = mysqli_fetch_array($sumResult)){
    $overall_price = $row[0];
}
while($row = mysqli_fetch_array($topresult)) {
    $POID = $row[0];
    echo "<div class='col-xs-12'>".
           "<span class='col-xs-4'><strong>PO number : </strong>"     .$row[0]."</span>".
           "<span class='col-xs-4'><strong>Customer : </strong>"      .$row[2]."</span>".
           "<span class='col-xs-4'><strong>Receiving Date : </strong>".$row[1]."</span></div>".
         "<div class='col-xs-12'>".
           "<span class='col-xs-4'><strong>Shipping Date : </strong>"   .$row[3]."</span>".
           "<span class='col-xs-4'><strong>Turn around time : </strong>".$row[4]." Days</span>".
           "<span class='col-xs-4'><strong>Employee: </strong>"         .$row[5]."</span></div>".
         "<div class='col-xs-12'>".
           "<span class='col-xs-4'><strong>Overall price : </strong>$".$overall_price."</span>";
}

while($row = mysqli_fetch_array($countresult)){

    echo "<span class='col-xs-4'><strong>Number of tools : </strong>"     .$row[0]."</span>".
         "<span class='col-xs-4'><strong>Number of line items : </strong>".$row[1]."</span></div>";
}
$newResult = mysqli_query($link, $topsql);
while($row = mysqli_fetch_array($newResult)){

    echo "<div class='col-xs-12'><div class='col-xs-4'><strong>Initial inspection :  </strong>" .$row[6]."</div>";
}
// All the info for the lineitems on this PO
// ordered by what line on the PO they are
$sql = "SELECT l.line_on_po, l.tool_ID, l.diameter, l.length, IF(l.double_end = 0, 'NO', 'YES'), l.quantity, posr.run_number_on_po, lr.number_of_items_in_run, lr.lineitem_run_comment
        FROM lineitem l, lineitem_run lr, pos_run posr, run r
        WHERE l.po_ID = '$q'
        AND posr.po_ID = l.po_ID
        AND l.lineitem_ID = lr.lineitem_ID
        AND lr.run_ID = r.run_ID
        AND posr.run_ID = r.run_ID
        ORDER BY l.line_on_po;";

$result = mysqli_query($link, $sql);

if(!$result){
     echo("Input data is fail".mysqli_error($link));
}

// All the info about the runs linked to this PO
$runsql ="SELECT c.coating_type, posr.run_number_on_po, r.ah_pulses, r.run_number, r.run_comment
          FROM run r, coating c, lineitem_run lr, lineitem l, pos_run posr
          WHERE l.po_ID = '$q'
          AND lr.lineitem_ID = l.lineitem_ID
          AND lr.run_ID = r.run_ID
          AND posr.po_ID = l.po_ID
          AND posr.run_ID = r.run_ID
          AND r.coating_ID = c.coating_ID
          GROUP BY r.run_ID
          ORDER BY posr.run_number_on_po;";

$runresult = mysqli_query($link, $runsql);

if(!$runresult){
     echo("Input data is fail".mysqli_error($link));
}
   echo "<table>";
   echo "<tr>".
          "<td>Line#</td>".
          "<td># of items on PO</td>".
          "<td>ToolID</td>".
          "<td>Dia</td>".
          "<td>Len</td>".
          "<td>DblEnd</td>".  
          "<td>Run#</td>".
          "<td># of items in run</td>".
          "<td>Final inspection</td>".
        "</tr>";

while($row = mysqli_fetch_array($result)) {
  // display letters not integers for run number
    if($row[6] == 1){ $row[6] = a;}
    if($row[6] == 2){ $row[6] = b;}
    if($row[6] == 3){ $row[6] = c;}
    if($row[6] == 4){ $row[6] = d;}
    if($row[6] == 5){ $row[6] = e;}
    if($row[6] == 6){ $row[6] = f;}
    if($row[6] == 7){ $row[6] = g;}
   echo "<tr>".
          "<td>".$row[0]."</td>".
          "<td>".$row[5]."</td>".
          "<td>".$row[1]."</td>".
          "<td>".$row[2]."</td>".
          "<td>".$row[3]."</td>".
          "<td>".$row[4]."</td>".
          "<td>".$row[6]."</td>".
          "<td>".$row[7]."</td>".
          "<td>".$row[8]."</td>".
        "</tr>";
}
echo "</table></div><div style='margin-top: 10px;'>RUN INFO<table>";
echo "<tr>".
       "<td>"."Coating Type"."</td>".
       "<td>"."Run#"        ."</td>".
       "<td>"."Ah/pulses"   ."</td>".
       "<td>"."run ID"      ."</td>".
       "<td>"."Comments"    ."</td>".
     "</tr>";

while($row = mysqli_fetch_array($runresult)){
    if($row[1] == 1){ $row[1] = a;}
    if($row[1] == 2){ $row[1] = b;}
    if($row[1] == 3){ $row[1] = c;}
    if($row[1] == 4){ $row[1] = d;}
    if($row[1] == 5){ $row[1] = e;}
    if($row[1] == 6){ $row[1] = f;}
    if($row[1] == 7){ $row[1] = g;}
    echo "<tr>".
           "<td>".$row[0]."</td>".
           "<td>".$row[1]."</td>".
           "<td>".$row[2]."</td>".
           "<td>".$row[3]."</td>".
           "<td>".$row[4]."</td>".
         "</tr>";
}
mysqli_close($link);
?>
</body>
</html>


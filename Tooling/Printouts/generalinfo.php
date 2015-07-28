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
	<h4>General information sheet</h4>
<?php
/*
        This page generates all the info needed
        for the "general information sheet" after you picked your PO number
        The user picks the ponumber but we are using the ID here  so we can access other tables via foreign keys

*/
session_start();
include '../connection.php';
// the po_ID the user picked from the dropdown list
$q = $_SESSION["po_ID"];
// finds the right info from that po_ID
$sql = "SELECT p.po_number, p.receiving_date, c.customer_name,  p.shipping_date, p.shipping_info, p.initial_inspection 
        FROM pos p, customer c
        WHERE p.customer_ID = c.customer_ID
        AND po_ID = '$q'";
$result = mysqli_query($link, $sql);

// finds all the line items for that PO
$tsql = "SELECT l.line_on_po, l.quantity, l.tool_ID, IF(l.coating_ID IS NULL, 'empty', c.coating_type), l.diameter, l.length, IF(l.double_end = 0, 'NO', 'YES') ,ROUND(l.price, 2), SUM(ROUND(l.price * l.quantity, 2)), ROUND(l.est_run_number, 2)
         FROM lineitem l 
         LEFT JOIN coating c
           ON l.coating_ID = c.coating_ID
         WHERE l.po_ID = '$q'
         GROUP BY l.line_on_po;";
$tresult = mysqli_query($link, $tsql);

// the sum of all the tools from all the line items on that PO
$sumSql = "SELECT SUM(quantity)
           FROM lineitem
           WHERE po_ID = '$q'";
$sumresult = mysqli_query($link, $sumSql);


while($row = mysqli_fetch_array($result)) {
    $POID = $row[0];
    echo "<div class='col-xs-12'>";
    echo "<span class='col-xs-4'>".'PO number : '         .$row[0]."</span>";
    echo "<span class='col-xs-4'>".'Receiving Date : '    .$row[1]."</span>";
    echo "<span class='col-xs-4'>".'Customer : '          .$row[2]."</span>";
    // echo "<span class='col-xs-6'>". 'Shipping Date : '. $row[3]."</span>";
    echo "<span class='col-xs-4'>".'Shipping Info : '     .$row[4]."</span>";
    echo "<span class='col-xs-4'>".'Initial inspection : '.$row[5]."</span>";
    echo "</div>";
}

echo "<table>";
echo    "<tr>
            <td>Line#</td>
            <td>Quantity</td>  
            <td>ToolID</td>
            <td>Coating</td>
            <td>dia / IC</td>
            <td>length</td>
            <td>est run#</td>
            <td>double end</td>
            <td>unit price</td>
            <td>total unit price</td>
        </tr>";
while($row = mysqli_fetch_array($tresult)) {
    echo "<tr>".
            "<td>" .$row[0]."</td>".
            "<td>" .$row[1]."</td>".
            "<td>" .$row[2]."</td>".
            "<td>" .$row[3]."</td>".
            "<td>" .$row[4]."</td>".
            "<td>" .$row[5]."</td>".
            "<td>" .$row[9]."</td>".
            "<td>" .$row[6]."</td>".
            "<td>$".$row[7]."</td>".
            "<td>$".$row[8]."</td>".
          "</tr>";
}
// Finds the price of all the tools on that po
$totalPricesql = "SELECT SUM(ROUND(l.price * l.quantity, 2)), SUM(ROUND(l.est_run_number, 2)) 
                  FROM lineitem l, pos p
                  WHERE p.po_ID = '$q'
                  AND l.po_ID = p.po_ID";

$totalPriceResult = mysqli_query($link, $totalPricesql);

$discountSql = "SELECT d.lineitem_ID, l.line_on_po, d.number_of_tools, d.discount, d.discount_reason
                FROM discount d, lineitem l
                WHERE l.po_ID = '$q'
                AND d.lineitem_ID = l.lineitem_ID;";
$discountSqlResult = mysqli_query($link, $discountSql);

$totalDiscountSql = "SELECT SUM(ROUND(d.discount * d.number_of_tools, 2)), SUM(d.number_of_tools)
                FROM discount d, lineitem l
                WHERE l.po_ID = '$q'
                AND d.lineitem_ID = l.lineitem_ID;";
$totalDiscountSqlResult = mysqli_query($link, $totalDiscountSql);

while($row = mysqli_fetch_array($sumresult)){
    echo "<tr class='bottomrow'>".
            "<td>Total: </td>".
            "<td>".$row[0]."</td>".
            "<td></td>".
            "<td></td>".
            "<td></td>";
}
while($frow = mysqli_fetch_array($totalPriceResult)){
    $totalSum = $frow[0];
    echo "<td></td>".
         "<td>".$frow[1]."</td>".
         "<td></td>".
         "<td></td>".
         "<td>$".$frow[0]."</td>".
    "</tr>";
}

echo "</table>";

if(mysqli_num_rows($discountSqlResult) > 0){
  echo "<h4>Discounts</h4>".
       "<table>".
          "<tr>".
            "<td>Line on PO</td>".
            "<td>Quantity</td>".
            "<td>Discount</td>".
            "<td>Reason</td>".
          "</tr>";
  while($row = mysqli_fetch_array($discountSqlResult)){
    echo "<tr>".
          "<td>".$row[1]."</td>".
          "<td>".$row[2]."</td>".
          "<td>".$row[3]."</td>".
          "<td>".$row[4]."</td>".
        "</tr>";
  }
  while($row = mysqli_fetch_array($totalDiscountSqlResult)){
    $totalDiscount = $row[0];
    echo "<tr class='bottomrow'>".
            "<td>Total: </td>".
            "<td>".$row[1]."</td>".
            "<td>".$row[0]."</td>".
            "<td></td>".
          "</tr>".
        "</table>";
  }


$finalPrice = $totalSum - $totalDiscount;
echo "<h4>Final price: ".$finalPrice."</h4>";
}
mysqli_close($link);
?>
</body>
</html>
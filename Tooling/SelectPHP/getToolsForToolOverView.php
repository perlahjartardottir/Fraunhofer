<?php

include '../connection.php';
session_start();
//POID from the dropdown list
$po_ID = $_SESSION["po_ID"];

// first we have to find the right po_ID from the po_Number we get from the user
$po_IDsql = "SELECT l.po_ID
             FROM lineitem l, pos p
             WHERE p.po_ID = '$po_ID'
             AND l.po_ID = p.po_ID;";
$po_IDresult = mysqli_query($link, $po_IDsql);

while($row = mysqli_fetch_array($po_IDresult)){
    $po_ID = $row[0];
}
//sql for table data
$sql = "SELECT l.line_on_po, l.quantity, l.tool_ID, IF(l.diameter = '' or l.diameter = 0, 'N/A', l.diameter), IF(l.length = '' or l.length = 0, 'N/A', l.length), IF(l.double_end = 1, 'Yes', 'No'), ROUND(l.price, 2), ROUND(l.price * l.quantity, 2)
        FROM lineitem l, POS p
        WHERE l.po_ID = '$po_ID'
        GROUP BY l.lineitem_ID
        ORDER BY l.line_on_po";
$result = mysqli_query($link, $sql);

//sql for bottom row
$sumSql = "SELECT COUNT('lineitem_ID'), SUM(quantity)
           FROM lineitem l
           WHERE l.po_ID = '$po_ID'";
$sumresult = mysqli_query($link, $sumSql);
//if sum table is wrong
if (!$sumresult) {
    $message  = 'Invalid sum query: ' . mysql_error() . "\n";
    $message .= 'Whole sum query: ' . $query;
    die($message);
}
//if table query is wrong
if (!$result) {
    $message  = 'Invalid query result query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
}
//building the header of the table.
   echo         "<tr>".
                "<td>Line#</td>".
                "<td>Quantity</td>".
                "<td>ToolID</td>".
                "<td>Dia / IC</td>".
                "<td>Length</td>".
                "<td>DblEnd</td>".
                "<td>Price</td>".
                "<td>total unit price</td>".
                "</tr>";

//filling it with data from POTools
while($row = mysqli_fetch_array($result)) {
   echo
        "<tr>".
        "<td>".$row[0]."</td>".
        "<td>".$row[1]."</td>".
        "<td>".$row[2]."</td>".
        "<td>".$row[3]."</td>".
        "<td>".$row[4]."</td>".
        "<td>".$row[5]."</td>".
        "<td>".$row[6]."</td>".
        "<td>".$row[7]."<button id='delRunToolButton' style='float:right; margin-right:-50px'class='btn btn-danger' onclick='delTool(".$row[0].")'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></td>".
        "</tr>";
}
$totalSumSql = "SELECT SUM(ROUND(l.price * l.quantity, 2))
                FROM lineitem l
                WHERE l.po_ID = '$po_ID'";
$totalSumResult = mysqli_query($link, $totalSumSql);
if (!$totalSumResult) {
    $message  = 'Invalid query result query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
}

while($row = mysqli_fetch_array($sumresult)){
    echo "<tr>".
         "<td>".'Number of Items: '.$row[0]."</td>".
         "<td>".'Number of tools: '.$row[1]."</td>".
         "<td></td>".
         "<td></td>".
         "<td></td>".
         "<td></td>".
         "<td>Total $: </td>";
}
while($row = mysqli_fetch_array($totalSumResult)){
    echo "<td>".$row[0]."</td>".
         "</tr>";
}
 mysqli_close($link);
?>

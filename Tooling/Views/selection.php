<!-- In this view we only display some parts if the security level is high enough -->
<!-- This is the front page -->
<!DOCTYPE html>
<html>
<head>
  <?php
  include '../connection.php';
  session_start();
  // find the current user
  $user = $_SESSION["username"];

  // find his level of security
  $secsql = "SELECT security_level
             FROM employee
             WHERE employee_name = '$user'";
  $secResult = mysqli_query($link, $secsql);

  while($row = mysqli_fetch_array($secResult)){
    $user_sec_lvl = $row[0];
  }
  ?>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
<?php include '../header.php'; ?>
  <div class='container'>
    <h1>Fraunhofer CCD</h1>
    <div class='row well well-lg'>
      <div class='col-md-12'>
        <!-- Every security level can see the tooling overview button -->
        <div class='col-md-3'>
            <button type='button' class='btn btn-primary col-md-8' onclick="location.href='../Views/overview.php'">Search</button>
        </div>
        <?php
        if($user_sec_lvl >= 2){
          echo
          "<div class='col-md-3 btn-group'>
              <button type='button' class='btn btn-primary' onclick="."location.href"."='../Views/checkin.php'>Tool processing</button>
              <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
                <span class='caret'></span>
                <span class='sr-only'>Toggle Dropdown</span>
              </button>
              <ul class='dropdown-menu' role='menu'>
                <li><a href='../Views/addNewPO.php'>Add a new PO</a></li>
                <li><a href='../Views/addTools2.php'>Add tools to PO</a></li>
                <li><a href='../Views/generateTrackSheet.php'>Generate a track sheet</a></li>
                <li><a href='../printouts/packinglist.php'>Generate a packing list</a></li>
              </ul>
          </div>";
        }
        ?>
        <?php
          if($user_sec_lvl >= 1){
            echo
            "<div class='col-md-3 btn-group'>
            <button type='button' class='btn btn-primary' onclick="."location.href"."='../Views/addOrEdit.php'>General information</button>
            <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
              <span class='caret'></span>
              <span class='sr-only'>Toggle Dropdown</span>
            </button>
            <ul class='dropdown-menu' role='menu'>
              <li><a href='../Views/viewAllCustomers.php'>Customer info</a></li>
              <li><a href='../Views/viewAllEmployees.php'>Employee info</a></li>
              <li><a href='../Views/viewAllMachines.php'>Machine info</a></li>
              <li><a href='../Views/viewAllCoatings.php'>Coating info</a></li>
            </ul>
            </div>";
          }
        ?>
        <?php
          if($user_sec_lvl >= 4){
            echo
            "<div class='col-md-3'>
            <button type='button' class='btn btn-primary' onclick="."location.href"."='../Report/reportOverview.php'>Tool coating overview</button>
            </div>";
          }
        ?>
      </div>
    </div>
    <div class="table-responsive col-md-10 col-md-offset-1">
      <?php
      /*
          query that shows a list of POs, and some info about them, that have not been shipped yet
          if clicked will display a list of the line items on that PO
      */
      $sql = "SELECT p.po_number, c.customer_name, p.receiving_date, p.initial_inspection, ROUND(SUM(l.est_run_number), 2), p.po_ID, p.final_price
              FROM pos p, customer c, lineitem l
              WHERE p.customer_ID = c.customer_ID
              AND (p.shipping_date > DATE(NOW()) OR p.shipping_date IS null)
              AND l.po_ID = p.po_ID
              GROUP BY p.po_ID
              ORDER BY p.receiving_date";
      $result = mysqli_query($link, $sql);
      //Total price of all the POs
      $totalPrice = 0;
      $totalEstRun = 0;
      while($row = mysqli_fetch_array($result)){
        // find the overall price of the PO
        $sumSql = "SELECT round(sum(l.price * l.quantity),2), ROUND(SUM(l.est_run_number), 2)
                   FROM lineitem l
                   WHERE l.po_ID = '$row[5]';";
        $sumResult = mysqli_query($link, $sumSql);
        $total = mysqli_fetch_array($sumResult);
        $totalPrice += $total[0];
        $totalEstRun += $total[1];
      }
      ?>
      <h2>POs that have not been shipped<span style='float:right; font-size:60%; margin-top:8px; margin-right:80px;'>Total price: $<?php echo $totalPrice; ?></span><span style='float:right; font-size:60%; margin-top:8px; margin-right:17px;'>Total est run: <?php echo $totalEstRun; ?></span></h2>

      <table class="table">
        <thead>
          <tr>
            <th class='col-md-1'>PO number</th>
            <th class='col-md-2'>Company name</th>
            <th class='col-md-2'>Receiving date</th>
            <th class='col-md-2'>Initial inspection</th>
            <th class='col-md-2'>Est run #</th>
            <th class='col-md-2'>Final price</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $result = mysqli_query($link, $sql);

          if (!$result) {
            die("Database query failed: " . mysql_error());
          }
          while ($row = mysqli_fetch_array($result)) {
            // find the overall price of the PO
            $sumSql = "SELECT round(sum(l.price * l.quantity),2)
            		       FROM lineitem l
            		       WHERE l.po_ID = '$row[5]';";
            $sumResult = mysqli_query($link, $sumSql);
            $finalPrice = mysqli_fetch_array($sumResult);
            $rightRow = $row[0];
            echo "<tr>".
                    "<td class='col-md-2'><a href='#' data-toggle='modal' onclick='setSessionIDSearch(".$row[5].")' data-target='#".$row[5]."'>".$row[0]."</td>".
                    "<td class='col-md-2'>".$row[1]."</td>".
                    "<td class='col-md-2'>".$row[2]."</td>".
                    "<td class='col-md-2'>".$row[3]."</td>".
                    "<td class='col-md-2'>".$row[4]."</td>".
                    "<td class='col-md-2'>$".$finalPrice[0]."</td>".
                 "</tr>";
            echo "<div class='modal fade' id='".$row[5]."' tabindex='-1' role='dialog' aria-labelledby='".$row[5]."' aria-hidden='true'>
                    <div class='modal-dialog'>
                      <div class='modal-content'>
                        <div class='modal-header'>
                          <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                          <h4 class='modal-title' id='myModalLabel'>PO number: ".$row[0]."</h4>
                        </div>
                        <div class='modal-body'>
                          <h3>PO information<h3>
                          <div class='btn-group'>
                              <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                Printout <span class='caret'></span>
                              </button>
                              <ul class='dropdown-menu' role='menu'>
                                <li><a href='../Printouts/tracksheet.php'>Track sheet</a></li>
                                <li><a href='../Printouts/generalinfo.php'>General info</a></li>
                                <li><a href='../Printouts/packingList.php'>Packing list</a></li>
                                <li><a href='../Printouts/scanprintout.php'>View PO scan</a></li>
                              </ul>
                              <p></p>
                          </div>
                          <div class='btn-group'>
                              <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                Edit <span class='caret'></span>
                              </button>
                              <ul class='dropdown-menu' role='menu'>
                                <li><a href='../Views/editPO.php'>Edit PO</a></li>
                                <li><a href='../Views/generateTrackSheet.php'>Edit PO track sheet</a></li>
                                <li><a href='../Views/addTools2.php'>Add lineitems</a></li>
                              </ul>
                          </div>
                          <button class='btn btn-danger' onclick='delPO(".$row[0].")'>Delete PO</button>
                        </div>
                        <div class='modal-footer'>
                          <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        </div>
                      </div>
                    </div>
                   </div>";
          }
       ?>
     </tbody>
   </table>
   </div>
  </div>
</body>
</html>

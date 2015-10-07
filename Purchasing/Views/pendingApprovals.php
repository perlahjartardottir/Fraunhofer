<?php
include '../../connection.php';
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
// if the user security level is not high enough we kill the page and give him a link to the log in page
if($user_sec_lvl < 4){
  echo "<!DOCTYPE html>
  <html lang='en'>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content=''>
    <title>Login Fraunhofer CCD</title>
    <link href='../css/bootstrap.min.css' rel='stylesheet'>
    <!-- Custom styles for this template -->
    <link href='../../css/signin.css' rel='stylesheet'>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='../../Tooling/dest/fraunhofer.min.js'></script>
  </head>
  <body>
  <div class='container'>
      <div class='form-signin'>
        <label for='userID' class='sr-only'>Employee ID</label>
        <input type='number' id='userID' class='form-control' placeholder='Employee ID' required autofocus>
        <label for='password' class='sr-only'>Password</label>
        <input type='password' id='password' class='form-control' placeholder='Password' required>
        <button class='btn btn-lg btn-primary btn-block' id='loginbtn' type='submit' onclick='authenticateAppending()'>Sign in</button>
      </div>";
      $sql = "SELECT employee_name, employee_ID
              FROM employee";
      $result = mysqli_query($link, $sql);
      while($row = mysqli_fetch_array($result)){
        echo "<span>".$row[0]." ID: ".$row[1]."</span></br>";
      }
      echo"
    </div> <!-- /container -->
  </body>
  <script type='text/javascript'>
  $(document).ready(function(){
    $('#password').keypress(function(e){
      if(e.keyCode==13)
        $('#loginbtn').click();
    });
  });
  </script>
  </html>";
  die("");
}
$sql = "SELECT order_ID, order_for_who, order_name
        FROM purchase_order
        WHERE approval_status = 'pending';";
$result = mysqli_query($link, $sql);
 ?>
 <head>
   <title>Fraunhofer CCD</title>
 </head>
 <body>
   <?php include '../header.php'; ?>
   <div class='container'>
     <div class='col-md-12'>
       <table class='table table-responsive'>
         <thead>
           <tr>
             <th>Purchase number</th>
             <th>Requested by</th>
           </tr>
         </thead>
         <tbody>
           <?php
           while($row = mysqli_fetch_array($result)){
             // Find who requested the order
             $orderForWhoSql = "SELECT employee_name
                                FROM employee
                                WHERE employee_ID = '$row[1]';";
             $orderForWhoResult = mysqli_query($link, $orderForWhoSql);
             $orderForWho = mysqli_fetch_array($orderForWhoResult);
             echo"<tr>
                   <td><a href='#' onclick='setSessionIDSearch(".$row[0].")' data-toggle='modal' data-target='#".$row[0]."'>".$row[2]."</a></td>
                   <td>".$orderForWho[0]."</td>
                  </tr>";
           }
           ?>
         </tbody>
       </table>
       <?php
       $result = mysqli_query($link, $sql);
       while($row = mysqli_fetch_array($result)){
         $orderItemSql = "SELECT quantity, part_number, description, unit_price
                          FROM order_item
                          WHERE order_ID = '$row[0]';";
         $orderItemResult = mysqli_query($link, $orderItemSql);
         echo"
         <div class='modal fade' id='".$row[0]."' tabindex='-1' role='dialog' aria-labelledby='".$row[0]."' aria-hidden='true'>
           <div class='modal-dialog'>
             <div class='modal-content'>
               <div class='modal-header'>
                 <h4>Purchase order: ".$row[2]."</h4>
               </div>
               <div class='modal-body'>
                <table class='table table-responsive'>
                  <thead>
                    <tr>
                      <th>Pos. #</th>
                      <th>Quantity</th>
                      <th>Part #</th>
                      <th>Description</th>
                      <th>USD Unit</th>
                      <th>USD Total</th>
                    </tr>
                  </thead>
                  <tbody>";
                  $counter = 1;
                  $totalOrderPrice = 0;
                  while($orderItemRow = mysqli_fetch_array($orderItemResult)){
                    $total = $orderItemRow[0] * $orderItemRow[3];
                    $totalOrderPrice = $totalOrderPrice + $total;
                    echo"
                      <tr>
                        <td>".$counter."</td>
                        <td>".$orderItemRow[0]."</td>
                        <td>".$orderItemRow[1]."</td>
                        <td>".$orderItemRow[2]."</td>
                        <td>$".number_format((float)$orderItemRow[3], 2, '.', '')."</td>
                        <td>$".number_format((float)$total, 2, '.', '')."</td>
                      </tr>";
                      $counter = $counter + 1;
                  }
              echo"
                 </tbody>
                </table>
                <form>
                  <label>Reply: </label><br>
                  <textarea id='approval_response' class='form-control' rows='4'></textarea>
                </form>
                 </div>
                 <div class='modal-footer'>
                  <button class='btn btn-danger' onclick='declineApprovalRequest(".$row[0].", this)'>Decline</button>
                  <button type='button' style='float:right;' class='btn' data-dismiss='modal'>Close</button>
                  <button style='float:right;' class='btn btn-success' onclick='approveApprovalRequest(".$row[0].", this)'>Approve</button>
                 </div>
                </div>
              </div>
            </div>";
        }
        ?>
     </div>
   </div>
 </body>

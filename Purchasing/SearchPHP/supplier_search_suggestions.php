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
$user_sec_lvl = str_split($user_sec_lvl);
$user_sec_lvl = $user_sec_lvl[1];

$supplier_name = mysqli_real_escape_string($link, $_POST['supplier_name']);
$supplier_name .= "%";
$supplier_contact = mysqli_real_escape_string($link, $_POST['supplier_contact']);
$supplier_contact .= "%";
$supplier_phone = mysqli_real_escape_string($link, $_POST['supplier_phone']);
$supplier_phone .= "%";
$supplier_email = mysqli_real_escape_string($link, $_POST['supplier_email']);
$supplier_email .= "%";
$supplier_address = mysqli_real_escape_string($link, $_POST['supplier_address']);
$supplier_address .= "%";
?>
<script>
// script to activate popovers
   $(document).ready(function () {
     // close open popovers when you open new one.
     $('.btn').popover();

     $('.btn').on('click', function (e) {
         $('.btn').not(this).popover('hide');
     });
   });
</script>
<div id='output'>
  <table class='table table-responsive'>
    <thead>
      <tr>
        <th>Supplier name</th>
        <th>Contact</th>
        <th class='col-md-2'>Phone</th>
        <th class='col-md-3'>Email</th>
        <th>Address</th>
        <th>Rating</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT s.supplier_ID, s.supplier_name, s.supplier_phone, s.supplier_email, s.supplier_address, s.supplier_fax, s.supplier_contact, s.supplier_website, s.supplier_login, s.supplier_password, s.supplier_accountNr, s.supplier_notes, s.net_terms
              FROM supplier s
              WHERE s.supplier_name LIKE '$supplier_name'
              AND s.supplier_contact LIKE '$supplier_contact'
              AND s.supplier_phone LIKE '$supplier_phone'
              AND s.supplier_email LIKE '$supplier_email'
              AND s.supplier_address LIKE '$supplier_address'
              ORDER BY (SELECT AVG(rating_timeliness) + AVG(rating_price) + AVG(rating_quality) / 3
                        FROM order_rating r, purchase_order o
                        WHERE o.order_ID = r.order_ID
                        AND o.supplier_ID = s.supplier_ID) DESC, s.supplier_name;";
      $result = mysqli_query($link, $sql);
      $category = "";
      while($row = mysqli_fetch_array($result)){

        // SQL to get each customers rating
        // Since we use the rating system from 1 to 5 diamonds we have to do a little math
        // To get the correct values, because timeliness is rated from 1 to 2 (not on time, on time)
        // and quality and price are ranked from 1 to 3
        $ratingSql = "SELECT ROUND((ROUND((AVG(rating_timeliness) + AVG(rating_price) + AVG(rating_quality) + AVG(customer_service)) / 4, 2) / 2) * 5, 2), ROUND((AVG(rating_timeliness) / 2) * 5, 2), ROUND(AVG((rating_price) / 2) * 5, 2), ROUND(AVG((rating_quality) / 2) * 5, 2), ROUND(AVG(TOTAL_WEEKDAYS(order_date, order_receive_date) - 1), 2),  SUM(CASE WHEN order_receive_date IS NULL THEN 1 ELSE 0 END), COUNT(o.order_ID), ROUND((AVG(customer_service) / 2) * 5, 2)
                      FROM purchase_order o, order_rating r
                      WHERE o.order_ID = r.order_ID
                      AND o.supplier_ID = '$row[0]';";
        $ratingResult  = mysqli_query($link, $ratingSql);
        if(!$ratingResult){
          echo mysqli_error($link);
        }
        $averageRating = mysqli_fetch_array($ratingResult);

        // Groupping together suppliers who have similar rating
        if($category != 'Ideal' && $averageRating[0] >= 4){
          $category = 'Ideal';
          echo"
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th style='font-size: 180%;'>".$category."</th>
              <th></th>
              <th></th>
            </tr>";
        }
        if($category != 'Acceptable' && $averageRating[0] >= 3 && $averageRating[0] < 4){
          $category = 'Acceptable';
          echo"
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th style='font-size: 180%;'>".$category."</th>
              <th></th>
              <th></th>
            </tr>";
        }
        if($category != 'Not Recommended' && $averageRating[0] < 3 && $averageRating[0] != ''){
          $category = 'Not Recommended';
          echo"
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th style='font-size: 180%;'>".$category."</th>
              <th></th>
              <th></th>
            </tr>";
        }
        if($category != 'Not Yet Rated' && $averageRating[0] == ''){
          $category = 'Not Yet Rated';
          echo"
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th style='font-size: 180%;'>".$category."</th>
              <th></th>
              <th></th>
            </tr>";
        }

        echo"<tr>
              <td><a href='#' data-toggle='modal' data-target='#".$row[0]."'>".$row[1]."</td>
              <td>".$row[6]."</td>
              <td>".$row[2]."</td>
              <td><a href='mailto:someone@example.com'>".$row[3]."</a></td>
              <td><a href='http://maps.google.com/?q=".$row[4]."' target='_blank'>".$row[4]."</a></td>
              <td><button
                    style='border:none;'
                    type='button'
                    class='btn btn-default popp'
                    data-container='body'
                    data-toggle='popover'
                    data-placement='right'
                    data-html='true'
                    data-content='Avg timeliness: ".$averageRating[1]."<br/> Avg price: ".$averageRating[2]."<br/> Avg quality: ".$averageRating[3]."<br/> Avg customer service: ".$averageRating[7]."'>";
             echo $averageRating[0]." <i class='fa fa-diamond' aria-hidden='true'></i>";
             echo "</button></td></tr>";
             echo "<div class='modal fade' id='".$row[0]."' tabindex='-1' role='dialog' aria-labelledby='".$row[0]."' aria-hidden='true'>
          			   <div class='modal-dialog'>
          			      <div class='modal-content'>
          			         <div class='modal-header'>
          			            <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
          			            <h4 class='modal-title' id='myModalLabel'>".$row[1]." - Information</h4>
          			         </div>
          			         <div class='modal-body'>
                            <div class='row'>
                              <div class='col-md-6'>
                                <input type='hidden' id='supplier_ID' value='".$row[0]."'>
                                <p><strong>Phone: </strong><span id='supplier_phone'>".$row[2]."</span></p>
                                <p><strong>Fax: </strong><span id='supplier_fax'>".$row[5]."</span></p>
                                <p><strong>Email: </strong><span id='supplier_email'>".$row[3]."</span></p>
                                <p><strong>Address: </strong><span id='supplier_address'>".$row[4]."</span></p>
                                <p><strong>Contact: </strong><span id='supplier_contact'>".$row[6]."</span></p>
                                <p><strong>Account Nr: </strong><span id='supplier_accountNr'>".$row[10]."</span></p>";
                                if($row[12] != ""){echo"<p><strong>Net Terms: </strong><span id='net_terms'>".$row[12]." days</span></p>";}
                              echo"</div>
                              <div class='col-md-6'>
                                <p><strong>Website: </strong><a href='".$row[7]."' target='_blank'><span id='supplier_website'>".$row[7]."</span></a></p>
                                <p><strong>Login: </strong><span id='supplier_login'>".$row[8]."</span></p>
                                <p><strong>Password: </strong><span id='supplier_password'>".$row[9]."</span></p>
                            </div>
                            <div class='col-md-6' style='margin-top:20px'>
                              <p><strong>Average lead time: </strong><span id='averageLeadTime'>".$averageRating[4]."</span></p>
                              <p><strong>Number of active POs: </strong><span id='numberOfActivePOs'>".$averageRating[5]."</span></p>
                              <p><strong>Overall orders: </strong><span id='overallOrders'>".$averageRating[6]."</span></p>
                            </div>
                            <div class='col-md-12'><label>Notes: </label><p id='supplier_notes'>".$row[11]."</p>
                         </div>
                        </div>
          			        <div class='modal-footer'>
                          <p style='float:left'><strong>Rating</strong>: ".$averageRating[0]." <i class='fa fa-diamond' aria-hidden='true'></i></p>";
                          if($user_sec_lvl > 2){
                            echo"  <button type='button' class='btn btn-primary' data-dismiss='modal' onclick='setSupplierID(this)'>Edit Supplier</button>";
                          }
                          echo"
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

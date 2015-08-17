<?php
include '../../connection.php';
$supplier_name = mysqli_real_escape_string($link, $_POST['supplier_name']);
$supplier_name .= "%";
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
        <th class='col-md-2'>Phone</th>
        <th>Email</th>
        <th>Address</th>
        <th>Rating</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT supplier_ID, supplier_name, supplier_phone, supplier_email, supplier_address, supplier_fax, supplier_contact, supplier_website, supplier_login, supplier_password, supplier_accountNr, supplier_notes
              FROM supplier
              WHERE supplier_name LIKE '$supplier_name'
              AND supplier_phone LIKE '$supplier_phone'
              AND supplier_email LIKE '$supplier_email'
              AND supplier_address LIKE '$supplier_address';";
      $result = mysqli_query($link, $sql);
      while($row = mysqli_fetch_array($result)){
        // SQL to get each customers rating
        $ratingSql = "SELECT ROUND((AVG(rating_timeliness) + AVG(rating_price) + AVG(rating_quality)) / 3, 2), ROUND(AVG(rating_timeliness), 2), ROUND(AVG(rating_price), 2), ROUND(AVG(rating_quality), 2), ROUND(AVG(TOTAL_WEEKDAYS(order_date, order_receive_date)), 2),  SUM(CASE WHEN order_receive_date IS NULL THEN 1 ELSE 0 END), COUNT(o.order_ID)
                      FROM purchase_order o LEFT JOIN order_rating r
                      	ON o.order_ID = r.order_ID
                      WHERE o.supplier_ID = '$row[0]';";
        $ratingResult  = mysqli_query($link, $ratingSql);
        if(!$ratingResult){
          echo mysqli_error($link);
        }
        $averageRating = mysqli_fetch_array($ratingResult);
        echo"<tr>
              <td><a href='#' data-toggle='modal' data-target='#".$row[0]."'>".$row[1]."</td>
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
                    data-content='Avg timeliness: ".$averageRating[1]."<br/> Avg price: ".$averageRating[2]."<br/> Avg quality: ".$averageRating[3]."'>";
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
                                <p><strong>Account Nr: </strong><span id='supplier_accountNr'>".$row[10]."</span></p>
                              </div>
                              <div class='col-md-6'>
                                <p><strong>Website: </strong><a href='http://".$row[7]."' target='_blank'><span id='supplier_website'>".$row[7]."</span></a></p>
                                <p><strong>Login: </strong><span id='supplier_login'>".$row[8]."</span></p>
                                <p><strong>Passwrd: </strong><span id='supplier_password'>".$row[9]."</span></p>
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
                          <p style='float:left'><strong>Rating</strong>: ".$averageRating[0]." <i class='fa fa-diamond' aria-hidden='true'></i></p>
                          <button type='button' class='btn btn-primary' data-dismiss='modal' onclick='setSupplierID(this)'>Edit Supplier</button>
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

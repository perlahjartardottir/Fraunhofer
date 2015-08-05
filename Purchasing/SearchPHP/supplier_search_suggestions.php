<?php
include '../../connection.php';
$supplier_name = mysqli_real_escape_string($link, $_POST['supplier_name']);
$supplier_name .= "%";
?>
<script>
// script to activate popovers
   $(document).ready(function () {
     $(function () {
       $("[data-toggle=popover]").popover();
     })
   });
</script>
<div id='output'>
  <table class='table table-responsive'>
    <thead>
      <tr>
        <th>Supplier name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Address</th>
        <th>Rating</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT supplier_ID, supplier_name, supplier_phone, supplier_email, supplier_address, supplier_fax, supplier_contact, supplier_website
              FROM supplier
              WHERE supplier_name LIKE '$supplier_name';";
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
              <td>".$row[3]."</td>
              <td><a href='http://maps.google.com/?q=".$row[4]."' target='_blank'>".$row[4]."</a></td>
              <td><button
                    style='border:none;'
                    type='button'
                    class='btn btn-default'
                    data-container='body'
                    data-toggle='popover'
                    data-placement='right'
                    data-html='true'
                    data-content='Avg timeliness: ".$averageRating[1]."<br/> Avg price: ".$averageRating[2]."<br/> Avg quality: ".$averageRating[3]."'>";
             echo $averageRating[0]."<i class='fa fa-diamond' aria-hidden='true'></i>";
             echo "</button></td></tr>";
             echo "<div class='modal fade' id='".$row[0]."' tabindex='-1' role='dialog' aria-labelledby='".$row[0]."' aria-hidden='true'>
          			   <div class='modal-dialog modal-lg'>
          			      <div class='modal-content'>
          			         <div class='modal-header'>
          			            <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
          			            <h4 class='modal-title' id='myModalLabel'>".$row[1]."</h4>
          			         </div>
          			         <div class='modal-body'>
          			            <h3>Supplier information</h3>
                              <div>
                                <p><strong>Phone</strong> : "  .$row[2]."</p>
                                <p><strong>Fax</strong> : "    .$row[5]."</p>
                                <p><strong>Email</strong> : "  .$row[3]."</p>
                                <p><strong>Address</strong> : ".$row[4]."</p>
                                <p><strong>Contact</strong> : ".$row[6]."</p>
                                <p><strong>Website</strong> : <a href='".$row[7]."' target='_blank'>".$row[7]."</a></p>
                              </div>
                              <div>
                                <p><strong>Average lean time:</strong> ".$averageRating[4]."</p>
                                <p><strong>Number of active POS:</strong> ".$averageRating[5]."</p>
                                <p><strong>Overall orders:</strong> ".$averageRating[6]."</p>
                              </div>
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

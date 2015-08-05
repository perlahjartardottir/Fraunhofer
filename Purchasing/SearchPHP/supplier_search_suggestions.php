<?php
include '../../connection.php';
?>
<script>
// script to activate popovers
   $(document).ready(function () {
     $(function () {
       console.log("swag");
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
              FROM supplier;";
      $result = mysqli_query($link, $sql);
      while($row = mysqli_fetch_array($result)){
        // SQL to get each customers rating
        $ratingSql = "SELECT ROUND((AVG(rating_timeliness) + AVG(rating_price) + AVG(rating_quality)) / 3, 2), ROUND(AVG(rating_timeliness), 2), ROUND(AVG(rating_price), 2), ROUND(AVG(rating_quality), 2)
                      FROM order_rating r, purchase_order o
                      WHERE r.order_ID = o.order_ID
                      AND o.supplier_ID = $row[0];";
        $ratingResult  = mysqli_query($link, $ratingSql);
        if(!$ratingResult){
          echo mysqli_error($link);
        }
        $averageRating = mysqli_fetch_array($ratingResult);
        echo"<tr>
              <td><a href='#' data-toggle='modal' data-target='#".$row[0]."'>".$row[1]."</td>
              <td>".$row[2]."</td>
              <td>".$row[3]."</td>
              <td>".$row[4]."</td>
              <td><button
                    style='border:none;'
                    type='button'
                    class='btn btn-default'
                    data-container='body'
                    data-toggle='popover'
                    data-placement='right'
                    data-html='true'
                    data-content='Avg timeliness: ".$averageRating[1]."<br/> Avg price: ".$averageRating[2]."<br/> Avg quality: ".$averageRating[3]."'>";
              for ($i=0; $i < $averageRating[0] ; $i++) {
                //echo "<span class='glyphicon glyphicon-star' aria-hidden='true'></span>";
                echo "<i class='fa fa-diamond' aria-hidden='true'></i>";
              }
             echo "</button></td></tr>";
             echo "<div class='modal fade' id='".$row[0]."' tabindex='-1' role='dialog' aria-labelledby='".$row[0]."' aria-hidden='true'>
          			   <div class='modal-dialog modal-lg'>
          			      <div class='modal-content'>
          			         <div class='modal-header'>
          			            <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
          			            <h4 class='modal-title' id='myModalLabel'>".$row[1]."</h4>
          			         </div>
          			         <div class='modal-body'>
          			            <h3>Supplier information<h3>
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

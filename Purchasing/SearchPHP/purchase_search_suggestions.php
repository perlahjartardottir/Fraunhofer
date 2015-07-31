<?php include '../../connection.php'; ?>
<div id='output'>
  <table class='table table-responsive'>
    <thead>
      <tr>
        <th>Purchase number</th>
        <th>Order date</th>
        <th>Receiving date</th>
        <th>Comment</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT order_ID, order_date, order_receive_date, order_final_inspection
              FROM purchase_order;";
      $result = mysqli_query($link, $sql);
      while($row = mysqli_fetch_array($result)){
        echo"
          <tr>
            <td onclick='POInfo(".$row[0].")'><a href='#'>".$row[0]."</a></td>
            <td>".$row[1]."</td>
            <td>".$row[2]."</td>
            <td>".$row[3]."</td>
          </tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<?php
include '../../connection.php';
?>
<div id='output'>
  <table class='table table-responsive'>
    <thead>
      <tr>
        <th>Supplier name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Rating</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT supplier_ID, supplier_name, supplier_phone, supplier_email
              FROM supplier;";
      $result = mysqli_query($link, $sql);
      while($row = mysqli_fetch_array($result)){
        echo"
          <tr>
            <td>".$row[1]."</td>
            <td>".$row[2]."</td>
            <td>".$row[3]."</td>
            <td>N/A</td>
          </tr>";
      }
      ?>
    </tbody>
  </table>
</div>

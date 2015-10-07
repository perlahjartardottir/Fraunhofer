<!DOCTYPE html>
<?php
include '../connection.php';
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
?>
<html>
<head>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
  <link href='../css/main.css' rel='stylesheet'>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div id='invalidLineitem'></div>
    <div class='row well well-lg'>
      <div class='col-md-12'>
        <!-- Getting the po_number from the po_ID -->
        <?php
            $po_ID = $_SESSION["po_ID"];
            $sql = "SELECT po_number, receiving_date, initial_inspection, nr_of_lines, shipping_info
                    FROM pos
                    WHERE po_ID = '$po_ID'";
            $result = mysqli_query($link, $sql);
            while($row = mysqli_fetch_array($result)){
                $po_number = $row[0];
                $receiving_date = $row[1];
                $initial_inspection = $row[2];
                $nr_of_lines = $row[3];
                $shipping_info = $row[4];
            }
        ?>
        <h1><?php echo $po_number; ?></h1>
        <p>Insert new values to the input fields below to edit the PO.</p>
        <p>Fields you leave empty will remain unchanged.</p>
        <div class='col-md-8'>
        <div class='col-md-4'>
            <p>
              <strong>PO number : </strong><?php echo $po_number; ?>
            </p>
            <input type='text' id='input_po_number'/>
        </div>
        <div class='col-md-4'>
            <p>
              <strong>Receiving date : </strong><?php echo $receiving_date; ?>
            </p>
            <input type='date' id='input_date'/>
        </div>
        <p></p>
        <div class='col-md-4' style='margin-top: -8px;'>
            <p>
              <strong>Initial inspection : </strong><?php echo $initial_inspection; ?>
            </p>
            <input type='text' id='input_initial_inspect'/>
        </div>
        <p></p>
        <div class='col-md-5' style='margin-top:4px;'>
            <p>
              <strong>Number of lines on PO : </strong><?php echo $nr_of_lines; ?>
            </p>
            <input type='number' id='input_number_of_lines'/>
        </div>
        <div class='col-md-4' style='margin-left:-56px; margin-top:4px'>
          <p>
            <strong>Shipping info: </strong><?php echo $shipping_info; ?>
          </p>
          <select id='shipping_sel'>
            <option value=''>Unchanged</option>
            <option value='Ground'>Ground</option>
            <option value='3 day'>3 day</option>
            <option value='2 day'>2 day</option>
            <option value='next day'>Next day</option>
            <option value='fedex'>Fedex</option>
            <option value='other'>Other</option>
          </select>
        </div>
        <div class='col-md-12'>
        <p></p>
        <input type='submit' value='Submit changes' onclick='changePOInfo(<?php echo $po_ID;?>)' class='btn btn-primary'/>
        </div>
      </div>
      <div class='col-xs-3'>
        <!-- The onsubmit is to not allow the user to add files bigger than 250kb -->
        <form action="../InsertPHP/addimage.php" method="post" enctype="multipart/form-data" onsubmit="return checkSize(356000)">
          <label>Select image to upload:</label>
          <!-- hidden type which is used to redirect to the correct view -->
          <input type='hidden' value='edit' id='redirect' name='redirect'>
          <input type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg">
          <p></p>
          <input type="submit" class='btn btn-primary' value="Upload Image" name="submit">
        </form>
          <button class='btn btn-danger' onclick='deletePOScan(<?php echo $_SESSION["po_ID"];?>)' style='margin-top:-56px; margin-left:118px;'>Delete image</button>
      </div>
      <div class='col-xs-2' style='margin-top:20px;'>
        <label>Click to enlarge</label>
        <input type='image' src="../Scan/getImage.php" width="100" height="100" onerror="this.src='../images/noimage.jpg'" onclick="window.open('../Printouts/scanprintout.php')"/>
      </div>
        <h4 class='col-md-12' style='margin-left: -15px;'>Lineitems</h4>
        <table class='col-md-12' style='width:84%'>
          <tr>
            <th>Line on PO</th>
            <th>Quantity</th>
            <th>price</th>
            <th>Tool ID</th>
            <th>Diameter</th>
            <th>Length</th>
            <th>Double end</th>
          </tr>
          <?php
          $sql = "SELECT line_on_po, quantity, price, tool_ID, diameter, length, IF(double_end = 0, 'NO', 'YES'), p.po_number, l.lineitem_ID
                  FROM lineitem l, pos p
                  WHERE l.po_ID = '$po_ID'
                  AND l.po_ID = p.po_ID";
          $result = mysqli_query($link, $sql);
          if (!$result){
            die("Database query failed: " . mysql_error());
          }
          while($row = mysqli_fetch_array($result)){
            echo "<tr>".
              "<td><a href='#' data-toggle='modal' data-target='#".$row[0]."-edit'>".$row[0]."</td>".
              "<td>".$row[1]."</td>".
              "<td>$".$row[2]."</td>".
              "<td>".$row[3]."</td>".
              "<td>".$row[4]."</td>".
              "<td>".$row[5]."</td>".
              "<td style='display:none;'>".$row[8]."</td>".
              "<td>".$row[6]."<button style='float:right; margin-right: -120px' class='btn btn-primary discountButton' href='#' data-toggle='modal' data-target='#".$row[0]."'>Add discount</button>
              <button style='float:right; margin-right:-165px' class='btn btn-danger' onclick='deleteLineitem(".$po_ID.", ".$row[0].")'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></td>".
            "</tr>";

          }
          echo "</table>";
          $result = mysqli_query($link, $sql);
          while($row = mysqli_fetch_array($result)){
            echo "
                    <div class='modal fade' id='".$row[0]."-edit' tabindex='-1' role='dialog' aria-labelledby='".$row[1]."' aria-hidden='true'>
                      <div class='modal-dialog'>
                        <div class='modal-content col-md-12'>
                          <div class='modal-header'>
                            <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                            <h4 class='modal-title' id='myModalLabel'>Line item ID: ".$row[8]."</h4>
                          </div>
                          <div class='modal-body col-md-12'>
                            <input type='hidden' value='".$row[0]."' id='line'/>
                            <h4>Edit line item</h4>
                            <div class='col-md-12'>
                              <label>Change the quantity: </label>
                              <input type='number' id='input_quantity' value='".$row[1]."'/>
                              <p></p>
                            </div>
                            <div class='col-md-12'>
                              <label>Change the price: </label>
                              <input type='text' id='input_price' value='".$row[2]."'/>
                              <p></p>
                            </div>
                            <div class='col-md-12'>
                              <label >Change the tool ID: </label>
                              <input type='text' id='input_tool' value='".$row[3]."'/>
                              <p></p>
                            </div>
                            <div class='col-md-12'>
                              <label>Change the diameter: </label>
                              <input type='text' id='input_diameter' value='".$row[4]."'/>
                              <p></p>
                            </div>
                            <div class='col-md-12'>
                              <label >Change the length: </label>
                              <input type='text' id='input_length' value='".$row[5]."'/>
                              <p></p>
                            </div>
                            <div class='col-md-12'>
                              <label >Change double end (Enter 1 to change to double end. Enter 0 to change to single end)</label>
                              <input type='text' id='input_end' value='".$row[6]."'/>
                              <p></p>
                            </div>
                          </div>
                          <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' onclick='changeLineitemInfo(".$po_ID.", this)' data-dismiss='modal'>Apply</button>
                          </div>
                        </div>
                      </div>
                   </div>";
            echo
            "<div class='modal fade' id='".$row[0]."' tabindex='-1' role='dialog' aria-labelledby='".$row[0]."' aria-hidden='true'>
              <div class='modal-dialog'>
                <div class='modal-content'>
                  <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title' id='myModalLabel'>Apply discount to lineitem : ".$row[0]." <br>on PO : ".$row[7]."</h4>
                  </div>
                  <div class='modal-body'>
                    <table>
                      <tr>
                        <td>Line on PO</td>
                        <td>Quantity</td>
                        <td>Price</td>
                        <td>Tool ID</td>
                      </tr>
                      <tr>
                        <td>".$row[0]."</td>
                        <td>".$row[1]."</td>
                        <td>$".$row[2]."</td>
                        <td>".$row[3]."</td>
                      </tr>
                    </table>
                    <div class='col-md-12'>
                    <p></p>
                    <p>Number of tools</p>
                    <input name='quantity' type='number' class='discount_quantity'/>
                    </div>
                    <div class='col-md-12'>
                    <p></p>
                    <p>Discount per tool</p>
                    <input name='discount' type='text' class='discount'/>
                    </div>
                    <div class='col-md-12'>
                    <p></p>
                    <p>Reason for discount</p>
                    <textarea name='reason' class='discount_reason'></textarea>
                    </div>
                  </div>
                  <div class='modal-footer'>
                    <button class='btn btn-primary' onclick='applyDiscount()'>Apply discount</button>
                  </div>
                </div>
              </div>
           </div>";
          }
          ?>
      </div>
      <?php
        // TODO
        // Check if this PO has a lineitem that has a discount
        // If so, display table containing the discount info
        // else, display naathin
        $sql = "SELECT d.lineitem_ID, l.line_on_po, d.number_of_tools, d.discount, d.discount_reason, d.discount_ID
                FROM discount d, lineitem l
                WHERE po_ID = '$po_ID'
                AND d.lineitem_ID = l.lineitem_ID;";
        $sqlResults = mysqli_query($link, $sql);
        if(mysqli_num_rows($sqlResults) > 0){
          echo "<div class='col-md-12'>
                <h4>Discounts</h4>
                <table class='col-md-12' style='width:88%'>
                  <tr>
                    <td>Line on PO</td>
                    <td>Number of tools</td>
                    <td>Discount</td>
                    <td>Reason</td>
                  </tr>";
          while ($row = mysqli_fetch_array($sqlResults)){
            echo "<tr>".
                    "<td>".$row[1]."</td>".
                    "<td>".$row[2]."</td>".
                    "<td>".$row[3]."</td>".
                    "<td style='display:none'>".$row[5]."</td>".
                    "<td>".$row[4]."<button style='float:right; margin-right:-135px'class='btn btn-danger deleteDiscountButton'>Delete discount</button></td>".
                  "</tr>";
          }
          echo "</table>
                </div>";
        } else{
          echo "<div class='col-md-12'>
                  <h4>No discounts</h4>
                </div>";
        }
      ?>
      <div class='col-md-12' style='margin-top:20px;'>
        <a class="btn btn-primary" href="../Views/addTools2.php" style="margin-top: 3px;">Add lineitems</a>
        <?php if($user_sec_lvl > 3){
          echo "<button class='btn btn-danger' style='float: right;' onclick='delPO(".$po_ID.", 1)'>Delete PO</button>";
        }else if(safe_delete($po_ID)){
          echo "<button class='btn btn-danger' style='float: right;' onclick='delPO(".$po_ID.", 0)'>Delete PO</button>";
        }?>
      </div>
    </div>
    <div class='row well'>
      <div class='col-md-12'>
        <h2>Status pictures</h2>
        <div class='col-md-3'>
          <!-- The onsubmit is to not allow the user to add files bigger than 2mb -->
          <form action="../InsertPHP/addStatusPicture.php" method="post" enctype="multipart/form-data" onsubmit="return checkSize(2097152)">
            <label>Select image to upload:</label>
            <!-- hidden type which is used to redirect to the correct view -->
            <input type='hidden' value='edit' id='redirect' name='redirect'>
            <input type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg">
            <p></p>
            <label>Picture comment:</label>
            <textarea class='form-control' rows='4' id='status_comment' name='status_comment'></textarea>
            <p></p>
            <input type="submit" class='btn btn-primary' value="Upload Image" name="submit">
          </form>
        </div>
        <div class='col-md-8'>
          <?php
          $statusSql = "SELECT status_ID, status_picture, status_comment
                        FROM po_status
                        WHERE po_ID = '$po_ID';";
          $statusResult = mysqli_query($link, $statusSql);
          while($statusRow = mysqli_fetch_array($statusResult)){
            echo"<div class='col-md-6'>
                  <div class='col-md-6'>
                    <input type='image' src='../Scan/getStatusPicture.php?id=".$statusRow[0]."' width='150' height='150' onerror=\"this.src='../images/noimage.jpg'\" onclick=\"window.open('../Printouts/statusPrintout.php?id=".$statusRow[0]."')\"/>
                    <button class='btn btn-danger' onclick='deleteStatusPicture(".$statusRow[0].")'>Delete image</button>
                  </div>
                  <div class='col-md-6'>
                    <p>".$statusRow[2]."</p>
                  </div>
                </div>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  <script>
$('.discountButton').click(function () {

    var lineitem_ID = $(this).closest('td').prev().html();
    setSessionLineitemID(lineitem_ID);
});

$('.deleteDiscountButton').click(function () {

    var discount_ID = $(this).closest('td').prev().html();
    deleteDiscount(discount_ID);
});
</script>
<script type="text/javascript">
// if this function returns false the file is not added
  function checkSize(max_img_size)
  {
      var input = document.getElementById("fileToUpload");
      if(input.files && input.files.length == 1)
      {
          if (input.files[0].size > max_img_size)
          {
              alert("The file size must be less than " + (max_img_size/1024) + "KB");
              return false;
          }
      }else{
          alert("No image chosen.");
          return false;
      }

      return true;
  }
</script>
</body>
</html>

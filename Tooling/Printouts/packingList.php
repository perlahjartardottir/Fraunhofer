<!DOCTYPE html>
<?php
include '../connection.php';
session_start();
//find the current user
$user = $_SESSION["username"];
$po_ID = $_SESSION["po_ID"];
//find his level of security
$secSql = "SELECT sec_lvl
           FROM Employees
           WHERE ename = '$user'";
$secResult = mysqli_query($link, $secSql);

while($row = mysqli_fetch_array($secResult)){
  $user_sec_lvl = $row[0];
}

//getting the right po_number
$po_IDsql = "SELECT p.po_number
             FROM   pos p
             WHERE p.po_ID = '$po_ID';";
$po_IDresult = mysqli_query($link, $po_IDsql);
if(!$po_IDresult){
  mysqli_error($link);
}
while($row = mysqli_fetch_array($po_IDresult)){
  $po_number = $row[0];
}
// query that gets all the data for the packing list table
// Add this for lineitems that have been added to runs
// AND lir.run_ID = r.run_ID
// AND r.coating_ID = c.coating_ID
// AND l.lineitem_ID = lir.lineitem_ID
$sql = "SELECT l.tool_ID, l.quantity, c.coating_type, l.quantity_on_packinglist, l.lineitem_ID
        FROM lineitem l, lineitem_run lir, coating c, run r
        WHERE l.po_ID = '$po_ID'
        AND lir.run_ID = r.run_ID
        AND r.coating_ID = c.coating_ID
        AND l.lineitem_ID = lir.lineitem_ID
        GROUP BY lir.lineitem_ID
        ORDER BY lir.lineitem_ID";
$tableresult = mysqli_query($link, $sql);
if(!$tableresult){
  mysqli_error($link);
}
// customer info for the packing list
$customerSql = "SELECT c.customer_name, c.customer_address, c.customer_phone, c.customer_fax
                FROM customer c, pos p
                WHERE p.customer_ID = c.customer_ID
                AND p.po_ID = '$po_ID';";
$customerResult = mysqli_query($link, $customerSql);
if(!$customerResult){
    mysqli_error($link);
}
while($row = mysqli_fetch_array($customerResult)){
    $customer_name    = $row[0];
    $customer_address = $row[1];
    $customer_phone   = $row[2];
    $customer_fax     = $row[3];
}

// Split the address to two parts so we can print it in two lines.
$addressArray = explode(',', $customer_address);

$address_line_1 = $addressArray[0];
$address_line_2 = $addressArray[1].$addressArray[2];

// query to find the right comment for this po
$sql = "SELECT final_inspection
        FROM pos
        WHERE po_ID = '$po_ID';";
$result = mysqli_query($link, $sql);
if(!$result){
  mysqli_error($link);
}
while($row = mysqli_fetch_array($result)){
    $comment = $row[0];
}
?>
<html>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <script type="text/javascript">
    window.onload = function() {
      $('input[type=date]').each(function() {
        if  (this.type != 'date' ) $(this).datepicker({
          dateFormat: 'yy-mm-dd'
        });
      });
    };
  </script>
  <link href='../css/print.css' rel='stylesheet'>
  <div class='container'>
    <div class='col-xs-12 commentHide'>
      <div class='row well well-lg'>
        <h4>Select a PO</h4>
        <form>
          <select name='POS' onchange='setSessionIDAndRefresh()' id='packingsel' class='form-control' style='width:auto;'>
            <option value''>Select a PO#: </option>
            <?php
            $sql = "SELECT po_ID, po_number
                    FROM pos
                    ORDER BY receiving_date DESC, po_ID DESC
                    LIMIT 12";
            $result = mysqli_query($link, $sql);
            while($row = mysqli_fetch_array($result)){
                echo '<option value="'.$row[0].'">'.$row[1].'</option>';
            }
           ?>
         </select>
       </form>
       <div class='col-xs-12' style='padding:0;'>
         <label for='addShippingDate'>Set a packing list comment</label>
       </br>
       <textarea id='packing_list_comment' rows='2' cols='35'><?php echo $comment; ?><?php $comment ?></textarea>
     </div>
     <div class='col-xs-12' style='padding:0;'>
      <label>Set shipping date</label>
    </br>
    <input type="date" id="addShippingDate" name='addShippingDate' value='<?php echo date("Y-m-d") ?>' class='form-control' style='width:auto;'/>
  </div>

  <div class='col-md-12' style='margin-bottom:5px; margin-left:-15px;'>
    <button type='button' id='addShippingDateButton' class='btn btn-primary col-md-3' onclick='confirmPO()'>Save <span class='glyphicon glyphicon-save'></span></button>
  </div>
  <div class='col-md-12' style='margin-bottom:5px; margin-left:-15px;'>
    <button type='button' class='btn btn-primary col-md-3' data-toggle='modal' data-target='#changeShipping'>Change shipping address</button>
  </div>
  <div class='col-md-12' style='margin-left:-15px;'>
    <button type='button' class='btn btn-primary col-md-3' onclick='saveAndPrint()'>Print <span class='glyphicon glyphicon-print'></span></button>
  </div>

  <!-- Modal to change shipping address -->
  <div id="changeShipping" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change shipping address</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class='form-group'>
            <label>Customer name: </label><input type='text' id='newCustomerName' class='form-control' style='width:auto' />
          </div>
          <div class='form-group'>
            <label>Address line 1: </label><input type='text' id='newAddress1' class='form-control' style='width:auto'>
          </div>
          <div class='form-group'>
            <label>Address line 2: </label><input type='text' id='newAddress2' class='form-control' style='width:auto'>
          </div>
          <div class='form-group'>
            <label>Phone number: </label><input type='text' id='newPhone' class='form-control' style='width:auto'>
          </div>
          <div class='form-group'>
            <label>Fax number: </label><input type='text' id='newFax' class='form-control' style='width:auto'>
          </div>
        </form>
        <p>*These changes will only be for this session, to see original version just refresh the page</p>
      </div>
      <div class="modal-footer">
        <button type='button' class='btn btn-primary' data-dismiss='modal' onclick='changeShippingAddress()'>Make changes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
</div>
</div>
<div class="col-xs-12">
  <img src="../images/iso.jpg" alt="ISO logo" style="float:right; width:110px; height:auto; margin-top:10px;"/>
  <img src="../images/fraunhoferlogo.jpg" alt="Fraunhofer Logo" style="float:left; width:220px; height:auto; margin-top:10px;"/>
</div>
<div>
 <div class="col-xs-12">
  <h5>Packing list </h5>
</div>
<div>
  <hr>
</div>
<div class='col-xs-6'>
  <span class='col-xs-12'><strong>Shipped to: </strong></span>
  <span class='col-xs-12'></br></span>
  <span id='changeShippingAddress'></span>
  <span id='originalShippingAddress'>
  <span class="col-xs-12"><strong><?php echo $customer_name;?></strong></span>
  <span class="col-xs-12"><?php echo $address_line_1; ?></span>
  <span class="col-xs-12"><?php echo $address_line_2; ?></span>
  <span class="col-xs-12">Ph. <?php echo $customer_phone;?></span>
  <span class="col-xs-12">Fax <?php echo $customer_fax;?></span></span>
</div>
<div class="col-xs-6" id="bottomDiv">
  <span class="col-xs-12 col-xs-offset-2">Fraunhofer USA</span>
  <span class="col-xs-12 col-xs-offset-2">Center for Coatings and Diamond Technologies</span>
  <span class='col-xs-12'></br></span>
  <span class="col-xs-12 col-xs-offset-2">1449 Engineering Research Court</span>
  <span class="col-xs-12 col-xs-offset-2">Michigan State University</span>
  <span class="col-xs-12 col-xs-offset-2">East Lansing, MI, 48824</span>
  <span class="col-xs-12 col-xs-offset-2"></br></span>
  <span class="col-xs-12 col-xs-offset-2">Lars Haubold</span>
  <span class="col-xs-12 col-xs-offset-2">Ph. 1-517-432-8179</span>
  <span class="col-xs-12 col-xs-offset-2">Fax. 1-517-432-8167</span>
  <span class="col-xs-12 col-xs-offset-2">Email: lhaubold@fraunhofer.org</span>
</div>
</div>

<div>
  <hr>
</div>
<div class="col-xs-12" id='aboveTable'>
  <h5 class='col-xs-4'>
    <?php
        // this displayes the date the right way
    $sql = "SELECT DATE_FORMAT(shipping_date,'%m/%d/%y')
            FROM pos
            WHERE po_ID = '$po_ID';";
    $result = mysqli_query($link, $sql);
    if(!$result){
      mysqli_error($link);
    }
    while($row = mysqli_fetch_array($result))
    {
      $shippingDate = $row[0];
    }
    echo "Shipping date: ".$shippingDate;
    ?>
  </h5>
  <span><h5 class="col-xs-6"> Purchase Order #: <span id='po_ID'><?php echo $po_number; ?></span></h5></span>
  <span><h5 class="col-xs-2"> Initial: LH</h5></span>
</div>
<div class="col-xs-12" id="tableDiv">
  <table class="packingTable col-xs-12">
    <tr class="packingTable">
      <th class="packingTable commentHide hidden">Lineitem_ID</th>
      <th class="packingTable">Tool type</th>
      <th class="packingTable"><center>Tools received</center></th>
      <th class="packingTable"><center>Tools in shipment</center></th>
      <th class="packingTable"><center>Coating type</center></th>
    </tr>
    <?php
    while($row = mysqli_fetch_array($tableresult)){
            // If the user has coated more tools then he got then
            // he recoated some tools. If there are some broken tools
            // the user puts in a comment but he still ships them all back.
      if($row[1] > $row[2]){
        $row[1] = $row[2];
      }
      echo "<tr class='packingTable'>".
      "<td class='packingTable commentHide hidden'>".$row[4]."</td>".
      "<td class='packingTable'>".$row[0]."</td>".
      "<td class='packingTable centering'>".$row[1]."</td>".
      "<td class='packingTable centering'><input type='text' style='text-align: center;' class='table_input' value='".$row[3]."'/><input type='button' style='margin-left: 3px;' class='btn btn-success commentHide saveButton' value='Save changes'></input></td>".
      "<td class='packingTable'><center>".$row[2]."</center></td>";

      // "<td class='packingTable'><input type='text' style='text-align: left;' class='table_input' value='".$row[3]."'/><span style='text-align: left;'>/".$row[1]." </span><input type='button' style='text-align: left;' class='btn btn-success commentHide saveButton' value='Save changes'></input></td>".
    }
    ?>
  </table>
</div>
<div class='col-xs-12'>
  <?php
  $sql = "SELECT final_inspection
          FROM pos
          WHERE po_ID = '$po_ID';";
  $result = mysqli_query($link, $sql);
  if(!$result){
    mysqli_error($link);
  }
  while($row = mysqli_fetch_array($result)){
    $comment = $row[0];
  }
  ?>
  <p class='col-xs-3 comments'>Comment: </p>
  <p class='comments'><?php echo $comment; ?></p>
</div>
  <div class='col-xs-12 thankYouNote text-right'>
  <i>------------ Thank you for your business ------------</i>
</div>
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Confirm PO</h4>
      </div>
      <div class="modal-body">
        <p>This PO is missing comments. It could be run comments or lineitem comments.</p>
        <p>To ignore this and store the PO click "Confirm PO"</p>
        <p>Click "Go to track sheet" to edit this PO</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-default" onclick='addShipDateToPO(document.getElementById("packing_list_comment").value, document.getElementById("addShippingDate").value)'>Confirm PO</button>
        <a href='../views/generateTrackSheet.php' class='btn btn-primary' role='button' type='button'>Track sheet</a>
      </div>
    </div>
  </div>
</div>
<script>
$('.saveButton').click(function () {
    // the quantity in the input field
    var quantity = $(this).prev('input').val();
    // the lineitem_ID in the hidden field of the table
    var lineitem_ID = $(this).closest('td').prev().prev().prev().html();
    updatePackinglistQuantity(lineitem_ID, quantity);
    $(this).val("Changes saved!");
});
</script>
<script>
    //if the user enters the view with a PO not on the dropdownlist
    // check if the value is in the list already
    var exists = false;
    $('#packingsel option').each(function(){
        if (this.value == '<?php echo $po_ID; ?>') {
            return false;
        }
    });
    // if the list doesnt contain our PO we add it to the dropdown
    if(!exists){
        $('#packingsel').append($('<option>', {
            value: <?php echo $po_ID; ?>,
            text: '<?php echo $po_number; ?>'
        }));
    }
    //make the dropdown list show the currently chosen PO
    $('#packingsel').val("<?php echo $po_ID;?>");
</script>

<script>
// makes the dropdown show the selected PO even after refresh
$( document ).ready(function() {
    var po_ID = <?php echo $po_ID; ?>;
    $("#packingsel").val(po_ID);
    });
</script>
</body>
</html>

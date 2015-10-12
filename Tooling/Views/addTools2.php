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
$user_sec_lvl = str_split($user_sec_lvl);
$user_sec_lvl = $user_sec_lvl[0];
if($user_sec_lvl < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}
// get the po_number from the session po_ID
$po_ID = $_SESSION["po_ID"];
$po_numberSql = "SELECT po_number
                 FROM pos
                 WHERE po_ID = '$po_ID'";

$po_numberResult = mysqli_query($link, $po_numberSql);
$po_number = mysqli_fetch_array($po_numberResult);
?>
<html>
<head>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
  <!-- <link href='../css/main.css' rel='stylesheet'> -->
  <link href='../css/tabs.css' rel='stylesheet'>
</head>
<body>
  <?php include '../header.php'; ?>
    <div class='container'>
      <div class='row well well-lg'>
        <!-- This is to fetch the latest po inserted -->
        <?php
          $sql = "SELECT po_number, po_ID
                  FROM pos
                  WHERE po_ID = (SELECT MAX(po_ID)
                                 FROM pos);";
          $result = mysqli_query($link, $sql);

          while($row = mysqli_fetch_array($result)){
              $_SESSION["po_number"] = $row[0];
              $newest_po_ID = $row[1];
          }
        ?>
          <input type="hidden" id='mostRecentPo_ID' value="<?php echo $newest_po_ID; ?>" />
          <div class='col-xs-12'>
            <h2>You are currently working on PO number <?php echo $po_number[0];?></h2>
            <div class='col-xs-6'>Most recently added PO is
              <button class='btn btn-md btn-link' onclick='showToolsAndRefreshImage(document.getElementById("mostRecentPo_ID").value)'>
                <?php echo $_SESSION["po_number"];?>
              </button>click to use it.</div class='col-xs-6'>
            </div>
          </div>
      <div class='row well well-lg'>
        <div class='col-xs-12'>
          <div class='col-xs-6'>
            <h2>Choose the right PO number</h2>
            <form>
              <select name='POS' onchange='showToolsAndRefreshImage(this.value)' id='posel' class='form-control' style='width: auto;'>
                <option value ''>Select a PO#: </option>
                <?php
                   $sql = "SELECT po_ID, po_number
                           FROM pos
                           ORDER BY receiving_date DESC
                           LIMIT 12";
                   $result = mysqli_query($link, $sql);
                   while($row = mysqli_fetch_array($result))
                   {
                     echo '<option value="'.$row[0].'">'.$row[1].'</option>';
                   }
                ?>
              </select>
            </form>
            <br>
            <div id="poinfo"><b>PO info will be listed here</b></div>
          </div>
          <div class='col-xs-3' style='margin-top:20px;'>
            <!-- The onsubmit is to not allow the user to add files bigger than 250kb -->
            <form action="../InsertPHP/addimage.php" method="post" enctype="multipart/form-data" onsubmit="return checkSize(356000)">
              <label>Select image to upload:</label>
              <!-- hidden type which is used to redirect to the correct view -->
              <input type='hidden' value='new' id='redirect' name='redirect'>
              <input type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg">
              <p></p>
              <input type="submit" class='btn btn-primary' value="Upload Image" name="submit">
              <button class='btn btn-danger' onclick='deletePOScan(<?php echo $_SESSION["po_ID"];?>)' style='margin-top:-56px; margin-left:118px;'>Delete image</button>
            </form>
          </div>
          <div class='col-xs-2' style='margin-top:20px;'>
            <label>Click to enlarge</label>
            <input type='image' src="../Scan/getImage.php" width="100" height="100" onerror="this.src='../images/noimage.jpg'" onclick="window.open('../Printouts/scanprintout.php')" />
          </div>
        </div>
      </div>
      <div class='row well well-lg'>
        <div role="tabpanel">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs tablist sampleTabs" role="tablist">
            <li role="presentation" class="active"><a href="#normal" aria-controls="home" role="tab" data-toggle="tab">Round Tools</a></li>
            <li role="presentation"><a href="#inserts" aria-controls="inserts" role="tab" data-toggle="tab">Insert tools</a></li>
            <li role="presentation"><a href="#odd" aria-controls="odd" role="tab" data-toggle="tab">Odd shaped tools</a></li>
            <li role="presentation"><a href="#topNotch" aria-controls="topNotch" role="tab" data-toggle="tab">Top notch tools</a></li>
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="normal">
              <form>
                <div class='col-xs-3 form-group'>
                  <label for="lineItem">Line on PO: </label>
                  <input type="number" name="lineItem" id="lineItem" class='form-control'>
                </div>
                <div class='col-xs-3 form-group'>
                  <label for="toolID">Tool ID Number: </label>
                  <input type="text" name="toolID" id="tid" class='form-control'>
                </div>
                <div class='col-xs-3 form-group'>
                  <label for="quantity">Quantity: </label>
                  <input type=" number" name="quantity" id="quantity" class='form-control'>
                </div>
                <div class='col-xs-3 form-group'>
                  <label for="coatingID">Coating</label>
                  <select id='coating_sel' onchange='generatePrice()' onfocus='generatePrice()' class='form-control'>
                    <option value="">Select coating type:</option>
                    <?php
                      $sql = "SELECT coating_ID, coating_type
                              FROM coating
                              ORDER BY coating_type ASC";
                      $result = mysqli_query($link, $sql);
                      if (!$result)
                      {
                        die("Database query failed: " . mysqli_error($link));
                      }
                      while($row = mysqli_fetch_array($result))
                      {
                        echo '<option value="'.$row['coating_ID'].'">'.$row['coating_type'].'</option>';
                      }
                      ?>
                  </select>
                </div>
                <div class='col-xs-3 form-group' id='changediameter'>
                  <label for="diameter">Diameter: </label>
                  <select id="diameter" name="diameter" onchange='generatePrice()' onfocus='generatePrice()' class='form-control'>
                    <option value="0">N/A</option>
                    <option value="1/8">1/8</option>
                    <option value="3/16">3/16</option>
                    <option value="1/4">1/4</option>
                    <option value="3/8">3/8</option>
                    <option value="1/2">1/2</option>
                    <option value="5/8">5/8</option>
                    <option value="3/4">3/4</option>
                    <option value="1">1</option>
                    <option value="1 1/4">1 1/4</option>
                    <option value="1 3/8">1 3/8</option>
                  </select>
                </div>
                <div class='col-xs-3 form-group' id='changelength'>
                  <label for="length">Length: </label>
                  <select id="length" name="length" onchange='generatePrice()' onfocus='generatePrice()' class='form-control'>
                    <option value="0">N/A</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                  </select>
                </div>
                <div class='col-xs-3 form-group' id='pricediv'>
                  <label for="price">Unit Price: </label>
                  <input name="price" id='price' value='' class='form-control'></input>
                </div>
                <div class='col-xs-3'>
                  <label for="dblEnd">Double ended? </label>
                  <input type="checkbox" name="dlbEnd" id="dblEnd" class='form-control' onchange='generatePrice()'>
                </div>
                <button type='button' class='btn btn-default col-xs-offset-10' onclick='showPOTools()'>
                  <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                </button>
                <button type='button' class='btn btn-default' onclick='addTool()'>
                  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
              </form>
            </div>
            <!-- ======================================= -->
            <div role="tabpanel" class="tab-pane" id="inserts">
              <form>
                <div class='col-xs-3 form-group'>
                  <label for="lineItemInsert">Line on PO: </label>
                  <input type="number" name="lineItemInsert" id="lineItemInsert" class='form-control'>
                </div>
                <div class='col-xs-3 form-group'>
                  <label for="toolIDInsert">Tool ID Number: </label>
                  <input type="text" name="toolIDInsert" id="toolIDInsert" class='form-control'>
                </div>
                <div class='col-xs-3 form-group'>
                  <label for="quantityInsert">Quantity: </label>
                  <input type=" number" name="quantityInsert" id="quantityInsert" class='form-control'>
                </div>
                <div class='col-xs-3 form-group'>
                  <label for="coatingIDInsert">Coating</label>
                  <select id='coating_sel_insert' class='form-control'>
                    <option value="">Select coating type:</option>
                    <?php
                      $sql = "SELECT coating_ID, coating_type
                      FROM coating
                      ORDER BY coating_type ASC";
                      $result = mysqli_query($link, $sql);
                      if (!$result)
                      {
                        die("Database query failed: " . mysqli_error($link));
                      }
                      while($row = mysqli_fetch_array($result))
                      {
                        echo '<option value="'.$row['coating_ID'].'">'.$row['coating_type'].'</option>';
                      }
                      ?>
                  </select>
                </div>
                <div class='col-xs-3 form-group'>
                  <label for="IC">IC: </label>
                  <!-- TODO : calculate prices using this value -->
                  <select id="diameterInsert" name="IC" class='form-control'>
                    <option value="0">N/A</option>
                    <option value="1/8">1/8</option>
                    <option value="3/16">3/16</option>
                    <option value="1/4">1/4</option>
                    <option value="3/8">3/8</option>
                    <option value="1/2">1/2</option>
                    <option value="5/8">5/8</option>
                    <option value="3/4">3/4</option>
                    <option value="1">1</option>
                    <option value="1 1/4">1 1/4</option>
                    <option value="1 3/8">1 3/8</option>
                  </select>
                </div>
                <div class='col-xs-3 form-group' id='pricediv'>
                  <label for="priceInsert">Unit Price: </label>
                  <input name="priceInsert" id='priceInsert' value='' class='form-control'></input>
                </div>
                <button type='button' class='btn btn-default col-xs-offset-10' onclick='showPOTools()'>
                  <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                </button>
                <button type='button' class='btn btn-default' onclick='addToolInsert()'>
                  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </button>
              </form>
            </div>
            <!---_______________________________________ -->
            <div role="tabpanel" class="tab-pane" id="odd">
              <div class='col-xs-3 form-group'>
                <label for="lineItemOdd">Line on PO: </label>
                <input type="number" name="lineItemOdd" id="lineItemOdd" class='form-control'>
              </div>
              <div class='col-xs-3 form-group'>
                <label for="toolIDOdd">Tool ID Number: </label>
                <input type="text" name="toolIDOdd" id="toolIDOdd" class='form-control'>
              </div>
              <div class='col-xs-3 form-group'>
                <label for="quantityOdd">Quantity: </label>
                <input type=" number" name="quantityOdd" id="quantityOdd" class='form-control'>
              </div>
              <div class='col-xs-3 form-group'>
                <label for="coatingIDInsert">Coating</label>
                <select id='coating_sel_odd' class='form-control'>
                  <option value="">Select coating type:</option>
                  <?php
                    $sql = "SELECT coating_ID, coating_type
                    FROM coating
                    ORDER BY coating_type ASC";
                    $result = mysqli_query($link, $sql);
                    if (!$result)
                    {
                      die("Database query failed: " . mysqli_error($link));
                    }
                    while($row = mysqli_fetch_array($result))
                    {
                      echo '<option value="'.$row['coating_ID'].'">'.$row['coating_type'].'</option>';
                    }
                    ?>
                </select>
              </div>
              <div class='col-xs-3 form-group' id='pricediv'>
                <label for="priceOdd">Unit Price: </label>
                <input name="priceOdd" id='priceOdd' value='' class='form-control'></input>
              </div>
              <div class='col-xs-3 form-group'>
                <label for="dblEndOdd">Double ended? </label>
                <input type="checkbox" name="dblEndOdd" id="dblEndOdd" class='form-control'>
              </div>
              <button type='button' class='btn btn-default col-xs-offset-10' onclick='showPOTools()'>
                <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
              </button>
              <button type='button' class='btn btn-default' onclick='addToolOdd()'>
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
              </button>
            </div>
            <!---_______________________________________ -->
            <div role="tabpanel" class="tab-pane" id="topNotch">
              <div class='col-xs-3 form-group'>
                <label for="lineItemTop">Line on PO: </label>
                <input type="number" name="lineItemTop" id="lineItemTop" class='form-control'>
              </div>
              <div class='col-xs-3 form-group'>
                <label for="toolIDTop">Tool ID Number: </label>
                <input type="text" name="toolIDTop" id="toolIDTop" class='form-control'>
              </div>
              <div class='col-xs-3 form-group'>
                <label for="quantityTop">Quantity: </label>
                <input type=" number" name="quantityTop" id="quantityTop" class='form-control'>
              </div>
              <div class='col-xs-3 form-group'>
                <label for="coatingIDTop">Coating</label>
                <select id='coating_sel_top' onchange='generatePriceTopNotch()' class='form-control'>
                  <option value="">Select coating type:</option>
                  <?php
                    $sql = "SELECT coating_ID, coating_type
                            FROM coating
                            ORDER BY coating_type ASC";
                    $result = mysqli_query($link, $sql);
                    if (!$result)
                    {
                      die("Database query failed: " . mysqli_error($link));
                    }
                    while($row = mysqli_fetch_array($result))
                    {
                      echo '<option value="'.$row['coating_ID'].'">'.$row['coating_type'].'</option>';
                    }
                    ?>
                </select>
              </div>
              <div class='col-xs-3 form-group'>
                <label for="insert_size">Insert size: </label>
                <!-- TODO : calculate prices using this value -->
                <select id="insert_size" name="IC" class='form-control' onchange='generatePriceTopNotch()'>
                  <option value="">N/A</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                </select>
              </div>
              <div class='col-xs-3 form-group' id='pricediv'>
                <label for="priceTop">Unit Price: </label>
                <input name="priceTop" id='priceTop' value='' class='form-control'></input>
              </div>
              <button type='button' class='btn btn-default col-xs-offset-10' onclick='showPOTools()'>
                <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
              </button>
              <button type='button' class='btn btn-default' onclick='addToolTop()'>
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div id='invalidTool'></div>
      <div id="status_text"></div>

      <!-- SelectPHP/getToolsForToolOverView.php -->
      <table id='txtAdd' class='table table-responsive table-bordered table-condensed'>
      </table>
      <div class='navbar navbar-default navbar-static-bottom'>
        <a class='btn btn-primary' style='margin-top:5px; margin-left: 5px; float:right;' href='../views/generateTrackSheet.php'>Generate track sheet for this PO</a>
        <a class='btn btn-primary' style='margin-top:5px; float:right;' href='../Printouts/generalinfo.php'>Print general information sheet</a>
      </div>
    </div>
    <script>
      //show the info for the PO chosen when you enter the page or refresh it
      $(document).ready(function() {
        var po_ID = <?php echo $po_ID; ?>;
        showTools(po_ID);
        showPOTools();
      });
      //if the user enters the view with a PO not on the dropdownlist
      // check if the value is in the list already
      var exists = false;
      $('#posel option').each(function() {
        if (this.value == '<?php echo $po_ID; ?>') {
          exists = true;
        }
      });
      // if the list doesnt contain our PO we add it to the dropdown
      if (!exists) {
        $('#posel').append($('<option>', {
          value: <?php echo $po_ID; ?>,
          text: '<?php echo $po_number[0]; ?>'
        }));
      }
      //make the dropdown list show the currently chosen PO
      $('#posel').val("<?php echo $po_ID;?>");
    </script>
    <script type="text/javascript">
      // if this function returns false the file is not added
      function checkSize(max_img_size) {
        var input = document.getElementById("fileToUpload");
        if (input.files && input.files.length == 1) {
          if (input.files[0].size > max_img_size) {
            alert("The file size must be less than " + (max_img_size / 1024) + "KB");
            return false;
          }
        } else {
          alert("No image chosen.");
          return false;
        }

        return true;
      }
    </script>
</body>

</html>

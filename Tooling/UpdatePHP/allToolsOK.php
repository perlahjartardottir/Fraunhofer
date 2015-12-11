<?php
include '../connection.php';

$po_ID = mysqli_real_escape_string($link, $_POST['po_ID']);
$lineitem_ID = mysqli_real_escape_string($link, $_POST['lineitem_ID']);
$run_ID = mysqli_real_escape_string($link, $_POST['run_ID']);

$sql = "SELECT lr.lineitem_ID, lr.run_ID, lr.lineitem_run_comment
        FROM lineitem l, lineitem_run lr, run r
        WHERE l.po_ID = '$po_ID'
        AND l.lineitem_ID = lr.lineitem_ID
        AND lr.run_ID = r.run_ID;";
$result = mysqli_query($link, $sql);

if (!$result) {
  die("Could not update run comments: ".mysqli_error($link));
}

// Put all OK to all lineitems with empty comment
while($row = mysqli_fetch_array($result)){
  // If the line item already has a comment, it doesn't alter that comment.
  if($row[2] == ''){
    $updateSql = "UPDATE lineitem_run SET lineitem_run_comment = 'OK'
                  WHERE lineitem_ID = '$row[0]'
                  AND run_ID = '$row[1]';";
    $updateResult = mysqli_query($link, $updateSql);
    if (!$updateResult) {
      die("Could not update run comments: ".mysqli_error($link));
    }
  }
}
mysqli_close($link);
?>

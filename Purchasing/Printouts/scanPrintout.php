<!DOCTYPE html>
<?php
$order_ID = $_SESSION["order_ID"];
$id = $_GET['id'];

?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<body>
<img onerror="this.src='../images/noimage.jpg'" src="../Scan/getImage.php?id=<?php echo $id; ?>" width="1000" height="1000" />
<p><?php echo $_SESSION["po_ID"];?>
</body>
</html>

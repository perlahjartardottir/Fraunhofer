<!DOCTYPE html>
<?php
$id = $_GET['id'];

?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<body>
<img onerror="this.src='../images/noimage.jpg'" src="../Scan/getRequestQuoteImage.php?id=<?php echo $id; ?>" width="1000" height="1000" />
<p><?php echo $_SESSION["po_ID"];?>
</body>
</html>

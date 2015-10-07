<?php
include '../../connection.php';
session_start();

$redirect = mysqli_real_escape_string($link, $_POST['redirect']);
$employee_ID = mysqli_real_escape_string($link, $_POST['employee_ID']);
$id = $_GET['id'];

$fileName = $_FILES['fileToUpload']['name'];
$tmpName  = $_FILES['fileToUpload']['tmp_name'];

$fp      = fopen($tmpName, 'r');
$content = fread($fp, filesize($tmpName));
$content = addslashes($content);
fclose($fp);

// if the user is setting his own signature he does not send ID with the link (?id=...)
// so he updates his own signature, but if you want to add signature for someone else in
// edit employee view then you send in an ID and update that persons signature
if($id == ''){
  $sql = "UPDATE employee
          SET employee_signature = '$content'
          WHERE employee_ID = '$employee_ID';";
}else {
  $sql = "UPDATE employee
          SET employee_signature = '$content'
          WHERE employee_ID = '$id';";
}
$result = mysqli_query($link, $sql);
if(!$result){
	echo("Something went wrong : ".mysqli_error($link));
}

// close connection
mysqli_close($link);

// Redirecting to the correct view, depending on whether
// we were adding a tool or editing PO
if($redirect == 'signature'){
	header('Location: ../../Views/viewAllEmployees.php');
}else if($redirect == 'editProfile'){
	header('Location: ../../Views/editProfile.php');
}
?>

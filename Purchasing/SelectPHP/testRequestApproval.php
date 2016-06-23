<?php
$to = "phjartardottir@fraunhofer.org";
$subject = "Purchasing test email";
$message = "This is the message";
$headers = "From: ccd.purchasing@gmail.com";

$mail = mail($to, $subject, $message, $headers);
if(!$mail){
echo "<script> console.log(".json_encode('No email').")</script>";
  print_r(error_get_last());
  var_dump(error_get_last());
}
?>

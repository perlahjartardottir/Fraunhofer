<?php
$link = mysqli_connect("localhost:8889", "root", "root", "Fraunhofer");

session_start();

// Send email with php
$emailSql = "SELECT email_ID, email_sender, order_ID FROM email";
$emailResult = mysqli_query($link, $emailSql);
while($emailRow = mysqli_fetch_array($emailResult)){
  // Find employee email
  $employeeEmailSql = "SELECT employee_email FROM employee
                       WHERE employee_ID = '$emailRow[1]';";
  $employeeEmailResult = mysqli_query($link, $employeeEmailSql);
  $employeeEmailRow = mysqli_fetch_array($employeeEmailResult);

  // Find the order name
  $emailOrderNameSql = "SELECT order_name FROM purchase_order
                        WHERE order_ID = '$emailRow[2]';";
  $emailOrderNameResult = mysqli_query($link, $emailOrderNameSql);
  $emailOrderNameRow = mysqli_fetch_array($emailOrderNameResult);

  // send the email
  mail($employeeEmailRow[0], "Purchase order incoming in 5 days", 'Your purchase order, '.$emailOrderNameRow[0].', is expected in 5 days', "From:" . "ffridfinnsson@fraunhofer.org");

  // As soon as the email is sent, we delete the row in the table so the employee
  // Doesn't get an email every day.
  $deleteEmailSql = "DELETE FROM email
                     WHERE email_ID = '$emailRow[0]';";
  $deleteEmailResult = mysqli_query($link, $deleteEmailSql);
}
?>

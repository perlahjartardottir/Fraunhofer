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
  $emailOrderNameSql = "SELECT order_name, supplier_ID FROM purchase_order
                        WHERE order_ID = '$emailRow[2]';";
  $emailOrderNameResult = mysqli_query($link, $emailOrderNameSql);
  $emailOrderNameRow = mysqli_fetch_array($emailOrderNameResult);

  $supplierSql = "SELECT supplier_name FROM supplier
                  WHERE supplier_ID = '$emailOrderNameRow[1]';";
  $supplierResult = mysqli_query($link, $supplierSql);
  $supplier = mysqli_fetch_array($supplierResult);

  // send the email
  mail($employeeEmailRow[0], $emailOrderNameRow[0]." from ".$supplier[0]." expected delivery in three days", 'Your purchase order, '.$emailOrderNameRow[0].', is expected in three days', "From:" . "ccd.purchasing@gmail.com");

  // As soon as the email is sent, we delete the row in the table so the employee
  // Doesn't get an email every day.
  $deleteEmailSql = "DELETE FROM email
                     WHERE email_ID = '$emailRow[0]';";
  $deleteEmailResult = mysqli_query($link, $deleteEmailSql);
}
?>

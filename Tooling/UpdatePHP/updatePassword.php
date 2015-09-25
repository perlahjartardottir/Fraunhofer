<?php
include '../connection.php';

$employee_ID   = mysqli_real_escape_string($link, $_POST['employee_ID']);
$currentPass   = mysqli_real_escape_string($link, $_POST['currentPass']);
$newPass       = mysqli_real_escape_string($link, $_POST['newPass']);
$confirmPass   = mysqli_real_escape_string($link, $_POST['confirmPass']);

if($newPass != $confirmPass){
  die("different passwords");
}
$sql = "SELECT employee_name, employee_password
        FROM employee
        WHERE employee_ID = '$employee_ID';";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$employee_name = $row[0];
$employee_password = $row[1];

if(crypt($currentPass, $employee_password) != $employee_password){
  die('invalid password');
}

// encrypting the password
function cryptPass($input, $rounds = 9){
	$salt = "";
	$saltChars = array_merge(range('A', 'Z'), range('a', 'z'), range(0,9));
	for($i = 0; $i < 22; $i++){
		$salt .= $saltChars[array_rand($saltChars)];
	}
	return crypt($input, sprintf('$2y$%02d$', $rounds) . $salt);
}
$hashedPassword = cryptPass($newPass);
$passwordSql = "UPDATE employee
                SET employee_password = '$hashedPassword'
                WHERE employee_ID = '$employee_ID';";
$passwordResult = mysqli_query($link, $passwordSql);
?>

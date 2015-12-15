<?php
include '../connection.php';

$employee_ID = mysqli_real_escape_string($link, $_POST['employee_ID']);

$sql =  "SELECT employee_name FROM employee
         WHERE employee_ID = '$employee_ID';";
$result = mysqli_query($link, $sql);

// Get the employee name fx. Freyr Fridfinnsson
$employee = mysqli_fetch_array($result);

// Only take the first name fx. Freyr
$firstName = explode(' ', trim($employee[0]));

// Put it to lower case fx. freyr
$firstNameLower = strtolower($firstName[0]);
// This will be the new password

// encrypting the password
function cryptPass($input, $rounds = 9){
	$salt = "";
	$saltChars = array_merge(range('A', 'Z'), range('a', 'z'), range(0,9));
	for($i = 0; $i < 22; $i++){
		$salt .= $saltChars[array_rand($saltChars)];
	}
	return crypt($input, sprintf('$2y$%02d$', $rounds) . $salt);
}
$hashedPassword = cryptPass($firstNameLower);

// Set the password to be the encrypted first name in lowercase
$passwordSql = "UPDATE employee
                SET employee_password = '$hashedPassword'
                WHERE employee_ID = '$employee_ID';";
$passwordResult = mysqli_query($link, $passwordSql);
mysqli_close($link);
?>

<?php
include '../connection.php';
 
// Escape user inputs for security
$eName        = mysqli_real_escape_string($link, $_POST['eName']);
$eEmail       = mysqli_real_escape_string($link, $_POST['eEmail']);
$ePass        = mysqli_real_escape_string($link, $_POST['ePass']);
$ePassAgain   = mysqli_real_escape_string($link, $_POST['ePassAgain']);
$ePhoneNumber = mysqli_real_escape_string($link, $_POST['ePhoneNumber']);
$sec_lvl      = mysqli_real_escape_string($link, $_POST['sec_lvl']);

function cryptPass($input, $rounds = 9){
	$salt = "";
	$saltChars = array_merge(range('A', 'Z'), range('a', 'z'), range(0,9));
	for($i = 0; $i < 22; $i++){
		$salt .= $saltChars[array_rand($saltChars)];
	}
	return crypt($input, sprintf('$2y$%02d$', $rounds) . $salt);
}
$hashedPassword = cryptPass($ePass);

 if(empty($eName)){
 	exit(0);
 }
 if($ePass != $ePassAgain){
 	die("The passwords do not match!" . mysqli_error($link));
 }
// attempt insert query execution
$sql = "INSERT INTO employee(employee_password, employee_name, employee_email, employee_phone, security_level) 
		VALUES ('$hashedPassword', '$eName', '$eEmail', '$ePhoneNumber', '$sec_lvl')";
$result = mysqli_query($link, $sql);
if(!$result){
    echo("Input data is fail" . mysqli_error($link));
}
 
// close connection
mysqli_close($link);
?>

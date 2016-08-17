<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with 'root' as password) */
$link = mysqli_connect("localhost", "root", "root", "Fraunhofer");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Change character set to utf8, for special characters like μ and °.
mysqli_set_charset($link,"utf8");

// Set file upload to max 100 MB. 
ini_set('upload_max_filesize','100M');

?>
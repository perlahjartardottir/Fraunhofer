<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with 'root' as password) */
$link = mysqli_connect("localhost", "root", "root", "Fraunhofer");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>
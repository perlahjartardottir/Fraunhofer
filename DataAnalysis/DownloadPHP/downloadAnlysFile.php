<?php
include '../connection.php';
session_start();

// Based on: http://stackoverflow.com/a/3802607 
if (isset($_GET['id'])) { 
    $file = $_GET['id'] ;
        if (file_exists($file) && is_readable($file))  { 
            // header('Content-type: application/pdf');  
            header("Content-Disposition: attachment; filename=\"$file\"");   
            readfile($file); 
        } 
    } else { 
    header("HTTP/1.0 404 Not Found"); 
    echo "<h1>Error 404: File Not Found: <br /><em>$file</em></h1>"; 
} 
?>
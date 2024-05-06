<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "expensetracker";

$conn = mysqli_connect($hostname, $username, $password, $database);
$errMsg = "";
error_reporting(E_ALL);
 ini_set("display_errors", 1);
 ini_set('display_startup_errors', 1);


function messageLog($err){
    error_log($err.PHP_EOL, 3, "error_log.txt");
    
}
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    messageLog("Database Connection successful");
    
}


?>
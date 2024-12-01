<?php
$servername = "localhost";
$username = "root"; 
$password = "";    
$dbname = "course_library";
$con = mysqli_connect($servername, $username, $password, $dbname);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

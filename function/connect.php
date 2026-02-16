<?php

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'BAbalola2024';

$dbName = 'app_admin';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

$conn_online = mysqli_connect("pgcollege-prod.cw1ucs7zc0yx.us-east-1.rds.amazonaws.com", "admissions", "ADmi#001", "pgsui_app_admin");


if (!$conn_online){
    die("Connection failed: " . mysqli_connect_error());
}

if ($conn->connect_error ) {
    die("Connection failed: " . $conn->connect_error);
}

?>

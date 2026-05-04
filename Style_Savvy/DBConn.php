<?php

$servername = (string) "localhost";
$username = (string) "root";
$password = (string) "";
$dbname = (string) "clothingstore";

$port = (int) 3308;

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname, $port);

echo $dbname;

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



?>
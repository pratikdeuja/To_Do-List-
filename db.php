<?php
// db.php

$servername = "localhost";
$username   = "root";       // Change as needed
$password   = "";           // Change as needed
$dbname     = "professional_todo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
$host = 'localhost'; 
$username = 'root'; 
$password = ''; // Leave empty if there's no password
$database = 'login_sample_db'; 

// Create a connection
$connection = mysqli_connect($host, $username, $password, $database);
// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}


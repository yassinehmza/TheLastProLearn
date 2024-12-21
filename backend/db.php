<?php
<<<<<<< HEAD
$host = '127.0.0.1'; 
=======
$host = 'localhost:3306'; 
>>>>>>> dd9e6bf69907c8ee074aa246e526b3fea90bba3f
$dbname = 'newlearn'; 
$username = 'root'; // Your MySQL username
$password = ''; // Your MySQL password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
include 'db.php'; 



// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');

$sql = "SELECT 
            courses.id, 
            courses.title, 
            courses.description, 
            courses.price, 
            courses.created_at,
            courses.updated_at
        FROM courses";

$result = $conn->query($sql);

$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'price' => $row['price'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at']
        ];
    }
    echo json_encode($courses); // Output the courses data as JSON
} else {
    echo json_encode(['message' => 'No courses found.']); // Handle empty results
}

$conn->close();
?>

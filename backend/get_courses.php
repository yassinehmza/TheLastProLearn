<?php
session_start();
include '../backend/db.php';

// Check if the user is logged in and has the 'professeur' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professeur') {
    // Redirect to login page if not a logged-in professor
    header('Location: ../login/login.php');
    exit();
}

// Get the professor's user ID from the session
$professor_id = $_SESSION['user_id'];

// SQL query to fetch courses for the logged-in professor
$sql = "SELECT id, title, description, price, created_at FROM courses WHERE professor_id = ?";
$courses = [];

// Prepare and execute the SQL statement
if ($stmt = $conn->prepare($sql)) {
    // Bind the professor's ID to the prepared statement
    $stmt->bind_param('i', $professor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch courses associated with the professor
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }

    // Close the statement after use
    $stmt->close();
} else {
    // In case the query preparation fails
    echo json_encode(['error' => 'Failed to retrieve courses.']);
    exit();
}

// Close the database connection
$conn->close();

// Set the content type to JSON and return the courses data
header('Content-Type: application/json');
echo json_encode($courses);
?>

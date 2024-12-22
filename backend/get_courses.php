<?php
// Include the database connection file
require_once '../backend/db.php';

// Start the session
session_start();
$user_id = $_SESSION['user_id'];

// Check if user_id is set
if (!isset($user_id)) {
    echo "Please log in to view your courses.";
    exit;
}

// Query to fetch user courses with status 'completed', including course title
$query = "SELECT c.title AS course_title, p.course_id, p.amount, p.created_at 
          FROM payments p
          JOIN courses c ON p.course_id = c.id
          WHERE p.user_id = ? AND p.status = 'completed'";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any courses were found
    if ($result->num_rows > 0) {
        echo "<h2>Your Paid Courses</h2>";
        echo "<div style='display: flex; flex-wrap: wrap; gap: 20px;'>"; // Flexbox container

        // Display courses
        while ($row = $result->fetch_assoc()) {
            // Format the amount by dividing by 100
            $formattedAmount = number_format($row['amount'] / 100, 2);

            echo "<div style='border: 1px solid #ccc; padding: 15px; border-radius: 8px; width: 300px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);'>";
            echo "<h3>" . htmlspecialchars($row['course_title']) . "</h3>";
            echo "<p><strong>Amount:</strong> $" . $formattedAmount . "</p>";
            echo "<p><strong>Payment Date:</strong> " . htmlspecialchars($row['created_at']) . "</p>";
            echo "<button style='background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;' onclick='playCourse(" . htmlspecialchars($row['course_id']) . ")'>Play Course</button>";
            echo "</div>";
        }

        echo "</div>";
    } else {
        echo "<p>No completed payments found for your account.</p>";
    }

    $stmt->close();
} else {
    echo "Error in preparing the query.";
}

// Close the database connection
$conn->close();
?>

<script>
// JavaScript function to handle 'Play Course' button click
function playCourse(courseId) {
    // Redirect to the course player page (you can customize this URL as needed)
    window.location.href = 'course_player.php?course_id=' + courseId;
}
</script>

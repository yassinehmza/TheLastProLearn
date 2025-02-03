<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch results for the logged-in user
$query = "SELECT qr.id, q.question, qr.score, qr.created_at 
          FROM quiz_results qr 
          JOIN quizzes q ON qr.quiz_id = q.id 
          WHERE qr.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$results = $stmt->get_result();

echo "<h1>Your Quiz Results</h1>";
while ($result = $results->fetch_assoc()) {
    echo "<div>
        <p><strong>Quiz:</strong> {$result['question']}</p>
        <p><strong>Score:</strong> {$result['score']}</p>
        <p><strong>Date:</strong> {$result['created_at']}</p>
    </div>";
}
?>

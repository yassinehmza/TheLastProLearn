<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch quizzes from the database
$query = "SELECT * FROM quizzes";
$result = $conn->query($query);

echo "<h1>Available Quizzes</h1>";
while ($quiz = $result->fetch_assoc()) {
    echo "<div>
        <h2>{$quiz['question']}</h2>
        <a href='take_quiz.php?quiz_id={$quiz['id']}'>Take Quiz</a>
    </div>";
}
?>

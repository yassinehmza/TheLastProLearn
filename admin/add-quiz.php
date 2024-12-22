<?php
// Include the database connection file
require_once '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize user input
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $options = json_encode($_POST['options']); // Expecting an array of options
    $correct_answer = mysqli_real_escape_string($conn, $_POST['correct_answer']);

    // Validate input
    if (empty($question) || empty($options) || empty($correct_answer)) {
        echo "All fields are required.";
        exit;
    }

    // Prepare SQL query to insert quiz data
    $sql = "INSERT INTO quizzes (question, options, correct_answer) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sss', $question, $options, $correct_answer);

        if (mysqli_stmt_execute($stmt)) {
            echo "Quiz added successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Quiz</title>
</head>
<body>
    <h1>Add Quiz</h1>
    <form method="POST" action="add-quiz.php">
        <label for="question">Question:</label><br>
        <textarea name="question" id="question" rows="4" cols="50" required></textarea><br><br>

        <label for="options">Options (comma-separated):</label><br>
        <input type="text" name="options[]" placeholder="Option 1" required><br>
        <input type="text" name="options[]" placeholder="Option 2" required><br>
        <input type="text" name="options[]" placeholder="Option 3"><br>
        <input type="text" name="options[]" placeholder="Option 4"><br><br>

        <label for="correct_answer">Correct Answer:</label><br>
        <input type="text" name="correct_answer" id="correct_answer" required><br><br>

        <button type="submit">Add Quiz</button>
    </form>
</body>
</html>

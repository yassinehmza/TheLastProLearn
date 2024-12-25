<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include '../backend/db.php';

// Initialize variables
$message = "";
$quiz_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$quiz_id) {
    header("Location: manage-quizzes.php"); // Redirect if no quiz ID is provided
    exit();
}

// Fetch the quiz data for the provided ID
$query = "SELECT * FROM quizzes WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $quiz_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$quiz = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$quiz) {
    header("Location: manage-quizzes.php"); // Redirect if no quiz is found
    exit();
}

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'];
    $options = $_POST['options'];
    $correct_answer = $_POST['correct_answer'];

    // Update the quiz in the database
    $update_query = "UPDATE quizzes SET question = ?, options = ?, correct_answer = ?, updated_at = NOW() WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sssi", $question, $options, $correct_answer, $quiz_id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Quiz updated successfully!";
        // Refresh quiz data
        $quiz['question'] = $question;
        $quiz['options'] = $options;
        $quiz['correct_answer'] = $correct_answer;
    } else {
        $message = "Failed to update quiz.";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Quiz</h1>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="question">Question</label>
            <textarea name="question" id="question" rows="3" required><?php echo htmlspecialchars($quiz['question']); ?></textarea>

            <label for="options">Options (comma-separated)</label>
            <textarea name="options" id="options" rows="3" required><?php echo htmlspecialchars($quiz['options']); ?></textarea>

            <label for="correct_answer">Correct Answer</label>
            <input type="text" name="correct_answer" id="correct_answer" value="<?php echo htmlspecialchars($quiz['correct_answer']); ?>" required>

            <button type="submit">Update Quiz</button>
        </form>
        <a class="back" href="manage-quizzes.php">Back to Manage Quizzes</a>
    </div>
</body>
</html>
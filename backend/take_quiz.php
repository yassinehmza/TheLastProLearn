<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$quiz_id = $_GET['quiz_id'];

// Fetch the quiz
$query = "SELECT * FROM quizzes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz = $stmt->get_result()->fetch_assoc();

$options = explode(',', $quiz['options']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_option = $_POST['option'];
    $score = ($selected_option == $quiz['correct_answer']) ? 1 : 0;

    // Store result in database
    $insertQuery = "INSERT INTO quiz_results (user_id, quiz_id, score) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iii", $user_id, $quiz_id, $score);
    $stmt->execute();

    header("Location: results.php");
    exit;
}
?>

<h1><?php echo $quiz['question']; ?></h1>
<form method="post">
    <?php foreach ($options as $option): ?>
        <div>
            <input type="radio" name="option" value="<?php echo $option; ?>" required>
            <label><?php echo $option; ?></label>
        </div>
    <?php endforeach; ?>
    <button type="submit">Submit</button>
</form>

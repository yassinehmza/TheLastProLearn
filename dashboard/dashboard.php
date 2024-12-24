<?php
// Start the session to access session variables
session_start();

// Include database connection
include '../backend/db.php'; // Replace with your actual database connection file
$user_id = $_SESSION['user_id'];

// Check if the user is logged in
if (!isset($user_id)) {
    // Redirect to login page if user is not logged in
    header("Location: ../login/login.html");
    exit;
}

// Fetch user details from the database using the session's user ID
 // Get the logged-in user's ID from the session
$query = "SELECT nom, prenom, email, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nom, $prenom, $email, $created_at);
$stmt->fetch();
$stmt->close();

// Fetch user's courses and calculate progress for each course
$courses_query = "SELECT id, title FROM courses";
$stmt = $conn->prepare($courses_query);
$stmt->execute();
$courses_result = $stmt->get_result();
$courses = [];
while ($row = $courses_result->fetch_assoc()) {
    $courses[] = $row;
}
$stmt->close();


// Fetch the progress for each course
$course_progress = [];
foreach ($courses as $course) {
    $course_id = $course['id'];

    // Calculate total items (videos + tests)
    $total_query = "SELECT 
        (SELECT COUNT(*) FROM videos WHERE course_id = ?) + 
        (SELECT COUNT(*) FROM tests WHERE course_id = ?) AS total_items";
    $stmt = $conn->prepare($total_query);
    $stmt->bind_param('ii', $course_id, $course_id);
    $stmt->execute();
    $stmt->bind_result($total_items);
    $stmt->fetch();
    $stmt->close();

    // Calculate completed items (videos + tests)
    $completed_query = "SELECT COUNT(*) FROM course_test_answers 
        WHERE user_id = ? AND test_id IN (
            SELECT id FROM videos WHERE course_id = ? 
            UNION 
            SELECT id FROM tests WHERE course_id = ?
        )";
    $stmt = $conn->prepare($completed_query);
    $stmt->bind_param('iii', $user_id, $course_id, $course_id);
    $stmt->execute();
    $stmt->bind_result($completed_items);
    $stmt->fetch();
    $stmt->close();

    // Calculate progress percentage
    $progress_percentage = $total_items > 0 ? ($completed_items / $total_items) * 100 : 0;

    // Store progress for each course
    $course_progress[$course_id] = $progress_percentage;
}

// Fetch quiz results taken by the user
$query = "SELECT q.quiz_id, q.score, q.created_at, t.question FROM quiz_results AS q
          JOIN tests AS t ON q.quiz_id = t.id
          WHERE q.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$quizzes = [];
while ($row = $result->fetch_assoc()) {
    $quizzes[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - E-Learning Platform</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your external CSS -->
    <style>
        /* Existing CSS... */
        .progress-bar { width: 100%; background: #eee; border-radius: 10px; margin-bottom: 20px; }
        .progress-bar div { height: 20px; background: #4CAF50; border-radius: 10px; }
        .card { border: 1px solid #ccc; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
        .section { margin-bottom: 30px; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="../backend/get_courses.php">Your Courses</a>
        <a href="../backend/mes_quiz.php">Your Quizzes</a>
        <a href="../backend/logout.php">Logout</a> <!-- Added Logout button -->
    </div>

    <div class="container">
        <!-- Left section: User Info -->
        <div class="card">
            <h2>Welcome, <?php echo htmlspecialchars($prenom ?? ''); ?> <?php echo htmlspecialchars($nom ?? ''); ?></h2>
            <p>Member since: <?php echo htmlspecialchars($created_at ?? ''); ?></p>
            <p>Email: <?php echo htmlspecialchars($email ?? ''); ?></p>
        </div>

        <!-- Middle section: Courses & Progress -->
        <div class="card">
            <h2>Your Courses</h2>
            <?php foreach ($courses as $course): ?>
                <div>
                    <p><?php echo htmlspecialchars($course['title']); ?></p>
                    <div class="progress-bar">
                        <div style="width: <?php echo $course_progress[$course['id']] ?? 0; ?>%"></div>
                    </div>
                    <p>Progress: <?php echo round($course_progress[$course['id']] ?? 0); ?>%</p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Right section: Quiz Status -->
        <div class="card">
            <h2>Your Quizzes</h2>
            <?php if (count($quizzes) > 0): ?>
                <?php foreach ($quizzes as $quiz): ?>
                    <div>
                        <p>Quiz: <?php echo htmlspecialchars($quiz['question']); ?></p>
                        <p>Score: <?php echo htmlspecialchars($quiz['score']); ?>%</p>
                        <p>Date Taken: <?php echo htmlspecialchars($quiz['created_at']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You haven't taken any quizzes yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 E-Learning Platform. All Rights Reserved.</p>
    </footer>
</body>
</html>

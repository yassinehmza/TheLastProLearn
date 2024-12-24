<?php
// Include the database connection file
require_once '../backend/db.php';

// Start the session
session_start();
$user_id = $_SESSION['user_id'];

// Check if user_id is set
if (!isset($user_id)) {
    echo "Please log in to access your courses.";
    exit;
}

// Get the course_id from the query parameter
if (!isset($_GET['course_id'])) {
    echo "Course not specified.";
    exit;
}

$course_id = intval($_GET['course_id']);

// Initialize variables to avoid undefined errors
$total_items = 0;
$completed_items = 0;

// Handle video completion logging
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['video_id'])) {
    $video_id = intval($_POST['video_id']);

    // Check if the video completion is already logged
    $check_query = "SELECT COUNT(*) FROM course_test_answers WHERE user_id = ? AND test_id = ? AND selected_option_id IS NULL";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param('ii', $user_id, $video_id);
    $stmt->execute();
    $stmt->bind_result($exists);
    $stmt->fetch();
    $stmt->close();

    if (!$exists) {
        // Log the video completion
        $insert_query = "INSERT INTO course_test_answers (test_id, user_id, selected_option_id, is_correct, created_at) VALUES (?, ?, NULL, NULL, NOW())";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('ii', $video_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
    exit;
}

// Handle test submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_id'])) {
    $test_id = intval($_POST['test_id']);
    $selected_option_id = intval($_POST['selected_option_id']);

    // Get the correctness of the selected option
    $correct_query = "SELECT is_correct FROM test_options WHERE id = ?";
    $stmt = $conn->prepare($correct_query);
    $stmt->bind_param('i', $selected_option_id);
    $stmt->execute();
    $stmt->bind_result($is_correct);
    $stmt->fetch();
    $stmt->close();

    // Log the test answer
    $insert_query = "INSERT INTO course_test_answers (test_id, user_id, selected_option_id, is_correct, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param('iiii', $test_id, $user_id, $selected_option_id, $is_correct);
    $stmt->execute();
    $stmt->close();
}

// Total items (videos + tests) in the course
$total_query = "SELECT 
    (SELECT COUNT(*) FROM videos WHERE course_id = ?) + 
    (SELECT COUNT(*) FROM tests WHERE course_id = ?) AS total_items";
$stmt = $conn->prepare($total_query);
$stmt->bind_param('ii', $course_id, $course_id);
$stmt->execute();
$stmt->bind_result($total_items);
$stmt->fetch();
$stmt->close();

// Completed items for the user
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

// Fetch videos
$videos_query = "SELECT id, title, video_file FROM videos WHERE course_id = ? ORDER BY id";
$stmt = $conn->prepare($videos_query);
$stmt->bind_param('i', $course_id);
$stmt->execute();
$videos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch tests
$tests_query = "SELECT id, question FROM tests WHERE course_id = ? ORDER BY id";
$stmt = $conn->prepare($tests_query);
$stmt->bind_param('i', $course_id);
$stmt->execute();
$tests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch test options
$test_options_query = "SELECT id, test_id, option_text FROM test_options WHERE test_id IN (SELECT id FROM tests WHERE course_id = ?)";
$stmt = $conn->prepare($test_options_query);
$stmt->bind_param('i', $course_id);
$stmt->execute();
$options_result = $stmt->get_result();
$test_options = [];
while ($row = $options_result->fetch_assoc()) {
    $test_options[$row['test_id']][] = $row;
}
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Progress</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .progress-bar { width: 100%; background: #eee; border-radius: 10px; margin-bottom: 20px; }
        .progress-bar div { height: 20px; background: #4CAF50; border-radius: 10px; width: <?php echo $progress_percentage; ?>%; }
        .section { margin-bottom: 30px; }
        .card { border: 1px solid #ccc; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h1>Course Progress</h1>
    <div class="progress-bar">
        <div></div>
    </div>
    <p>Progress: <?php echo round($progress_percentage); ?>%</p>

    <div class="section">
        <?php foreach ($videos as $video): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                <video controls onended="logVideoCompletion(<?php echo $video['id']; ?>)">
                    <source src="<?php echo htmlspecialchars($video['video_file']); ?>" type="video/mp4">
                </video>
            </div>
        <?php endforeach; ?>

        <?php foreach ($tests as $test): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($test['question']); ?></h3>
                <form method="POST">
                    <input type="hidden" name="test_id" value="<?php echo $test['id']; ?>">
                    <?php foreach ($test_options[$test['id']] as $option): ?>
                        <label>
                            <input type="radio" name="selected_option_id" value="<?php echo $option['id']; ?>">
                            <?php echo htmlspecialchars($option['option_text']); ?>
                        </label>
                    <?php endforeach; ?>
                    <button type="submit">Submit</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function logVideoCompletion(videoId) {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `video_id=${videoId}`
            });
        }
    </script>
</body>
</html>

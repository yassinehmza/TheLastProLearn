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

// Fetch videos and tests for the course
$videos_query = "SELECT id, title, video_file FROM videos WHERE course_id = ? ORDER BY id";
$tests_query = "SELECT id, question FROM tests WHERE course_id = ? ORDER BY id";

// Fetch videos
$videos = [];
if ($stmt = $conn->prepare($videos_query)) {
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $videos[] = $row;
    }

    $stmt->close();
}

// Fetch tests
$tests = [];
if ($stmt = $conn->prepare($tests_query)) {
    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $tests[] = $row;
    }

    $stmt->close();
}

// Interleave videos and tests
$content = [];
$max_items = max(count($videos), count($tests));

for ($i = 0; $i < $max_items; $i++) {
    if (isset($videos[$i])) {
        $content[] = ['type' => 'video', 'data' => $videos[$i]];
    }
    if (isset($tests[$i])) {
        $content[] = ['type' => 'test', 'data' => $tests[$i]];
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Player</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .section {
            margin-bottom: 30px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .card h3 {
            margin-top: 0;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #45a049;
        }
        video {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Course Player</h1>

    <div class="section">
        <?php if (!empty($content)): ?>
            <?php foreach ($content as $item): ?>
                <?php if ($item['type'] === 'video'): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($item['data']['title']); ?></h3>
                        <video controls>
                            <source src="<?php echo htmlspecialchars($item['data']['video_file']); ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                <?php elseif ($item['type'] === 'test'): ?>
                    <div class="card">
                        <h3>Question: <?php echo htmlspecialchars($item['data']['question']); ?></h3>
                        <a class="button" href="/take_test.php?test_id=<?php echo $item['data']['id']; ?>">Take Test</a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No content available for this course.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the admin is logged in
    if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
        die('You must be logged in as an admin to create a course.');
    }

    // General course information
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Save course to database
    $sql = "INSERT INTO courses (title, description, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssd', $title, $description, $price);
    $stmt->execute();
    $course_id = $stmt->insert_id;
    $stmt->close();

    // Handle multiple videos (each video with a title and file upload)
    if (isset($_POST['video_title']) && isset($_FILES['video'])) {
        $video_titles = $_POST['video_title']; // Array of video titles
        $videos = $_FILES['video']; // Array of video files

        foreach ($video_titles as $index => $video_title) {
            $video_path = '';
            $video = $videos['tmp_name'][$index];
            $video_name = $videos['name'][$index];
            $video_type = $videos['type'][$index];
            $video_size = $videos['size'][$index];

            // Video upload validation
            $allowed_types = ['video/mp4', 'video/avi', 'video/mov']; // Allowed file types
            $upload_dir = __DIR__ . '/uploads/videos/'; // Path to save video files
            $max_size = 50 * 1024 * 1024; // Max file size: 50MB

            if (in_array($video_type, $allowed_types) && $video_size <= $max_size) {
                $filename = time() . '_' . basename($video_name); // Unique filename
                $target_file = $upload_dir . $filename;

                // Create upload directory if it doesn't exist
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Move uploaded video to the target directory
                if (move_uploaded_file($video, $target_file)) {
                    $video_path = 'uploads/videos/' . $filename; // Save relative path to database

                    // Insert video record into the database
                    $sql = "INSERT INTO videos (course_id, title, video_file) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('iss', $course_id, $video_title, $video_path);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    die('Failed to upload the video.');
                }
            } else {
                die('Invalid video format or file size too large.');
            }
        }
    }

    // Save test questions and options
    if (isset($_POST['test_question'])) {
        $questions = $_POST['test_question'];
        $options = $_POST['test_options'];
        $correct_answers = $_POST['correct_answer'];

        foreach ($questions as $index => $question) {
            // Insert question into tests table
            $sql = "INSERT INTO tests (course_id, question) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('is', $course_id, $question);
            $stmt->execute();
            $test_id = $stmt->insert_id;
            $stmt->close();

            // Insert options for each question
            foreach ($options[$index] as $option_index => $option) {
                $is_correct = ($option_index == $correct_answers[$index]) ? 1 : 0;
                $sql = "INSERT INTO test_options (test_id, option_text, is_correct) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('isi', $test_id, $option, $is_correct);
                $stmt->execute();
            }
        }
    }

    echo 'Course and videos have been successfully added!';
}

?>

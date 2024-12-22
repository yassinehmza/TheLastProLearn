<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the admin is logged in
    if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
        die('You must be logged in as an admin to manage a course.');
    }

    // General course information
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $course_id = isset($_POST['course_id']) ? $_POST['course_id'] : null; // Course ID for editing

    if ($course_id) {
        // Update existing course
        $sql = "UPDATE courses SET title = ?, description = ?, price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssdi', $title, $description, $price, $course_id);
        $stmt->execute();
        $stmt->close();

        echo 'Course information has been successfully updated!';
    } else {
        // Add a new course
        $sql = "INSERT INTO courses (title, description, price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssd', $title, $description, $price);
        $stmt->execute();
        $course_id = $stmt->insert_id;
        $stmt->close();

        echo 'Course has been successfully added!';
    }

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
            $allowed_types = ['video/mp4', 'video/avi', 'video/mov'];
            $upload_dir = __DIR__ . '/uploads/videos/';
            $max_size = 50 * 1024 * 1024;

            if (in_array($video_type, $allowed_types) && $video_size <= $max_size) {
                $filename = time() . '_' . basename($video_name);
                $target_file = $upload_dir . $filename;

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                if (move_uploaded_file($video, $target_file)) {
                    $video_path = 'uploads/videos/' . $filename;

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

    if (!$course_id) {
        die('Invalid course ID.');
    }
}
?>

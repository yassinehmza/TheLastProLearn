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
<button > <a href="../admin/manage-courses.php"> Manage Couses</a></button>
<style>
    /* Style général */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 20px;
}

/* Bouton de navigation */
button {
    background: linear-gradient(90deg, #0070f3, #0053c1);
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    padding: 12px 20px;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    margin-top: 20px;
}

button:hover {
    background: linear-gradient(90deg, #0053c1, #0070f3);
    transform: scale(1.05);
}

button:active {
    transform: scale(0.95);
}

/* Lien dans le bouton */
button a {
    text-decoration: none;
    color: #fff;
}

button a:hover {
    text-decoration: underline;
}

/* Conteneur principal pour messages */
.message {
    max-width: 800px;
    margin: 0 auto;
    padding: 15px 20px;
    background-color: #ffffff;
    border-radius: 6px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    font-size: 18px;
    font-weight: 500;
}

/* Réussite */
.message.success {
    border-left: 6px solid #28a745;
    color: #155724;
}

/* Erreur */
.message.error {
    border-left: 6px solid #dc3545;
    color: #721c24;
}

/* Mise en page des formulaires */
form {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    max-width: 800px;
}

input[type="text"], 
input[type="number"], 
textarea, 
select {
    width: calc(100% - 20px);
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus,
select:focus {
    border-color: #0070f3;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 112, 243, 0.2);
}

/* Responsivité */
@media (max-width: 768px) {
    body {
        padding: 10px;
    }

    form {
        padding: 15px;
    }

    button {
        font-size: 14px;
        padding: 10px 16px;
    }
}

</style>

<?php
include '../backend/db.php';

// Fetch course details
$course_id = $_GET['id'];
$sql = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

// Fetch videos for the course
$sql_videos = "SELECT * FROM videos WHERE course_id = ?";
$stmt_videos = $conn->prepare($sql_videos);
$stmt_videos->bind_param('i', $course_id);
$stmt_videos->execute();
$videos_result = $stmt_videos->get_result();
$videos = $videos_result->fetch_all(MYSQLI_ASSOC);

// Fetch tests for the course
$sql_tests = "SELECT * FROM tests WHERE course_id = ?";
$stmt_tests = $conn->prepare($sql_tests);
$stmt_tests->bind_param('i', $course_id);
$stmt_tests->execute();
$tests_result = $stmt_tests->get_result();
$tests = $tests_result->fetch_all(MYSQLI_ASSOC);
?>

<!-- Form for editing course -->
<<<<<<< HEAD
 <br><br>
=======
>>>>>>> cff39cb5e7fe6449dafd2bf1e627f3961423247c
<form action="../backend/save_cours.php" method="POST">
    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
    <input type="text" name="title" value="<?php echo $course['title']; ?>" placeholder="Course Title">
    <textarea name="description" placeholder="Course Description"><?php echo $course['description']; ?></textarea>
    <input type="number" name="price" value="<?php echo $course['price']; ?>" placeholder="Course Price">
    
    <h3>Videos</h3>
    <?php foreach ($videos as $index => $video): ?>
        <div>
            <input type="hidden" name="videos[<?php echo $index; ?>][id]" value="<?php echo $video['id']; ?>">
            <input type="text" name="videos[<?php echo $index; ?>][title]" value="<?php echo $video['title']; ?>" placeholder="Video Title">
            <input type="text" name="videos[<?php echo $index; ?>][video_file]" value="<?php echo $video['video_file']; ?>" placeholder="Video File URL">
        </div>
    <?php endforeach; ?>
    
    <h3>Tests</h3>
    <?php foreach ($tests as $test_index => $test): ?>
        <div>
            <input type="hidden" name="tests[<?php echo $test_index; ?>][id]" value="<?php echo $test['id']; ?>">
            <input type="text" name="tests[<?php echo $test_index; ?>][question]" value="<?php echo $test['question']; ?>" placeholder="Test Question">
            
            <h4>Options</h4>
            <?php
            $sql_options = "SELECT * FROM test_options WHERE test_id = ?";
            $stmt_options = $conn->prepare($sql_options);
            $stmt_options->bind_param('i', $test['id']);
            $stmt_options->execute();
            $options_result = $stmt_options->get_result();
            $options = $options_result->fetch_all(MYSQLI_ASSOC);
            ?>
            <?php foreach ($options as $option_index => $option): ?>
                <div>
                    <input type="hidden" name="tests[<?php echo $test_index; ?>][options][<?php echo $option_index; ?>][id]" value="<?php echo $option['id']; ?>">
                    <input type="text" name="tests[<?php echo $test_index; ?>][options][<?php echo $option_index; ?>][option_text]" value="<?php echo $option['option_text']; ?>" placeholder="Option Text">
                    <label>
                        <input type="checkbox" name="tests[<?php echo $test_index; ?>][options][<?php echo $option_index; ?>][is_correct]" <?php echo $option['is_correct'] ? 'checked' : ''; ?>> Correct
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <button type="submit">Save Changes</button>
</form>
<style>
    /* Style général */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f4f4f4;
    color: #333;
}

/* Conteneur principal */
form {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Titres */
h3 {
    font-size: 18px;
    color: #0053c1;
    border-bottom: 2px solid #f4f4f4;
    padding-bottom: 5px;
    margin-bottom: 10px;
}

h4 {
    font-size: 16px;
    color: #0070f3;
    margin-top: 10px;
}

/* Champs de formulaire */
input[type="text"],
input[type="number"],
textarea {
    width: 100%;
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
textarea:focus {
    border-color: #0070f3;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 112, 243, 0.2);
}

/* Conteneurs pour les vidéos et tests */
div {
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background-color: #f9f9f9;
}

/* Boutons */
button {
    display: inline-block;
    padding: 12px 24px;
    font-size: 16px;
    font-weight: bold;
    color: #fff;
    background: linear-gradient(90deg, #0070f3, #0053c1);
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
    margin-top: 10px;
}

button:hover {
    background: linear-gradient(90deg, #0053c1, #0070f3);
    transform: scale(1.05);
}

button:active {
    transform: scale(0.98);
}

/* Labels pour les options */
label {
    font-size: 14px;
    margin-left: 10px;
    color: #333;
}

/* Cases à cocher */
input[type="checkbox"] {
    margin-right: 5px;
}

/* Responsivité */
@media (max-width: 768px) {
    form {
        padding: 15px;
    }

    input[type="text"],
    input[type="number"],
    textarea {
        font-size: 14px;
    }

    button {
        font-size: 14px;
    }
}

</style>
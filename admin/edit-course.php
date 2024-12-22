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
<form action="save_course.php" method="POST">
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

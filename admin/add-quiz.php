<?php
// Include the database connection file
require_once '../backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize user input
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $options = json_encode($_POST['options']); // Expecting an array of options
    $correct_answer = mysqli_real_escape_string($conn, $_POST['correct_answer']);

    // Validate input
    if (empty($question) || empty($options) || empty($correct_answer)) {
        echo "All fields are required.";
        exit;
    }

    // Prepare SQL query to insert quiz data
    $sql = "INSERT INTO quizzes (question, options, correct_answer) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sss', $question, $options, $correct_answer);

        if (mysqli_stmt_execute($stmt)) {
            echo "Quiz added successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Quiz</title>
</head>
<body>
    <h1>Add Quiz</h1>
    <form method="POST" action="add-quiz.php">
        <label for="question">Question:</label><br>
        <textarea name="question" id="question" rows="4" cols="50" required></textarea><br><br>

        <label for="options">Options (comma-separated):</label><br>
        <input type="text" name="options[]" placeholder="Option 1" required><br>
        <input type="text" name="options[]" placeholder="Option 2" required><br>
        <input type="text" name="options[]" placeholder="Option 3"><br>
        <input type="text" name="options[]" placeholder="Option 4"><br><br>

        <label for="correct_answer">Correct Answer:</label><br>
        <input type="text" name="correct_answer" id="correct_answer" required><br><br>

        <button type="submit">Add Quiz</button>
    </form>
</body>
<style>
    /* Styles globaux */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Conteneur principal */
form {
    background: #fff;
    padding: 20px 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 100%;
}

/* Titres */
h1 {
    margin-right: 150px;
    font-size: 2rem;
    margin-bottom: 20px;
    text-align: center;
    color: #333;
}

/* Champs du formulaire */
label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
    color: #555;
}

input[type="text"],
textarea {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 1rem;
    color: #333;
}

textarea {
    resize: none;
}

input[type="text"]:focus,
textarea:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
}

/* Bouton */
button {
    width: 100%;
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

button:hover {
    background-color: #0056b3;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Placeholder pour les options */
input[placeholder] {
    font-style: italic;
    color: #888;
}

/* Responsivité */
@media (max-width: 600px) {
    form {
        padding: 15px 20px;
    }

    h1 {
        font-size: 1.5rem;
    }

    button {
        font-size: 0.9rem;
    }
}

</style>
</html>

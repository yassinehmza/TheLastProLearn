<?php
// Start the session to access session variables
session_start();

// Include database connection
include '../backend/db.php'; // Replace with your actual database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: ../login.php");
    exit;
}

// Fetch user details from the database using the session's user ID
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID from the session
$query = "SELECT nom, prenom, email, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nom, $prenom, $email, $created_at);
$stmt->fetch();
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        /* Navbar Styling */
        .navbar {
            background-color: #333;
            display: flex;
            justify-content: space-around;
            padding: 10px;
            color: white;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 5px;
        }
        .navbar a:hover {
            background-color: #575757;
        }
        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }
        .card {
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 10px;
            width: 30%;
            border-radius: 8px;
        }
        .card h2 {
            margin-bottom: 10px;
        }
        .card p {
            font-size: 14px;
            margin: 10px 0;
        }
        .progress-bar {
            width: 100%;
            height: 10px;
            background-color: #eee;
            border-radius: 5px;
        }
        .progress-bar div {
            height: 100%;
            background-color: #4caf50;
            border-radius: 5px;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
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
            <div>
                <p>Course 1: Web Development Basics</p>
                <div class="progress-bar">
                    <div style="width: 70%"></div>
                </div>
                <p>Progress: 70%</p>
            </div>
            <div>
                <p>Course 2: Advanced JavaScript</p>
                <div class="progress-bar">
                    <div style="width: 40%"></div>
                </div>
                <p>Progress: 40%</p>
            </div>
        </div>

        <!-- Right section: Quiz Status -->
        <div class="card">
            <h2>Your Quizzes</h2>
            <div>
                <p>Quiz 1: HTML & CSS Basics</p>
                <p>Status: Completed</p>
            </div>
            <div>
                <p>Quiz 2: JavaScript Fundamentals</p>
                <p>Status: In Progress</p>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 E-Learning Platform. All Rights Reserved.</p>
    </footer>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Admin Dashboard</h1>
            <nav>
                <ul>
                    <li><a href="/admin/manage-courses.php">Manage Courses</a></li>
                    <li><a href="/admin/manage-quizzes.php">Manage Quizzes</a></li>
                    <li><a href="/admin//manage-users.php">Manage Users</a></li>
                    <li><a href="../backend/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <section>
            <h2>Welcome to the Admin Panel</h2>
            <p>Here you can manage courses, quizzes, and users for your e-learning site.</p>
        </section>
    </div>
</body>
</html>

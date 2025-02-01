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
    <style>
         /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            color: #333;
            background-image: url("../images/extra/1.png");
        
        }

    header {
        background-color: #ffffff;
        color: #333333; 
        padding: 15px 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5); 
        position: fixed;
        top: 0;
        width: 100%;
        height: 40px;
        z-index: 1050;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    nav ul {
        list-style: none;
        display: flex;
        gap: 5px;
    }

    nav a {
        text-decoration: none;
        color: #333333; 
        font-weight: 500;
        padding: 10px 20px; 
        margin-right:45px; 
        border-radius: 8px; 
        transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    }

    nav a:hover {
        color: #FA4B37;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

     section {
            background: whitesmoke;
            padding: 100px;
            border-radius: 10px;
            width: 400px;
            margin-top: 170px;
            text-align: center;
            margin-left: 870px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
    }

    section h2 {
        margin-top: 0;
        font-size: 2em;
        color: red;
    }

    section p {
            line-height: 1.6;
    }
    </style>
</head>
<body>
        <header>
            <h1>Admin Dashboard</h1>
            <nav>
                <ul>
                    <li><a href="/admin/manage-courses.php">Manage Courses</a></li>
                    <li><a href="/admin/manage-quizzes.php">Manage Quizzes</a></li>
                    <li><a href="/admin//manage-users.php">Manage Users</a></li>
                    <li><a href="/admin/add-admin.php">add admin</a></li>
                    <li><a href="/backend/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <section>
            <h2>Welcome to the Admin Panel</h2>
            <p>Here you can manage courses, quizzes, and users for your e-learning site.</p>
        </section>
    
</body>
</html>

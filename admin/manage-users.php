<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Example database connection
include '../backend/db.php';

$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: green;
            color: #333;
        }

        .container {
            max-width: 1700px;
            
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 50px 90px;
            margin-bottom: 50px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 2.5em;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 10px 0 0;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        nav ul li a {
            color: black;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 30px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        nav ul li a:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        section {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            padding: 10px;
        }

        section h2 {
            margin-top: 0;
            font-size: 2em;
            color: #333;
        }

        section table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        section table thead th {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            text-align: left;
            padding: 10px;
        }

        section table tbody td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        section table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        section table tbody tr:hover {
            background-color: #f1f1f1;
        }

        section table tbody td a {
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
        }

        section table tbody td a[href*="edit-user.php"] {
            background-color: #28a745; /* Vert */
        }

        section table tbody td a[href*="delete-user.php"] {
            background-color: #dc3545; /* Rouge */
        }

        section a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            color: #fff;
            background: linear-gradient(to right, #FA4B37, #DF2771);
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        section a:hover {
            background: linear-gradient(to right, #DF2771, #FA4B37);
        }

    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Manage Users</h1>
            <nav>
                <br>
                <ul>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="manage-courses.php">Manage Courses</a></li>
                    <li><a href="manage-quizzes.php">Manage Quizzes</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <section>
            <h2>User List</h2>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['prenom']; ?></td>
                            <td><?php echo $row['nom']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                                <a href="edit-user.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                <a href="delete-user.php?id=<?php echo $row['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>

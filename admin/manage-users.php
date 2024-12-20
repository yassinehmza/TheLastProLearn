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
            background-color: white;
            color: #333;
        }

        .container {
            
            
        }
        header {
        background-color: #ffffff;
        color: #333333; 
        padding: 15px 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
        position: fixed;
        top: 0;
        width: 100%;
        height: 40px;
        z-index: 1050;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        max-width: 1700px;
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
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(1, 1, 1, 1);
        margin-top: 150px;
        max-width: 1200px;
        margin-left: 150px;
        padding: 20px;
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
            background-color: antiquewhite;
            color: black;
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

        
        /* Action Links */

        .edit{
            color: green;
        }
        .Delete {
            color: red;
        }
        .ADD {
            color: #333333;
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
                                <a class="edit" href="edit-user.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                <a class="Delete" href="delete-user.php?id=<?php echo $row['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>

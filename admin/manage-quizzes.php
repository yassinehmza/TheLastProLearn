<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include '../backend/db.php';

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_quiz_id'])) {
    $quiz_id = intval($_POST['delete_quiz_id']); // Sanitize input
    $delete_query = "DELETE FROM quizzes WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $quiz_id);
    if (mysqli_stmt_execute($stmt)) {
        $message = "Quiz deleted successfully!";
    } else {
        $message = "Failed to delete quiz.";
    }
    mysqli_stmt_close($stmt);
}

// Fetch quizzes
$query = "SELECT * FROM quizzes";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Quizzes</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
          /* Styles globaux */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
}

/* En-tête */
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

header nav {
    display: flex;
    align-items: center;
    margin-right: 120px;
}

header nav ul {
    list-style: none;
    display: flex;
    gap: 15px;
    margin: 0;
    padding: 0;
}

header nav a {
    text-decoration: none;
    color: #333333; 
    font-weight: 500;
    padding: 10px 20px; 
    border-radius: 8px; 
    transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
}

header nav a:hover {
    color: #FA4B37;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Section principale */
section {
    margin-top: 80px;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin: 80px auto 20px; /* Ajustement pour l'espace sous l'en-tête */
    max-width: 1200px;
}

section h2 {
    margin-top: 0;
    font-size: 2em;
    color: #333;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
}

/* Tableau */
section table {
    width: 95%;
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

/* Liens d'action */
.edit {
    color: green;
    font-weight: bold;
}

.delete {
    color: red;
    font-weight: bold;
    text-decoration: none;
}

.add {
    color: green;
    text-decoration: none;
    font-weight: bold;
}

#add {
    padding: 10px;
    background-color: antiquewhite;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

#add:hover {
    background-color: #e6d3c7;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Responsivité */
@media (max-width: 768px) {
    header nav ul {
        flex-direction: column;
        gap: 10px;
    }

    section {
        padding: 15px;
        margin-top: 100px;
    }

    section table thead, 
    section table tbody {
        font-size: 14px;
    }
}
.edit {
            color: green;
        }
        .Delete {
            color: red;
        }

        .ADD {
            color: #333333;
            text-decoration: none;
        }
        #add {
            padding: 10px;
            border-radius: 20px;
        }

    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Manage Quizzes</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="manage-courses.php">Manage Courses</a></li>
                    <li><a href="manage-users.php">Manage Users</a></li>
                    <li><a href="../backend/logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>
        <br><br><br><br>
        <section>
            <div>
                <h2>Quiz List</h2>
                <?php if (isset($message)) : ?>
                    <p><?php echo $message; ?></p>
                <?php endif; ?>
                <table>
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Options</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?php echo $row['question']; ?></td>
                                <td><?php echo $row['options']; ?></td>
                                <td>
                                    <a class="add" href="edit-quiz.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="delete_quiz_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="delete" onclick="return confirm('Are you sure you want to delete this quiz?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a class="ADD" id="add" href="add-quiz.php">Add New Quiz</a>
            </div>
        </section>
    </div>
</body>
</html>

<?php
session_start();
include '../backend/db.php'; // Your database connection file

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to check if the admin exists in the database
    $query = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Admin found, set session and redirect to admin dashboard
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit();
    } else {
        // If admin is not found, show error message
        $error_message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Global Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-image: url("../images/extra/1.png");
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    background-repeat: no-repeat;
    height: 100vh;
    
}

/* Login Container */
.login-container {
    background: #fff;
    padding: 40px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
    width: 100%;
    max-width: 400px;
    text-align: center;
    height: 400px;
    margin-left: 800px;
}

/* Title */
.login-container h2 {
    margin: 0 0 20px;
    color: #333;
    font-size: 1.8em;
}

/* Error Message */
.login-container p {
    margin-bottom: 15px;
}

/* Form Group */
.form-group {
    margin-bottom: 20px;
    text-align: left;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 0.9em;
    color: #555;
}

.form-group input {
    width: 100%;
    padding: 10px;
    font-size: 1em;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

.form-group input:focus {
    border-color: #6a11cb;
    outline: none;
}

/* Button */
button[type="submit"] {
    background: linear-gradient(to right, #6a11cb, #2575fc);
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 10px 15px;
    font-size: 1em;
    cursor: pointer;
    width: 100%;
    transition: background 0.3s ease, transform 0.2s ease;
}

button[type="submit"]:hover {
    background: linear-gradient(to right, #2575fc, #6a11cb);
    transform: scale(1.05);
}

/* Responsive */
@media (max-width: 768px) {
    .login-container {
        padding: 30px 20px;
    }
}

    </style>
</head>
<body>
    
    <div class="login-container">
        <h2>Admin Login</h2>

        <?php
        // Display error message if login fails
        if (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="submit">Login</button>
        </form>
    </div>
</body>
</html>

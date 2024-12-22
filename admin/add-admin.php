<?php
// Include database connection
include '../backend/db.php';

// Initialize variables
$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check for errors before inserting into database
    if (empty($username_err) && empty($password_err)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the SQL statement
        $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                echo "Admin added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
</head>
<body>
    <h2>Add Admin</h2>
    <form action="add-admin.php" method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>">
            <span><?php echo $username_err; ?></span>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <span><?php echo $password_err; ?></span>
        </div>
        <div>
            <button type="submit">Add Admin</button>
        </div>
    </form>
</body>
</html>

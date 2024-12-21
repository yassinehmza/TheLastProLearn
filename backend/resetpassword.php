<?php
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['password'];

    // Debug: Check the token
    var_dump($token);

    // Verify the token (only unused tokens that are not expired)
    $query = "SELECT * FROM password_resets WHERE token = ? AND expiry > NOW() AND used = 0 ORDER BY expiry DESC LIMIT 1";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Debug: Check the `used` and `expiry` values
        echo "Used: " . $row['used'] . "<br>";
        echo "Expiry: " . $row['expiry'] . "<br>";

        // Update the user's password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $updateQuery = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();

        // Mark the token as used
        $markUsedQuery = "UPDATE password_resets SET used = 1 WHERE token = ?";
        $stmt = $conn->prepare($markUsedQuery);
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo "Votre mot de passe a été réinitialisé avec succès.";
    } else {
        // Debug: Check if no rows matched
        echo "Le lien de réinitialisation est invalide ou a expiré.";
    }
}
?>
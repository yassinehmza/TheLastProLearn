<?php
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['password'];

    // Verify the token (fetch the most recent token for this request)
    $query = "SELECT * FROM password_resets WHERE token = ? AND expiry > NOW() ORDER BY expiry DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Update the user's password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $updateQuery = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();

        // Delete all tokens for this email to prevent re-use
        $deleteQuery = "DELETE FROM password_resets WHERE email = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        echo "Votre mot de passe a été réinitialisé avec succès.";
    } else {
        echo "Le lien de réinitialisation est invalide ou a expiré.";
    }
}
?>

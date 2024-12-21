<?php
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = filter_var($_POST['token'], FILTER_SANITIZE_NUMBER_INT); // Ensure the token is numeric
    $newPassword = $_POST['password'];

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

        echo json_encode(['success' => true, 'message' => 'Votre mot de passe a été réinitialisé avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Le lien de réinitialisation est invalide ou a expiré.']);
    }
}
?>

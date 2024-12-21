<?php
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging: Log the raw POST data to check the token
    error_log("Raw POST data: " . print_r($_POST, true));

    // Get and sanitize inputs
    $token = filter_var($_POST['token'], FILTER_SANITIZE_NUMBER_INT); // Ensure the token is numeric
    $newPassword = trim($_POST['password']);

    // Validate the new password (example: minimum 8 characters)
    if (strlen($newPassword) < 8) {
        echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 8 caractères.']);
        exit;
    }

    // Verify the token (only unused tokens that are not expired)
    $query = "SELECT * FROM password_resets WHERE token = ? AND expiry > NOW() AND used = 0 ORDER BY expiry DESC LIMIT 1";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Une erreur s\'est produite lors de la préparation de la requête.']);
        exit;
    }

    // Use "i" for integer binding for the token
    $stmt->bind_param("i", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Update the user's password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $updateQuery = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($updateQuery);

        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Une erreur s\'est produite lors de la mise à jour du mot de passe.']);
            exit;
        }

        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();

        // Mark the token as used
        $markUsedQuery = "UPDATE password_resets SET used = 1 WHERE token = ?";
        $stmt = $conn->prepare($markUsedQuery);

        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Une erreur s\'est produite lors de la mise à jour du jeton.']);
            exit;
        }

        // Use "i" for integer binding for the token
        $stmt->bind_param("i", $token);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Votre mot de passe a été réinitialisé avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Le lien de réinitialisation est invalide ou a expiré.']);
    }
}
?>

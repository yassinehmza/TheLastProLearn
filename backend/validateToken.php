<?php
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging: Log the raw POST data to check the token
    error_log("Raw POST data: " . print_r($_POST, true));

    // Get and sanitize the token input
    $token = filter_var($_POST['token'], FILTER_SANITIZE_NUMBER_INT); // Ensure the token is numeric

    if (empty($token)) {
        echo json_encode(['success' => false, 'message' => 'Token manquant.']);
        exit;
    }

    // Verify the token (check if it exists, is not expired, and is not used)
    $query = "SELECT * FROM password_resets WHERE token = ? AND expiry > NOW() AND used = 0 LIMIT 1";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la préparation de la requête.']);
        exit;
    }

    // Bind token parameter
    $stmt->bind_param("i", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid
        echo json_encode(['success' => true, 'message' => 'Token valide. Vous pouvez maintenant réinitialiser votre mot de passe.']);
    } else {
        // Token is invalid or expired
        echo json_encode(['success' => false, 'message' => 'Le lien de réinitialisation est invalide ou a expiré.']);
    }
}
?>

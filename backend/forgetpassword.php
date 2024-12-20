<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Store token in `password_resets` table
        $insertQuery = "INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sss", $email, $token, $expiry);
        $stmt->execute();

        // Send reset email
        $resetLink = "http://localhost/your_project_directory/resetpassword.php?token=" . $token;
        $subject = "Réinitialisation de votre mot de passe";
        $message = "Cliquez sur le lien suivant pour réinitialiser votre mot de passe : " . $resetLink;
        $headers = "From: noreply@yourwebsite.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "Un lien de réinitialisation a été envoyé à votre adresse e-mail.";
        } else {
            echo "Erreur lors de l'envoi de l'e-mail.";
        }
    } else {
        echo "Aucun compte trouvé avec cet e-mail.";
    }
}
?>

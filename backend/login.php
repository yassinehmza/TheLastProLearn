<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db.php';

    // Get user inputs
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL query to fetch user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameter and execute query
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['role'] = $user['role'];

                // Redirect to a common dashboard
                header('Location: ../index.php');
                exit();
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Aucun utilisateur trouvé avec cet e-mail.";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../backend/db.php'; // Assurez-vous que le chemin vers db.php est correct

    // Sanitize and validate input data
    $prenom = htmlspecialchars($_POST['prenom']);
    $nom = htmlspecialchars($_POST['nom']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Validate name fields to allow only letters
    if (!preg_match("/^[a-zA-Z]+$/", $prenom)) {
        echo "<script>alert('Le prénom ne doit contenir que des lettres (sans espaces ni caractères spéciaux).');</script>";
        exit();
    }
    if (!preg_match("/^[a-zA-Z]+$/", $nom)) {
        echo "<script>alert('Le nom ne doit contenir que des lettres (sans espaces ni caractères spéciaux).');</script>";
        exit();
    }

    // Validate password strength
    if (
        strlen($password) < 8 ||
        !preg_match("/[A-Z]/", $password) ||
        !preg_match("/[a-z]/", $password) ||
        !preg_match("/[0-9]/", $password) ||
        !preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $password)
    ) {
        echo "<script>alert('Le mot de passe doit contenir au moins 8 caractères, incluant au moins une majuscule, une minuscule, un chiffre et un caractère spécial.');</script>";
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Adresse email invalide.');</script>";
        exit();
    }

    // Check if the password and confirm password match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Les mots de passe ne correspondent pas.');</script>";
        exit();
    }

    // Hash the password for secure storage
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists in the database
    $checkEmailSql = "SELECT email FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($checkEmailSql)) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('Cet e-mail est déjà utilisé. Veuillez en choisir un autre.');</script>";
            $stmt->close();
            exit();
        }
        $stmt->close();
    }

    // Set the role to "etudiant"
    $role = 'etudiant';

    // Prepare SQL query to insert the new user into the database
    $sql = "INSERT INTO users (role, prenom, nom, email, password) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param('sssss', $role, $prenom, $nom, $email, $passwordHash);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Inscription réussie!'); window.location.href = '../login/login.html';</script>";
        } else {
            echo "<script>alert('Erreur d\\'inscription: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Erreur de préparation de la requête: " . $conn->error . "');</script>";
    }

    // Close the database connection
    $conn->close();
}
?>

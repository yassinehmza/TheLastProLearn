<?php
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
require 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if email exists in database
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Generate secure random token
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', time() + (15 * 60));
            
            // Store token in database
            $insertQuery = "INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sss", $email, $token, $expiry);
            $stmt->execute();
            
            $mail = new PHPMailer(true);
            try {
                // Set SMTP server to MailHog
                $mail->isSMTP();
                $mail->Host = '127.0.0.1'; // MailHog SMTP server
                $mail->Port = 1025; // Default MailHog SMTP port
                $mail->SMTPAuth = false; // No authentication
                
                // Recipients
                $mail->setFrom('hi@demomailtrap.com', 'Your Website');
                $mail->addAddress($email);
                
                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                
                // Use token instead of email in reset link
                $resetLink = "http://localhost:3000/pages/resetpassword.html?token=" . urlencode($token);
                
                $mail->Body = '
                    <p>Hello,</p>
                    <p>You have requested to reset your password. Click the link below to proceed:</p>
                    <p><a href="' . $resetLink . '">' . $resetLink . '</a></p>
                    <p>This link will expire in 1 hour.</p>
                    <p>If you did not request this password reset, please ignore this email.</p>
                ';
                
                $mail->send();
                echo 'A password reset link has been sent to your email address.';
            } catch (Exception $e) {
                echo  'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
            }
        } else {
            // Don't reveal if email exists or not (security best practice)
            echo json_encode(['success' => true, 'message' => 'If your email exists in our system, you will receive a password reset link.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    }
}
?>
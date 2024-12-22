<?php
// checkout.php
session_start(); // Start the session to get the logged-in user's ID
require '../vendor/autoload.php'; // Ensure Stripe is installed with Composer
require 'db.php'; // Ensure this includes your DB connection

\Stripe\Stripe::setApiKey('sk_test_51QYZ38HTWnHCirvr6jj9B2dkaaapN9u6AQiHexDOUumKcOjZVEGQbhHN7bJDwIb6gfM2MBm8ngrZ97gjvYx5847q00al69ulEI');

// Get the POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    // Return an error if the JSON is not valid
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$payment_method_id = isset($input['payment_method']) ? $input['payment_method'] : '';
$course_id = isset($input['course_id']) ? (int)$input['course_id'] : 0;

// Ensure the user is logged in, otherwise return an error
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID from the session

// Validate the payment method and course ID
if (!$payment_method_id || !$course_id || !$user_id) {
    echo json_encode(['error' => 'Missing payment_method, course_id, or user_id']);
    exit;
}

// Fetch course details to get the price
$query = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $course = $result->fetch_assoc();
} else {
    echo json_encode(['error' => 'Course not found']);
    exit;
}

// Price should be in cents (multiply by 100 if stored as dollars)
$amount = (int)($course['price'] * 100); // Assuming 'price' is stored as dollars in the database

try {
    // Create a PaymentIntent with automatic payment methods (no redirects)
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'usd',
        'payment_method' => $payment_method_id,
        'confirm' => true,
        'automatic_payment_methods' => [
            'enabled' => true,
            'allow_redirects' => 'never', // Disallow redirect-based payments
        ]
    ]);

    // Check the status of the PaymentIntent
    if ($paymentIntent->status == 'succeeded') {
        // Store payment details in MySQL (update table structure to include payment_method)
        $stmt = $conn->prepare("INSERT INTO payments (user_id, course_id, amount, status, payment_method) VALUES (?, ?, ?, ?, ?)");
        $status = 'completed';
        $stmt->bind_param("iiiss", $user_id, $course_id, $amount, $status, $payment_method_id);
        $stmt->execute();

        echo json_encode(['message' => 'Payment succeeded!']);
    } else {
        echo json_encode(['error' => 'Payment failed!']);
    }
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Handle error
    echo json_encode(['error' => $e->getMessage()]);
}

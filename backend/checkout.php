<?php
// checkout.php
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

// Validate the payment method and course ID
if (!$payment_method_id || !$course_id) {
    echo json_encode(['error' => 'Missing payment_method or course_id']);
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
        // Store payment details in MySQL
        $user_id = 1; // Replace this with actual logged-in user's ID
        $stmt = $conn->prepare("INSERT INTO payments (user_id, course_id, amount, status) VALUES (?, ?, ?, ?)");
        $status = 'completed';
        $stmt->bind_param("iiis", $user_id, $course_id, $amount, $status);
        $stmt->execute();

        echo json_encode(['message' => 'Payment succeeded!']);
    } else {
        echo json_encode(['error' => 'Payment failed!']);
    }
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Handle error
    echo json_encode(['error' => $e->getMessage()]);
}

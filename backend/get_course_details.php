<?php
// get_course_details.php
require 'db.php'; // Ensure this includes your DB connection

$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
$course_details = null;
if (isset($_SESSION['user_id'])) {
    // If not logged in, redirect to register.html
    header('Location: ../register/register.html');
    exit();
}
if ($course_id) {
    // Fetch course details from the database
    $query = "SELECT * FROM courses WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $course_details = $result->fetch_assoc();
    } else {
        die('Course not found');
    }
} else {
    die('Invalid course ID');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h2>Course Payment: <?php echo htmlspecialchars($course_details['title']); ?></h2>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($course_details['description']); ?></p>
    <p><strong>Price:</strong> $<?php echo number_format($course_details['price'] / 100, 2); ?> USD</p> <!-- Assuming price is in cents -->

    <form id="payment-form">
        <div id="card-element"><!-- Stripe.js injects the card form here --></div>
        <button type="submit" id="submit">Pay</button>
        <div id="error-message"></div>
        <div id="success-message" style="color: green; display: none;"></div> <!-- Success message -->
    </form>

    <script>
        const stripe = Stripe('pk_test_51QYZ38HTWnHCirvrDe2bXLNEejoWOcmPOJTk2lYEtC9VbacUmLIX8f7dwRDgh8DhwwtTIuwO7DOfu18J48StnODq00bkwxbiDR');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const { error, paymentMethod } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });

            if (error) {
                document.getElementById('error-message').textContent = error.message;
            } else {
                // Send the paymentMethod ID and course_id to checkout.php
                fetch('checkout.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        payment_method: paymentMethod.id,
                        course_id: <?php echo $course_id; ?>
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        // Show success message
                        document.getElementById('success-message').textContent = data.message;
                        document.getElementById('success-message').style.display = 'block';
                    } else {
                        document.getElementById('error-message').textContent = data.error;
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    </script>
</body>
</html>

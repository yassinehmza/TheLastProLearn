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
    <style>
       /* Global styles */
.cnt {
    font-family: 'Inter', 'Roboto', Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
    color: #333;
    line-height: 1.6;
    border-radius: 15px;
    margin-left: 400px;
    margin-right: 500px;

}

/* Page container */
.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px 30px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.5s ease-out;
}

/* Fade-in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Header */
h2 {
    font-size: 28px;
    font-weight: 600;
    color: #222;
    margin-bottom: 20px;
    text-align: center;
}

p {
    font-size: 16px;
    color: #555;
    margin-bottom: 15px;
}

strong {
    color: #000;
}

/* Form styles */
#payment-form {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    width: 300px;
}

#card-element {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f9f9f9;
    font-size: 16px;
    color: #333;
    transition: border-color 0.3s, box-shadow 0.3s;
}

#card-element:focus {
    border-color: #0070f3;
    box-shadow: 0 0 8px rgba(0, 112, 243, 0.2);
}

/* Submit button */
button#submit {
    padding: 14px;
    background: linear-gradient(90deg, #0070f3, #0053c1);
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}

button#submit:hover {
    background: linear-gradient(90deg, #0053c1, #0070f3);
    transform: scale(1.02);
}

button#submit:active {
    transform: scale(0.98);
}

/* Messages */
#error-message,
#success-message {
    font-size: 14px;
    font-weight: 500;
    padding: 10px;
    border-radius: 6px;
    margin-top: 10px;
    display: none;
}

#error-message {
    background: #ffe5e5;
    color: #d9534f;
}

#success-message {
    background: #e5ffe5;
    color: #5cb85c;
    display: none;
}

/* Responsiveness */
@media (max-width: 768px) {
    .container {
        padding: 20px;
    }

    h2 {
        font-size: 24px;
    }

    button#submit {
        font-size: 14px;
    }
}

    </style>
</head>
<body>
    <center>
        <br><br><br><br>
    <div class="cnt">
        <br>
    <h2>Course Payment: <?php echo htmlspecialchars($course_details['title']); ?></h2>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($course_details['description']); ?></p>
    <p><strong>Price:</strong> $<?php echo number_format($course_details['price'] / 100, 2); ?> USD</p> <!-- Assuming price is in cents -->

    <form id="payment-form">
        <div id="card-element"><!-- Stripe.js injects the card form here --></div>
        <button type="submit" id="submit">Pay</button>
        <div id="error-message"></div>
        <div id="success-message" style="color: green; display: none;"></div> <!-- Success message -->
    </form>
    <br><br>
    </div>
    </center>

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

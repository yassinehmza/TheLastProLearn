<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProLearn</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">ProLearn</div>
            <span class="hamburger" onclick="toggleMenu()">☰</span>
            <ul id="navLinks">
                <li><a href="./index.php" onclick="showSection('home')"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="/pages/showallcourses.html" onclick="showSection('courses')"><i class="fas fa-book"></i> Courses</a></li>
                <li id="loginButton"><a href="login/login.html" onclick="showSection('login')"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li id="signupButton"><a href="register/register.html" onclick="showSection('signup')"><i class="fas fa-user-plus"></i> Signup</a></li>
                <li id="dashboardButton" style="display: none;"><a href="/dashboard/dashboard.html" onclick="showSection('dashboard')"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="#feedback" onclick="showSection('feedback')"><i class="fas fa-comment-dots"></i> Feedback</a></li>
                <li><a href="#contact" onclick="showSection('contact')"><i class="fas fa-envelope"></i> Contact</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Home Section -->
        <section id="home" class="welcome">
            <h1>Welcome to ProLearn</h1>
            <p>Learn and practice your skills with us.</p>
            <a href="./login/login.html" class="get-started" onclick="showSection('signup')">Get Started</a>
        </section>

        <!-- Popular Subjects Section -->
        <div class="title">
            <span>Popular Subjects on ProLearn</span>
        </div>
        <div class="course">
            <center>
                <div class="cbox">
                    <div class="det"><a href="Cours/quiz.html"><img src="images/courses/d1.png" alt="Daily Quiz">Daily Quiz</a></div>
                </div>
            </center>
            <div class="cbox">
                <div class="det"><a href="Cours/computer_courses.html"><img src="images/courses/computer.png" alt="Computer Courses">Computer Courses</a></div>
                <div class="det"><a href="/pages/DataStructures.html"><img src="images/courses/data.png" alt="Data Structures">Data Structures</a></div>
                <div class="det"><a href="pages/Algorithm.html"><img src="images/courses/algo.png" alt="Algorithm">Algorithm</a></div>
            </div>
        </div>

        <!-- About Section -->
        <section id="about_section" class="diffSection">
            <center><p style="font-size: 50px; padding: 100px">About</p></center>
            <div class="about-content">
                <div class="side-image">
                    <img class="sideImage" src="images/extra/e3.jpg" alt="About Us">
                </div>
                <div class="side-text">
                    <h2>What you think about us?</h2>
                    <p>Education is the process of facilitating learning, or the acquisition of knowledge, skills, values, beliefs, and habits...</p>
                </div>
            </div>
        </section>

        <!-- Feedback Section -->
        <div class="title2" id="feedback">
            <span>Feedbacks</span>
            <div class="shortdesc2">
                <p>See what others have shared about us:</p>
            </div>
        </div>
        <div class="feedbox">
            <?php
            // Include database connection
            include './backend/db.php';

            // Query to fetch feedback data
            $sql = "SELECT id, name, your_feedback, rate_your_experience, created_at FROM feedback";
            if ($result = $conn->query($sql)) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <div class='feedback-card'>
                            <h3>" . htmlspecialchars($row['name']) . "</h3>
                            <p class='feedback-text'>" . htmlspecialchars($row['your_feedback']) . "</p>
                            <div class='rating'>
                                " . generateStars($row['rate_your_experience']) . "
                            </div>
                            <p class='submitted-at'>Submitted At: " . htmlspecialchars($row['created_at']) . "</p>
                        </div>";
                    }
                } else {
                    echo "<p>No feedbacks found. Be the first to share your feedback!</p>";
                }
                $result->free();
            } else {
                echo "<p>Error fetching feedbacks: " . htmlspecialchars($conn->error) . "</p>";
            }

            // Close the database connection
            $conn->close();

            // Function to generate star rating based on score
            function generateStars($rating) {
                $stars = "";
                for ($i = 0; $i < 5; $i++) {
                    if ($i < $rating) {
                        $stars .= "&#9733; "; // Full star
                    } else {
                        $stars .= "&#9734; "; // Empty star
                    }
                }
                return $stars;
            }
            ?>
        </div>



        <!-- Contact Section -->
        <section id="contact" class="contact">
            <h2>Contact Us</h2>
            <p>If you have any questions, feel free to reach out!</p>
            <form action="contact_process.php" method="POST">
                <label for="contact-name">Your Name:</label>
                <input type="text" id="contact-name" name="name" required>

                <label for="contact-email">Your Email:</label>
                <input type="email" id="contact-email" name="email" required>

                <label for="contact-message">Message:</label>
                <textarea id="contact-message" name="message" rows="4" required></textarea>

                <button type="submit" class="btn-submit">Send Message</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 ProLearn. All rights reserved.</p>
    </footer>

    <script src="js/script.js"></script>
    <script>
        // Function to toggle the menu in mobile view
        function toggleMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.style.display = navLinks.style.display === 'block' ? 'none' : 'block';
        }

        // Function to check login status
        function checkLoginStatus() {
            fetch('backend/check_login_status.php')
                .then(response => response.json())
                .then(data => {
                    if (data.logged_in) {
                        document.getElementById('loginButton').style.display = 'none';
                        document.getElementById('signupButton').style.display = 'none';
                        document.getElementById('dashboardButton').style.display = 'inline-block';
                    } else {
                        document.getElementById('loginButton').style.display = 'inline-block';
                        document.getElementById('signupButton').style.display = 'inline-block';
                        document.getElementById('dashboardButton').style.display = 'none';
                    }
                })
                .catch(error => console.error('Error checking login status:', error));
        }

        window.onload = checkLoginStatus;
    </script>
</body>
</html>

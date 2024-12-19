<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="png" href="../images/icon/favicon.png">
    <title>ProLearn</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <a href="../index.html"><img src="../images/icon/logo.png" style="width: 200px;" alt="Logo"></a>
            <span class="hamburger" onclick="toggleMenu()">☰</span>
            <ul id="navLinks">
                <li><a href="./index.html" onclick="showSection('home')"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="/pages/showallcourses.html" onclick="showSection('courses')"><i class="fas fa-book"></i> Courses</a></li>
                <li id="loginButton"><a href="login/login.html" onclick="showSection('login')"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li id="signupButton"><a href="register/register.html" onclick="showSection('signup')"><i class="fas fa-user-plus"></i> Signup</a></li>
                <li id="dashboardButton" style="display: none;"><a href="dashboard.html" onclick="showSection('dashboard')"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
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

        <!-- Some Popular Subjects -->
        <div class="title">
            <span>Popular Subjects on ProLearn</span>
        </div>

        <div class="course">
            <div class="cbox">
                <div class="det"><a href="Cours/quiz.html"><img src="images/courses/d1.png" alt="Daily Quiz">Daily Quiz</a></div>
            </div>
            <div class="cbox">
                <div class="det"><a href="Cours/computer_courses.html"><img src="images/courses/computer.png" alt="Computer Courses">Computer Courses</a></div>
                <div class="det"><a href="Cours/computer_courses.html#data"><img src="images/courses/data.png" alt="Data Structures">Data Structures</a></div>
                <div class="det"><a href="Cours/computer_courses.html#algo"><img src="images/courses/algo.png" alt="Algorithm">Algorithm</a></div>
            </div>
        </div>

        <!-- ABOUT -->
        <div class="diffSection" id="about_section">
            <center><p class="about-title">About</p></center>
            <div class="about-content">
                <div class="side-image">
                    <img class="sideImage" src="images/extra/e3.jpg" alt="About Us">
                </div>
                <div class="side-text">
                    <h2>What do you think about us?</h2>
                    <p>Empowering Learning for Everyone <br>
                        Welcome to ProLearn, your partner in digital learning excellence. We provide an intuitive and accessible platform for learners and educators alike. <br>
                        Our Mission: <br>
                        <ul>
                            <li>High-quality courses tailored to diverse learning needs.</li>
                            <li>Advanced tools for tracking progress and mastering new skills.</li>
                            <li>A community-driven approach to support and inspire learners at every step.</li>
                        </ul>
                        Why Choose ProLearn? <br>
                        <ul>
                            <li>User-Centric Design: Our platform is designed for ease of use and maximum engagement.</li>
                            <li>Expert Content: Courses curated by industry professionals to ensure up-to-date and valuable knowledge.</li>
                            <li>Flexibility: Learn anytime, anywhere, at your own pace.</li>
                        </ul>
                        Join us in our journey to make learning accessible, enjoyable, and impactful for all.
                    </p>
                </div>
            </div>
        </div>

        <!-- CONTACT US -->
        <div class="diffSection" id="contactus_section">
            <center><p class="contact-title">Contact Us</p></center>
            <div class="back-contact">
                <div class="cc">
                    <form action="mailto:roshank9419@gmail.com" method="post" enctype="text/plain">
                        <label>First Name <span class="imp">*</span></label>
                        <label style="margin-left: 185px">Last Name <span class="imp">*</span></label><br>
                        <center>
                            <input type="text" name="fname" style="margin-right: 10px; width: 175px" required="required">
                            <input type="text" name="lname" style="width: 175px" required="required"><br>
                        </center>
                        <label>Email <span class="imp">*</span></label><br>
                        <input type="email" name="mail" style="width: 100%" required="required"><br>
                        <label>Message <span class="imp">*</span></label><br>
                        <input type="text" name="message" style="width: 100%" required="required"><br>
                        <label>Additional Details</label><br>
                        <textarea name="additional"></textarea><br>
                        <button type="submit" id="csubmit">Send Message</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Feedback Section -->
        <div class="title2" id="feedBACK">
            <span>Give Feedback</span>
            <div class="shortdesc2">
                <p>Please share your valuable feedback with us</p>
            </div>
        </div>

        <div class="feedbox">
            <div class="feed">
                <form action="mailto:roshank9419@gmail.com" method="post" enctype="text/plain">
                    <label>Your Name</label><br>
                    <input type="text" name="fname" class="fname" required="required"><br>
                    <label>Email</label><br>
                    <input type="email" name="mail" required="required"><br>
                    <label>Additional Details</label><br>
                    <textarea name="additional"></textarea><br>
                    <button type="submit" id="csubmit">Send Message</button>
                </form>
            </div>
        </div>

        <!-- Contact Section -->
        <section id="contact" class="contact">
            <h2>Contact Us</h2>
            <p>If you have any questions, feel free to reach out!</p>
            <form>
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
        // Function to check login status and user role
        function checkLoginStatus() {
            fetch('../backend/check_login_status.php') // This file checks login status from the session
                .then(response => response.json())
                .then(data => {
                    if (data.logged_in) {
                        // Hide login and signup, show dashboard
                        document.getElementById('loginButton').style.display = 'none';
                        document.getElementById('signupButton').style.display = 'none';

                        // Show the correct dashboard based on role
                        document.getElementById('dashboardButton').style.display = 'block';
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Check login status on page load
        window.onload = checkLoginStatus;

        // Toggle the navigation menu on mobile view
        function toggleMenu() {
            let navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }
    </script>
</body>
</html>

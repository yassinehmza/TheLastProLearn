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
    <header class="Header">
        <nav id="navbar">
            <a href="index.php"><img src="../images/extra/ll.png" style="width: 200px;" alt="Logo"></a>
            <span class="hamburger" onclick="toggleMenu()">☰</span>
            <ul id="navLinks">
                <li><a href="index.php" onclick="showSection('home')"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="/pages/showallcourses.html" onclick="showSection('courses')"><i class="fas fa-book"></i> Courses</a></li>
                <li id="loginButton"><a href="login/login.html" onclick="showSection('login')"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li id="signupButton"><a href="../register/register.html" onclick="showSection('signup')"><i class="fas fa-user-plus"></i> Signup</a></li>
                <li id="dashboardButton" style="display: none;"><a href="../dashboard/dashboard.html" onclick="showSection('dashboard')"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="#feedback_section" onclick="showSection('feedback')"><i class="fas fa-comment-dots"></i> Feedback</a></li>
                <li><a href="#contact" onclick="showSection('contact')"><i class="fas fa-envelope"></i> Contact</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <!-- Home Section -->
        <section id="home" class="welcome">
            <div class="img">
                <img src="./images/extra/Code_on_t-shirt-removebg-preview.png" alt="">
            </div>
            <h1>Empower Your Future <br>Learn It
    Skills <br> Anytime , Anywhere!</h1>
            <a href="./login/login.html" class="get-started" onclick="showSection('signup')">Get Started</a>
        </section>
        <br><br><br>
        <!-- Some Popular Subjects -->
	<div class="title">
		<span>Popular Subjects on ProLearn</span>
	</div>
	<br><br>
	<div class="course">
		<center><div class="cbox">
		<div class="det"><a href="Cours/quiz.html"><img src="images/courses/d1.png">Daily Quiz</a></div>
		</div></center>
		<div class="cbox">
		<div class="det"><a href="Cours/computer_courses.html"><img src="images/courses/computer.png" alt="Computer Courses">Computer Courses</a></div>
		
		
		</div>
	</div>
        

        <!-- ABOUT Section -->
        <div class="diffSection" id="about_section">
            <center><h1 class="about-title">About</></center>
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

        <!-- CONTACT US Section -->
        <div class="diffSection" id="contactus_section">
            <center><p class="contact-title">Contact Us</p></center>
            <div class="back-contact">
                <div class="cc">
                    <form action="yassine5.hamza5@gmail.com" method="post" enctype="text/plain">
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
<!--Feedack-->
        <div class="diffSection" id="feedback_section">
    <center><p class="feedback-title">Student Feedback</p></center>
    <div class="feedback-container">
        <button class="slider-btn prev">‹</button>
        <div class="feedback-slider">
            <!-- PHP générera ici les cartes feedback -->
            <?php
            include './backend/db.php';

            $sql = "SELECT id, name, your_feedback, rate_your_experience, created_at FROM feedback";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $stars = str_repeat('★', intval($row['rate_your_experience'])) . 
                             str_repeat('☆', 5 - intval($row['rate_your_experience']));

                    echo "<div class='feedback-card'>
                            <div class='card-header'>
                                <h3>{$row['name']}</h3>
                                <div class='stars'>{$stars}</div>
                            </div>
                            <div class='card-content'>
                                <p>{$row['your_feedback']}</p>
                            </div>
                            <div class='card-footer'>
                                <span>{$row['created_at']}</span>
                            </div>
                          </div>";
                }
            } else {
                echo "<p>No feedbacks found. Be the first to share your feedback!</p>";
            }

            $conn->close();
            ?>
        </div>
        <button class="slider-btn next">›</button>
    </div>
</div>
<br><br>

<script>
    const slider = document.querySelector(".feedback-slider");
const prevBtn = document.querySelector(".slider-btn.prev");
const nextBtn = document.querySelector(".slider-btn.next");

let currentIndex = 0; // Index du slide actuel
const itemsPerSlide = 6; // Nombre de feedbacks par slide
const totalItems = document.querySelectorAll(".feedback-card").length;
const totalSlides = Math.ceil(totalItems / itemsPerSlide); // Nombre total de slides

// Fonction pour aller au slide précédent
prevBtn.addEventListener("click", () => {
    if (currentIndex > 0) {
        currentIndex--;
        updateSliderPosition();
    }
});

// Fonction pour aller au slide suivant
nextBtn.addEventListener("click", () => {
    if (currentIndex < totalSlides - 1) {
        currentIndex++;
        updateSliderPosition();
    }
});

// Met à jour la position du slider
function updateSliderPosition() {
    const slideWidth = slider.parentElement.offsetWidth; // Largeur du container
    slider.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
}

</script>


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
        checkLoginStatus();
    </script>
</body>
</html>

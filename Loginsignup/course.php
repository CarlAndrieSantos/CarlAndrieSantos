<?php
session_start();
include("connection.php");

// Fetch courses from the database
$query_courses = "SELECT course_id, courses, Price, duration FROM courses";
$courses_result = mysqli_query($connection, $query_courses);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="android-chrome-512x512.png" type="image/x-icon">
    <link rel="stylesheet" href="course1.css">
    <title>Course</title>
</head>
<body>
    <header>
        <div class="container">
           <div class="logo">CodeBegin</div>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li class="courses"><a href="course.php">Courses</a></li>
                <li><a href="tutor.php">Tutors</a></li>
                <li><a href="contactus.html">Contact Us</a></li>
                <li class="userlogo"><a href="logout.php">Logout</a></li>
            </ul>
        </nav> 
        </div>
    </header>

    <section class="courses-page">
        <div class="courses-heading">
            <h1>Explore Our Web Development Courses</h1>
            <p>Tailored courses for beginners to advanced learners. Learn the skills you need to succeed.</p>
        </div>

        <section class="course-categories">
            <h2>Browse by Category</h2>

            <div class="categories-container">
                <div class="category-box">
                    <h3>Frontend Development</h3>
                    <p>Learn how to build responsive and interactive websites.</p>
                </div>

                <div class="category-box">
                    <h3>Backend Development</h3>
                    <p>Master server-side technologies and databases.</p>
                </div>

                <div class="category-box">
                    <h3>Full Stack Development</h3>
                    <p>Become a complete web developer with both frontend and backend skills.</p>
                </div>

                <div class="category-box">
                    <h3>Special Topics</h3>
                    <p>Dive into specialized areas like React, Node.js, or Web Security.</p>
                </div>
            </div>
        </section>
    </section>

    <!-- Popular Courses Section -->
    <section class="popular-courses">
        <h2>Our Most Popular Courses</h2>
        <div class="courses-grid">
            <?php
            // Fetch and display courses 
            while ($row = mysqli_fetch_assoc($courses_result)) {
                echo '<div class="course-box">';
                echo '<h3>' . $row['courses'] . '</h3>';
                echo '<p class="course-description">Learn and master the skills required for ' . $row['courses'] . '.</p>';
                echo '<div class="course-details">';
                echo '<p><strong>Duration:</strong> ' . $row['duration'] . ' </p>';
                echo '<p><strong>Price:</strong> ₱' . $row['Price'] . '</p>';
                echo '</div>';
                echo '<a href="coursedetails.html?course_id=' . $row['course_id'] . '" class="course-button">View Course Details</a>';
                echo '</div>';
            }
            ?>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-content">
            <div class="quick-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Courses</a></li>
                    <li><a href="#">Tutors</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <div class="social-media">
                <h3>Follow Us</h3>
                <ul class="social-icons">
                    <li><a href="#"><img src="linkedin-big-logo.png" alt="LinkedIn"></a></li>
                    <li><a href="#"><img src="twitter.png" alt="Twitter"></a></li>
                    <li><a href="#"><img src="github.png" alt="GitHub"></a></li>
                </ul>
            </div>

            <div class="contact-info">
                <h3>Contact Us</h3>
                <p>Email: info@codebegin.com</p>
                <p>Phone: +63 912 345 6789</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2024 CodeBegin. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>

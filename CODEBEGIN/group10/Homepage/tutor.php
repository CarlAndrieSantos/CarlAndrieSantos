<?php
session_start();
include("connection.php");

// Fetch all tutors from the database
$query = "SELECT tutor_id, tutor_name, Bio, available_date, Expertise, rating FROM tutors";
$result = mysqli_query($connection, $query);
$tutors = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="tutor2.css">
    <link rel="shortcut icon" href="android-chrome-512x512.png" type="image/x-icon">
    <title>Tutors</title>
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">CodeBegin</div>
            <nav>
                <ul>
                    <li><a href="home.html">Home</a></li>
                    <li><a href="course.php">Courses</a></li>
                    <li class="courses"><a href="tutor.php">Tutors</a></li>
                    <li><a href="contactus.html">Contact Us</a></li>
                    <li><a href="/Loginsignup/login1.php">Login/Sign up</a></li>
                </ul>
            </nav> 
        </div>
    </header>

    <section class="tutors-section">
        <header>
            <h1>Meet Our Expert Web Development Tutors</h1>
            <p>Learn from experienced web developers who are passionate about teaching.</p>
           
        </header>
        <div class="featured-tutors1">
            <h2>Featured Tutors</h2>
        </div>
    
        <section class="featured-tutors">
            
            
            <?php foreach ($tutors as $tutor): ?>
            <div class="tutor">
                <img src="userforall.png" alt="<?php echo $tutor['tutor_name']; ?>">
                <div class="tutor-info">
                    <h3><?php echo $tutor['tutor_name']; ?> (<?php echo $tutor['Expertise']; ?>)</h3>
                    <p><?php echo $tutor['Bio']; ?></p>
                    <p><strong>Expertise:</strong> <?php echo $tutor['Expertise']; ?></p>
                    <p><strong>Rating:</strong> ★★★★★ (<?php echo $tutor['rating']; ?>/5)</p>
                    <button><a href="/Loginsignup/login1.php">Book a Session</a></button>
                </div>
            </div>
            <?php endforeach; ?>
        </section>

        <section class="cta-section">
            <h2>Need Help Finding the Right Tutor?</h2>
            <p>Contact us, and we’ll match you with the perfect tutor for your needs.</p>
            <a href="contactus.html"><button>Get Assistance</button></a>
        </section>
    </section>

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

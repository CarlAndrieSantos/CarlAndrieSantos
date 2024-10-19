<?php  
session_start();
include("connection.php");
include("functions.php");

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password']; // Get confirm password

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name) && $password === $confirm_password) {
        // Check if the username (email) already exists in the database
        $query = "SELECT * FROM users WHERE user_email = '$user_name' LIMIT 1";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Username already exists
            $error_message = "<p style='color: red; text-align: center;'>This email is already registered.</p>";
        } else {
            // Username is available, proceed to insert the new user
            $user_id = random_num(20);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
            $query = "INSERT INTO users (id, user_email, password) VALUES ('$user_id', '$user_name', '$hashed_password')";
            mysqli_query($connection, $query);

            // Set session variable after signup
            $_SESSION['id'] = $user_id; // Store user ID in session
            header("Location: home.php");
            die;
        }
    } else {
        $error_message = "Please enter some valid information!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="signup.css">
    <link rel="shortcut icon" href="android-chrome-512x512.png" type="image/x-icon">
    <title>Sign-up</title>
</head>
<body>
    <header>
        <div class="container">
           <div class="logo">CodeBegin</div>
           <nav>
               <ul>
                   <li><a href="../CODEBEGIN/group10/Homepage/home.html">Home</a></li>
                   <li><a href="../CODEBEGIN/group10/Homepage/course.php">Courses</a></li>
                   <li><a href="../CODEBEGIN/group10/Homepage/tutor.php">Tutors</a></li>
                   <li><a href="../CODEBEGIN/group10/Homepage/contactus.html">Contact Us</a></li>
               </ul>
           </nav> 
        </div>
    </header>
    <section class="signup">
        <div class="signup-container">
            <h2>Sign Up</h2>
            <?php if ($error_message): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="POST" onsubmit="return validateSignup()">
                <label for="username">Email</label>
                <input type="text" id="username" name="username" required placeholder="Enter your email">

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">

                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required placeholder="Confirm your password">

                <input type="submit" value="Sign Up">
            </form>
            <div class="login-link">
                <p>Already have an account? <a href="login1.php">Login here</a></p>
            </div>
        </div>
    </section>

    <script>
        function validateSignup() {
            const emailInput = document.getElementById('username');
            const emailValue = emailInput.value;
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm-password');
            const confirmPasswordValue = confirmPasswordInput.value;
    
            // Regular expression to validate the email format
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
            // Check for valid email format
            if (!emailPattern.test(emailValue)) {
                alert("Please enter a valid email address that contains '@' and '.'");
                return false; // Prevent form submission
            }
    
            // Check if passwords match
            if (passwordInput.value !== confirmPasswordValue) {
                alert("Passwords do not match. Please try again.");
                return false; // Prevent form submission
            }
    
            return true; // Allow form submission
        }
    </script>

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
            <p>&copy; 2024 Codebegin. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>

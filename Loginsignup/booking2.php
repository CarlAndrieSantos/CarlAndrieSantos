<?php
session_start();
include("connection.php");

// Fetch Tutors and Availability from the database
$query_tutors = "SELECT tutor_id, tutor_name, available_date FROM tutors";
$tutors_result = mysqli_query($connection, $query_tutors);

$query_courses = "SELECT course_id, courses, Price, duration FROM courses";
$courses_result = mysqli_query($connection, $query_courses);

$booking_successful = false;  // Flag to check if booking is successful

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Check if the user is logged in
    if (isset($_SESSION['id'])) {
        // Collect form data
        $user_id = $_SESSION['id'];  // Get user ID from session
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone = $_POST['user_phone'];
        $user_address = $_POST['user_address'];
        $age = $_POST['user_age'];
        $gender = $_POST['user_gender'];
        $available_date = $_POST['available_date'];
        $prepared_time = $_POST['prepared_time'];
        $tutor_id = $_POST['tutor_id'];
        $course_id = $_POST['course'];
        $payment_method = $_POST['payment_method'];

        // Insert into bookings table
        $query = "INSERT INTO bookings (id, first_name, last_name, user_phone, user_address, user_age, user_gender, prepared_time, course_id, tutor_id, available_date, payment_method)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare the query
        if ($stmt = mysqli_prepare($connection, $query)) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "issisissiiss", $user_id, $first_name, $last_name, $phone, $user_address, $age, $gender, $prepared_time, $course_id, $tutor_id, $available_date, $payment_method);
            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $booking_successful = true; // Set flag to true if booking is successful
            } else {
                echo "Error: " . mysqli_stmt_error($stmt);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Error preparing statement: " . mysqli_error($connection);
        }
    } else {
        echo "You need to log in to make a booking.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="booking_5.css">
    <link rel="shortcut icon" href="android-chrome-512x512.png" type="image/x-icon">
    <title>Book a Session</title>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">CodeBegin</div>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="course.php">Courses</a></li>
                    <li class="courses"><a href="tutor.php">Tutors</a></li>
                    <li><a href="contactus.html">Contact Us</a></li>
                    <li class="userlogo"><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Booking Form -->
    <form method="post">
        <div class="container_booking">
            <h2>Book Your Session</h2>
            <h3>Enter Your Information</h3>
            <div class="name">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" placeholder="Enter your first name" required><br><br>
                
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" placeholder="Enter your last name" required><br><br>
            </div>
            
            <div class="info">
               <label for="phone">Phone Number:</label>
                <input type="tel" name="user_phone" placeholder="Enter your phone number" required><br><br>

                <label for="age">Age:</label>
                <input type="number" name="user_age" placeholder="Enter your age" required><br><br>
            </div>

            <div class="info2">  
                <label for="gender">Gender:</label>
                <select name="user_gender" id="gender" required>
                    <option value="" selected disabled>Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select><br><br>          
                
                <label for="prepared_time">Preferred Time:</label>
                <select name="prepared_time" id="preferred_time" required>
                    <option value="" selected disabled>Select Time</option>
                    <option value="Morning (9:00 AM - 12:00 PM)">Morning (9:00 AM - 12:00 PM)</option>
                    <option value="Afternoon (1:00 PM - 4:00 PM)">Afternoon (1:00 PM - 4:00 PM)</option>
                    <option value="Evening (6:00 PM - 9:00 PM)">Evening (6:00 PM - 9:00 PM)</option>
                </select>  
            </div>
            
            <div class="address">
                <label for="user_address">Address:</label>
                <input type="text" name="user_address" placeholder="Enter your address" required>
            </div><br>

        <div class="choose">
               <label for="tutor_id">Choose a Tutor:</label>
               <select name="tutor_id" id="tutor_id" required>
                <option value="" selected disabled>Select Tutor</option>
                <?php
                while ($row = mysqli_fetch_assoc($tutors_result)) {
                    echo "<option value='{$row['tutor_id']}' data-available-date='{$row['available_date']}'>{$row['tutor_name']} - {$row['available_date']}</option>";
                }
                ?>
                </select>

            <input type="hidden" name="available_date" id="available_date" value="">

            <!-- Courses and Prices -->
            <label for="course">Courses:</label>
            <select name="course" id="courses" onchange="updatePrice()" required>
                <option value="" selected disabled>Select Course</option>
                <?php
                while ($row = mysqli_fetch_assoc($courses_result)) {
                    echo "<option value='{$row['course_id']}' data-Price='{$row['Price']}'>{$row['courses']} - PHP {$row['Price']}</option>";
                }
                ?>
            </select>
        </div><br>
                <!-- Display selected price -->
                <div class="course-price">
                <h4>Course Price: PHP <span id="course_price">0</span></h4>
                </div>
        <div class="payment">
            <label for="payment_method">Payment Method:</label>
            <select name="payment_method" id="payment_method" onchange="showPaymentModal()" required>
                <option value="" selected disabled>Select Payment Method</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Paypal">PayPal</option>
                <option value="Gcash">GCash</option>
            </select><br><br>   
        </div>
        <div class="terms">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">I accept the <a href="#" id="termsLink">Terms and Conditions</a></label><br><br>
            
        </div>
        <button type="submit" name="submit">Submit Booking</button>
        </div>
    </form> 

    <!-- Modal for Payment Methods -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="paymentTitle"></h2>
            
            <!-- Payment Method Specific Fields -->
            <div id="paymentInputs"></div>
            
            <!-- Pay Now Button -->
            <button id="payNowBtn" onclick="processPayment()">Pay Now</button>
        </div>
    </div>

    <!-- Modal for Payment Success -->
    <div id="successModal" class="modal">
        <div class="modal-content"> 
            <span class="close">&times;</span>
            <h2>Payment Successful</h2>
            <p>Your payment has been successfully completed. Thank you! 😊</p>
        </div>
    </div>

    <!-- Modal for Terms and Conditions -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Terms and Conditions</h2>
            <p>Here are the terms and conditions of the service. Please read carefully.</p>
            <ul>
                <li>All sessions must be booked at least 24 hours in advance.</li>
                <li>Cancellation must be done 12 hours before the scheduled session.</li>
                <li>Payments are non-refundable unless the tutor cancels the session.</li>
                <li>By booking, you agree to follow the guidelines set by the tutor.</li>
                <li>Any misconduct during the session can lead to a ban from future sessions.</li>
            </ul>
        </div>
    </div>

    <!-- Modal for Booking Success -->
    <div id="bookingSuccessModal" class="modal" <?php if($booking_successful) echo 'style="display:block;"'; ?>>
        <div class="modal-content">
            <span class="close" id="closeSuccessModal">&times;</span>
            <h2>Booking Successful</h2>
            <p>Your booking has been successfully completed. Thank you! 😊</p>
        </div>
    </div>

    <script>
        const termsLink = document.getElementById("termsLink");
        const termsModal = document.getElementById("termsModal");
        const closeButtons = document.querySelectorAll(".modal .close");

        termsLink.addEventListener("click", function(event) {
            event.preventDefault();
            termsModal.style.display = "block";
        });

        closeButtons.forEach(button => {
            button.addEventListener("click", function() {
                this.closest('.modal').style.display = "none";
            });
        });

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = "none";
            }
        };

        const tutorSelect = document.getElementById("tutor_id");
        const availableDateInput = document.getElementById("available_date");

        tutorSelect.addEventListener("change", function() {
            const selectedOption = tutorSelect.options[tutorSelect.selectedIndex];
            const availableDate = selectedOption.getAttribute("data-available-date");
            availableDateInput.value = availableDate;
        });

        function updatePrice() {
            const courseSelect = document.getElementById("courses");
            const selectedOption = courseSelect.options[courseSelect.selectedIndex];
            const price = selectedOption.getAttribute("data-Price");
            document.getElementById("course_price").textContent = price;
        }

        function showPaymentModal() {
            const paymentMethod = document.getElementById("payment_method").value;
            const paymentTitle = document.getElementById("paymentTitle");
            const paymentInputs = document.getElementById("paymentInputs");

            paymentTitle.textContent = paymentMethod;
            paymentInputs.innerHTML = "";  // Clear any previous inputs

            if (paymentMethod === "Credit Card") {
                paymentInputs.innerHTML = `
                    <label for="cc_number">Card Number:</label>
                    <input type="number" id="cc_number" required><br><br>
                    <label for="cc_expiry">Expiry Date:</label>
                    <input type="date" id="cc_expiry" required><br><br>
                    <label for="cc_cvv">CVV:</label>
                    <input type="number" id="cc_cvv" required><br><br>
                `;
            } else if (paymentMethod === "Paypal") {
                paymentInputs.innerHTML = `
                    <label for="paypal_email">PayPal Email:</label>
                    <input type="email" id="paypal_email" required><br><br>
                `;
            } else if (paymentMethod === "Gcash") {
                paymentInputs.innerHTML = `
                    <label for="gcash_number">Gcash Number:</label>
                    <input type="number" id="gcash_number" required><br><br>
                `;
            }

            document.getElementById("paymentModal").style.display = "block";
        }

        function processPayment() {
            document.getElementById("paymentModal").style.display = "none";
            document.getElementById("successModal").style.display = "block";
        }

        const successModal = document.getElementById("bookingSuccessModal");
        const closeSuccessModal = document.getElementById("closeSuccessModal");

        closeSuccessModal.addEventListener("click", function() {
            window.location.href = "home.php";  // Redirect to home.php
        });

        window.onclick = function(event) {
            if (event.target == successModal) {
                window.location.href = "home.php";  // Redirect to home.php if clicked outside the modal
            }
        };
    </script>
    </script>
</body>
</html>

<?php
session_start();
$host = 'localhost'; 
$username = 'root'; 
$password = '';
$database = 'login_sample_db'; 

// Create a connection
$connection = mysqli_connect($host, $username, $password, $database);
// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch bookings from the database
$query = "SELECT * FROM bookings"; // Adjust this query based on your actual table structure
$result = mysqli_query($connection, $query);



// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM bookings WHERE booking_id = '$delete_id'";
    mysqli_query($connection, $delete_query);
    header("Location: admin.php"); // Redirect to avoid form resubmission
}

// Handle update request
if (isset($_POST['update'])) {
    $booking_id = $_POST['booking_id'];
    $available_date = $_POST['available_date'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $user_phone = $_POST['user_phone'];
    $user_address = $_POST['user_address'];
    $user_age = $_POST['user_age'];
    $user_gender = $_POST['user_gender'];
    $prepared_time = $_POST['prepared_time'];
    $payment_method = $_POST['payment_method'];
    $course_id = $_POST['course_id'];

    $check_course_query = "SELECT COUNT(*) as count FROM courses WHERE course_id = '$course_id'";
    $check_course_result = mysqli_query($connection, $check_course_query);
    $check_course_row = mysqli_fetch_assoc($check_course_result);
    if ($check_course_row['count'] == 0) {
        echo "Course ID does not exist.";
        exit; // Stop execution
    }
    // Update query
    $update_query = "UPDATE bookings SET 
        available_date = '$available_date',
        first_name = '$first_name',
        last_name = '$last_name',
        user_phone = '$user_phone',
        user_address = '$user_address',
        user_age = '$user_age',
        user_gender = '$user_gender',
        prepared_time = '$prepared_time',
        payment_method = '$payment_method',
        course_id = '$course_id'
        WHERE booking_id = '$booking_id'";

if (mysqli_query($connection, $update_query)) {
    header("Location: admin.php"); // Redirect to avoid form resubmission
} else {
    echo "Error updating booking: " . mysqli_error($connection);
}
}
    if (isset($_GET['delete'])) {
        $booking_id = $_GET['delete'];
        $delete_query = "DELETE FROM bookings WHERE booking_id = '$booking_id'";
        mysqli_query($connection, $delete_query);
        header("Location: admin.php"); // Redirect to avoid form resubmission
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="android-chrome-512x512.png" type="image/x-icon">
    <title>Admin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: "Poppins", system-ui;
            display: flex;
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }
        .sidebar {
            width: 250px;
            background-color: rgb(219, 217, 217);
            transition: transform 0.3s ease;
            transform: translateX(0);
            z-index: 1000; /* Ensures sidebar is above other content */
            position: fixed; /* Fix the sidebar position */
            height: 100%; /* Full height */
        }
        .sidebar.hidden {
            transform: translateX(-250px); /* Move the sidebar off the screen */
        }
        .toggle-btn {
            background-color: rgb(27, 67, 154);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            position: absolute;
            z-index: 1001; /* Ensure the button stays above the sidebar */
            top: 13px;
            left: 10px;
        }
        .logo {
            text-align: center;
            padding: 1rem;
            font-weight: 800;
            font-size: x-large;
            background-color: rgb(27, 67, 154);
            color: white;
        }
        aside ul {
            list-style: none;
            padding: 0;
            margin: 0;
            font-weight: 500;
        }
        aside ul li {
            padding: 15px;
            cursor: pointer;
            text-align: center;
        }
        aside ul li:hover {
            background-color: lightgrey;
        }
        .content {
            flex: 1;
            padding: 20px;
            margin-left: 250px; /* Leave space for the sidebar */
            transition: margin-left 0.3s ease; /* Smooth transition for content area */
        }
        .content.sidebar-hidden {
            margin-left: 0; /* Adjust content margin when sidebar is hidden */
        }
        .table_booking {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: rgb(226, 228, 231);
        }
        h3 {
            margin: 0;
            padding: 15px;
        }
        .action-buttons {
            margin-bottom: 15px;
        }
        .underline {
            background-color: grey;
            width: 88%;
            text-align: center;
            
        }
        .content h3{
            text-align: center;
            font-size: xx-large;
        }
        ul li a{
            text-decoration: none;
        }
        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
        }

        .modal-content {
        background-color: #fff;
        margin: auto;
        padding: 20px;
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0,0,0,0.2); /* Subtle shadow for depth */
        width: 90%;
        max-width: 500px; /* Max width for larger screens */
        }

        .form-group {
        margin-bottom: 15px; /* Space between fields */
        }

        label {
        display: block; /* Make labels block elements */
        margin-bottom: 5px; /* Space between label and input */
        font-weight: 500; /* Slightly bolder font */
        }

        input[type="text"],
        input[type="date"],
        input[type="tel"],
        input[type="number"],
        input[type="time"],
        select {
        width: 96%; /* Full width */
        padding: 8px; /* Padding for comfort */
        border: 1px solid #ccc; /* Border color */
        border-radius: 4px; /* Slightly rounded corners */
        }

        input[type="submit"].btn {
        background-color: rgb(27, 67, 154); /* Primary button color */
        color: white;
        border: none; /* Remove border */
        padding: 10px; /* Padding for button */
        border-radius: 4px; /* Slightly rounded corners */
        cursor: pointer; /* Pointer on hover */
        }

        input[type="submit"].btn:hover {
        background-color: rgb(15, 47, 114); /* Darker shade on hover */
        }
        .close{
            font-size: 30px;
            cursor: pointer !important;
        }        
        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px; /* Slightly narrower on smaller screens */
            }
            .toggle-btn {
                top: 15px;
                left: 15px;
            }
        }
        @media (max-width: 480px) {
            .sidebar {
                width: 100%; /* Full width for very small screens */
                height: auto; /* Adjust height for small screens */
                position: relative; /* Change to relative for stacking */
            }
            .content {
                margin-left: 0; /* No margin for small screens */
            }
            .content.sidebar-hidden {
                margin-left: 0; /* Ensure no margin when sidebar is hidden */
            }
            .toggle-btn {
                position: fixed; /* Keep toggle button fixed */
                top: 10px;
                left: 10px;
            }
        }
    </style>
</head>

<body>
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <div class="sidebar" id="sidebar">
        <div class="logo">CodeBegin</div>
        <aside>
            <ul> 
                <li><a href="../Loginsignup/home.php">Home</a></li>
                <li class="underline"><a href="admin.php">Booking</a></li>
                <li><a href="admin_tutors.php">Tutors</a></li>
                <li><a href="admin_users.php">Users</a></li>
                <li><a href="admin_courses.php">Courses</a></li>
            </ul>
        </aside>
    </div>

    <div class="content" id="content">
        <h3>List of Bookings</h3>
        <table class="table_booking">
            <thead>
                <tr>
                    <th>Booking Id</th>
                    <th>User</th>
                    <th>Tutor</th>
                    <th>Available Date</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Prepared Time</th>
                    <th>Payment Method</th>
                    <th>Course</th>
                    <th>Actions</th> <!-- Add actions column -->
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['tutor_id']; ?></td>
                    <td><?php echo $row['available_date']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
                    <td><?php echo $row['user_phone']; ?></td>
                    <td><?php echo $row['user_address']; ?></td>
                    <td><?php echo $row['user_age']; ?></td>
                    <td><?php echo $row['user_gender']; ?></td>
                    <td><?php echo $row['prepared_time']; ?></td>
                    <td><?php echo $row['payment_method']; ?></td>
                    <td><?php echo $row['course_id']; ?></td>
                    <td>
                        <button onclick="openModal('<?php echo $row['booking_id']; ?>', '<?php echo $row['available_date']; ?>', '<?php echo $row['first_name']; ?>', '<?php echo $row['last_name']; ?>', '<?php echo $row['user_phone']; ?>', '<?php echo $row['user_address']; ?>', '<?php echo $row['user_age']; ?>', '<?php echo $row['user_gender']; ?>', '<?php echo $row['prepared_time']; ?>', '<?php echo $row['payment_method']; ?>', '<?php echo $row['course_id']; ?>')">Edit</button>
                        <button onclick="confirmDelete(<?php echo $row['booking_id']; ?>)">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- The Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Edit Booking</h2>
        <form method="POST" action="admin.php">
            <input type="hidden" name="booking_id" id="booking_id">
            <div class="form-group">
                <label for="available_date">Available Date:</label>
                <input type="date" name="available_date" id="available_date" required>
            </div>
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" required>
            </div>
            <div class="form-group">
                <label for="user_phone">Phone:</label>
                <input type="tel" name="user_phone" id="user_phone" required>
            </div>
            <div class="form-group">
                <label for="user_address">Address:</label>
                <input type="text" name="user_address" id="user_address" required>
            </div>
            <div class="form-group">
                <label for="user_age">Age:</label>
                <input type="number" name="user_age" id="user_age" required>
            </div>
            <div class="form-group">
                <label for="user_gender">Gender:</label>
                <select name="user_gender" id="user_gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <label for="prepared_time">Preferred Time:</label>
                <select name="prepared_time" id="preferred_time" required>
                    <option value="" selected disabled>Select Time</option>
                    <option value="Morning (9:00 AM - 12:00 PM)">Morning (9:00 AM - 12:00 PM)</option>
                    <option value="Afternoon (1:00 PM - 4:00 PM)">Afternoon (1:00 PM - 4:00 PM)</option>
                    <option value="Evening (6:00 PM - 9:00 PM)">Evening (6:00 PM - 9:00 PM)</option>
                </select>  
            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="GCash">GCash</option>
                </select>
            </div>
            <div class="form-group">
                <label for="course_id">Course:</label>
                <input type="text" name="course_id" id="course_id" required>
            </div>
            <input type="submit" name="update" value="Update" class="btn">
        </form>
    </div>
</div>


    <script>
        function openModal(booking_id, available_date, first_name, last_name, user_phone, user_address, user_age, user_gender, prepared_time, payment_method, course_id) {
            document.getElementById('editModal').style.display = "block";
            document.getElementById('booking_id').value = booking_id;
            document.getElementById('available_date').value = available_date;
            document.getElementById('first_name').value = first_name;
            document.getElementById('last_name').value = last_name;
            document.getElementById('user_phone').value = user_phone;
            document.getElementById('user_address').value = user_address;
            document.getElementById('user_age').value = user_age;
            document.getElementById('user_gender').value = user_gender;
            document.getElementById('prepared_time').value = prepared_time;
            document.getElementById('payment_method').value = payment_method;
            document.getElementById('course_id').value = course_id;
        }

        function closeModal() {
            document.getElementById('editModal').style.display = "none";
        }
        function confirmDelete(booking_id) {
            if (confirm("Are you sure you want to delete this booking?")) {
                window.location.href = "admin.php?delete=" + booking_id; // Redirect to delete the booking
            }
        }

        // Close the modal if the user clicks anywhere outside of the modal content
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            sidebar.classList.toggle('hidden');
            content.classList.toggle('sidebar-hidden');
        }
    </script>
</body>
</html>

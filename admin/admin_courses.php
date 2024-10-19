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
$query = "SELECT * FROM courses"; 
$result = mysqli_query($connection, $query);

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM courses WHERE course_id  = '$delete_id'";
    mysqli_query($connection, $delete_query);
    header("Location: admin_courses.php"); 
}

// Handle update request
if (isset($_POST['update'])) {
    $course_id  = $_POST['course_id '];
    $courses = $_POST['courses'];  
    $Price = $_POST['Price'];                 
    $duration = $_POST['duration'];
   
    // Update query
    $update_query = "UPDATE courses SET 
    courses = '$courses',
    Price = '$Price',
    duration = '$duration',
    WHERE course_id = '$course_id'";


if (mysqli_query($connection, $update_query)) {
    header("Location: admin_courses.php"); // Redirect to avoid form resubmission
} else {
    echo "Error updating tutors: " . mysqli_error($connection);
}
}
    if (isset($_GET['delete'])) {
        $course_id = $_GET['delete'];
        $delete_query = "DELETE FROM courses WHERE course_id = '$course_id'";
        mysqli_query($connection, $delete_query);
        header("Location: admin_courses.php"); // Redirect to avoid form resubmission
    }

    if (isset($_POST['add'])) {
        $courses = $_POST['add_courses'];
        $Price = $_POST['add_Price'];
        $duration = $_POST['add_duration'];
    
        $add_query = "INSERT INTO courses (courses, Price, duration) 
                      VALUES ('$courses', '$Price', '$duration')";
    
        if (mysqli_query($connection, $add_query)) {
            header("Location: admin_courses.php"); // Redirect to refresh the page
        } else {
            echo "Error adding tutor: " . mysqli_error($connection);
        }
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
            height: 120vh;
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
        .close{
            font-size: 30px;
            cursor: pointer !important;
        }
        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            padding-top:5%;
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
                <li ><a href="../Loginsignup/home.php">Home</a></li>
                <li><a href="admin.php">Booking</a></li>
                <li><a href="admin_tutors.php">Tutors</a></li>
                <li><a href="admin_users.php">Users</a></li>
                <li class="underline"><a href="admin_courses.php">Courses</a></li>
            </ul>
        </aside>
    </div>

    <div class="content" id="content">
        <h3>List of Courses</h3>
        <button onclick="openAddModal()">Add Course</button>
        <table class="table_booking">
            <thead>
                <tr>
                    <th>Course Id</th>
                    <th>Course</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Actions</th> 
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['course_id']; ?></td>
                    <td><?php echo $row['courses']; ?></td>
                    <td><?php echo $row['Price']; ?></td>
                    <td><?php echo $row['duration']; ?></td>
                    <td>
                        <button onclick="openModal('<?php echo $row['course_id']; ?>', '<?php echo $row['courses']; ?>', '<?php echo $row['Price']; ?>', '<?php echo $row['duration']; ?>')">Edit</button>
                        <button onclick="confirmDelete(<?php echo $row['course_id']; ?>)">Delete</button>
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
        <h2>Edit Course</h2>

        <form method="POST" action="admin_courses.php">
            <input type="hidden" name="course_id" id="course_id">
            
            <div class="form-group">
                <label for="courses">Course:</label>
                <input type="text" name="courses" id="courses">
            </div>

            <div class="form-group">
                <label for="Price">Price:</label>
                <input type="number" name="Price" id="Price">
                </div>
            <div class="form-group">
                <label for="duration">Duration</label>
                <input type="text" name="duration" id="duration" >
            </div>

            <input type="submit" name="update" value="Update" class="btn">
        </form>

    </div>
</div>

<!-- Add Tutor Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h2>Add New Course</h2>
        <form method="POST" action="admin_courses.php">
            <div class="form-group">
                <label for="add_courses">Course:</label>
                <input type="text" name="add_courses" id="add_courses" required>
            </div>

            <div class="form-group">
                <label for="add_Price">Price:</label>
                <input type="number" name="add_Price" id="add_Price" required>
            </div>

            <div class="form-group">
                <label for="add_duration">Duration:</label>
                <input type="text" name="add_duration" id="add_duration" required>
            </div>

            <input type="submit" name="add" value="Add Course" class="btn">
        </form>
    </div>
</div>

    <script>
        function openModal(course_id, courses, Price, duration) {
            document.getElementById('editModal').style.display = "block";
            document.getElementById('course_id').value = course_id;
            document.getElementById('courses').value = courses;
            document.getElementById(' Price').value =  Price;
            document.getElementById('duration').value = duration;
        }

        function closeModal() {
            document.getElementById('editModal').style.display = "none";
        }
        function confirmDelete(course_id) {
            if (confirm("Are you sure you want to delete this Tutor?")) {
                window.location.href = "admin_courses.php?delete=" + course_id; // Redirect to delete the booking
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
        function openAddModal() {
        document.getElementById('addModal').style.display = "block";
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = "none";
        }

        // Close the modal if the user clicks anywhere outside of the modal content
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            if (event.target == addModal) {
                addModal.style.display = "none";
            }
        }
    </script>
</body>
</html>

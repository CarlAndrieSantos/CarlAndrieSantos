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
$query = "SELECT * FROM tutors"; 
$result = mysqli_query($connection, $query);

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM tutors WHERE tutor_id = '$delete_id'";
    mysqli_query($connection, $delete_query);
    header("Location: admin_tutors.php"); 
}

// Handle update request
if (isset($_POST['update'])) {
    $tutor_id = $_POST['tutor_id'];
    $tutor_name = $_POST['tutor_name'];  
    $bio = $_POST['Bio'];                 
    $available_date = $_POST['available_date'];
    $expertise = $_POST['Expertise'];   
    $rating = $_POST['rating'];           

    
    // Update query
    $update_query = "UPDATE tutors SET 
    tutor_name = '$tutor_name',
    Bio = '$bio',
    available_date = '$available_date',
    expertise = '$expertise',
    rating = '$rating'
    WHERE tutor_id = '$tutor_id'";


if (mysqli_query($connection, $update_query)) {
    header("Location: admin_tutors.php"); // Redirect to avoid form resubmission
} else {
    echo "Error updating tutors: " . mysqli_error($connection);
}
}
    if (isset($_GET['delete'])) {
        $tutor_id = $_GET['delete'];
        $delete_query = "DELETE FROM tutors WHERE tutor_id = '$tutor_id'";
        mysqli_query($connection, $delete_query);
        header("Location: admin_tutors.php"); // Redirect to avoid form resubmission
    }

    if (isset($_POST['add'])) {
        $tutor_name = $_POST['add_tutor_name'];
        $bio = $_POST['add_bio'];
        $available_date = $_POST['add_available_date'];
        $expertise = $_POST['add_expertise'];
        $rating = $_POST['add_rating'];
    
        $add_query = "INSERT INTO tutors (tutor_name, Bio, available_date, Expertise, rating) 
                      VALUES ('$tutor_name', '$bio', '$available_date', '$expertise', '$rating')";
    
        if (mysqli_query($connection, $add_query)) {
            header("Location: admin_tutors.php"); // Redirect to refresh the page
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
                <li class="underline"><a href="admin_tutors.php">Tutors</a></li>
                <li><a href="admin_users.php">Users</a></li>
                <li><a href="admin_courses.php">Courses</a></li>
            </ul>
        </aside>
    </div>

    <div class="content" id="content">
        <h3>List of Tutors</h3>
        <button onclick="openAddModal()">Add Tutor</button>
        <table class="table_booking">
            <thead>
                <tr>
                    <th>Tutor Id</th>
                    <th>Tutor Name</th>
                    <th>Bio</th>
                    <th>Available Date</th>
                    <th>Expertise</th>
                    <th>Rating</th>
                    <th>Actions</th> 
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['tutor_id']; ?></td>
                    <td><?php echo $row['tutor_name']; ?></td>
                    <td><?php echo $row['Bio']; ?></td>
                    <td><?php echo $row['available_date']; ?></td>
                    <td><?php echo $row['Expertise']; ?></td>
                    <td><?php echo $row['rating']; ?></td>
                    <td>
                        <button onclick="openModal('<?php echo $row['tutor_id']; ?>', '<?php echo $row['tutor_name']; ?>', '<?php echo $row['available_date']; ?>', '<?php echo $row['Expertise']; ?>', '<?php echo $row['rating']; ?>')">Edit</button>
                        <button onclick="confirmDelete(<?php echo $row['tutor_id']; ?>)">Delete</button>
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
        <h2>Edit Tutors</h2>
        <form method="POST" action="admin_tutors.php">
            <input type="hidden" name="tutor_id" id="tutor_id">
            
            <div class="form-group">
                <label for="last_name">Tutor Name:</label>
                <input type="text" name="tutor_name" id="tutor_name">
            </div>

            <div class="form-group">
                <label for="Bio">Bio:</label>
                <input type="text" name="Bio" id="bio">
                </div>
            <div class="form-group">
                <label for="available_date">Available Date</label>
                <input type="date" name="available_date" id="available_date" >
            </div>
            <div class="form-group">
                <label for="Expertise">Expertise:</label>
                <input type="text" name="Expertise" id="expertise">
                </div>
            <div class="form-group">
                <label for="rating">Rating</label>
                <input type="text" name="rating" id="rating">
                </div>


            <input type="submit" name="update" value="Update" class="btn">
        </form>
    </div>
</div>
<!-- Add Tutor Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddModal()">&times;</span>
        <h2>Add New Tutor</h2>
        <form method="POST" action="admin_tutors.php">
            <div class="form-group">
                <label for="add_tutor_name">Tutor Name:</label>
                <input type="text" name="add_tutor_name" id="add_tutor_name" required>
            </div>

            <div class="form-group">
                <label for="add_bio">Bio:</label>
                <input type="text" name="add_bio" id="add_bio" required>
            </div>

            <div class="form-group">
                <label for="add_available_date">Available Date:</label>
                <input type="date" name="add_available_date" id="add_available_date" required>
            </div>

            <div class="form-group">
                <label for="add_expertise">Expertise:</label>
                <input type="text" name="add_expertise" id="add_expertise" required>
            </div>

            <div class="form-group">
                <label for="add_rating">Rating:</label>
                <input type="text" name="add_rating" id="add_rating" required>
            </div>

            <input type="submit" name="add" value="Add Tutor" class="btn">
        </form>
    </div>
</div>

    <script>
        function openModal(tutor_id, tutor_name, Bio, available_date, Expertise, rating) {
            document.getElementById('editModal').style.display = "block";
            document.getElementById('tutor_id').value = tutor_id;
            document.getElementById('tutor_name').value = tutor_name;
            document.getElementById(' Bio').value =  Bio;
            document.getElementById('available_date').value = available_date;
            document.getElementById('Expertise').value = Expertise;
            document.getElementById('rating').value = rating;
        }

        function closeModal() {
            document.getElementById('editModal').style.display = "none";
        }
        function confirmDelete(tutor_id) {
            if (confirm("Are you sure you want to delete this Tutor?")) {
                window.location.href = "admin_tutors.php?delete=" + tutor_id; // Redirect to delete the booking
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

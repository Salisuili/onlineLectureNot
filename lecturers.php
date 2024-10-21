<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header('location: Login.php');
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturers Management</title>
    <link rel="stylesheet" href="styles/css/lecturer_style.css">
</head>
<body>
    <style>
        .modal-content {
    max-width: 800px; /* Adjust as needed */
    margin: auto;
    padding: 20px;
}

.modal-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.form-group {
    flex: 0 0 48%; /* Keeps fields side by side, using 48% width */
    margin-bottom: 15px;
}

button.btn {
    background-color: #5cb85c; /* Green background for the submit button */
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    font-size: 16px;
    border-radius: 5px;
}

button.btn:hover {
    background-color: #4cae4c; /* Darker green on hover */
}

    </style>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
 
    <main class="main-content">
        <?php include 'navbar.php'; ?>
        <section class="lecturers-section">
            <h1>Manage Lecturers</h1>
            <button class="add-lecturer-button" onclick="openModal()">Add Lecturer</button>
            
            <!-- Form to Add Lecturer -->
            <div id="lecturerModal" class="modal">
                <div class="modal-content">
                    <span class="close-button" onclick="closeModal()">&times;</span>
                    <h2>Add a Lecturer</h2>
                    <form action="process_lecturer.php" method="POST" class="modal-form" onsubmit="return validateForm()">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="text" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="course">Course:</label>
                            <input type="text" id="course" name="course" required>
                        </div>
                        <div class="form-group">
                            <label for="department">Department:</label>
                            <input type="text" id="department" name="department" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn">Add Lecturer</button>
                    </form>
                </div>
            </div>

            <script>
            // JavaScript validation function
            function validateForm() {
                const phoneRegex = /^\+234\d{10}$|^0\d{10}$/;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                const name = document.getElementById("name").value;
                const address = document.getElementById("address").value;
                const phone = document.getElementById("phone").value;
                const course = document.getElementById("course").value;
                const department = document.getElementById("department").value;
                const email = document.getElementById("email").value;
                const username = document.getElementById("username").value;
                const password = document.getElementById("password").value;

                if (!name || !address || !phone || !course || !department || !email || !username || !password) {
                    alert("All fields are required.");
                    return false;
                }

                if (!phone.match(phoneRegex)) {
                    alert("Phone number must be 10 digits and start with country code.");
                    return false;
                }

                if (!email.match(emailRegex)) {
                    alert("Please enter a valid email address.");
                    return false;
                }

                return true;  // Allow form submission if all validations pass
            }
            </script>



            <!-- Display Current Lecturers -->
            <table class="lecturers-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Course</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'database.php';
                    

                    $sql = "SELECT * FROM lecturers";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['address']}</td>
                                <td>{$row['phone']}</td>
                                <td>{$row['course']}</td>
                                <td>{$row['department']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['username']}</td>
                                <td><a href='delete_lecturer.php?id={$row['lecturer_id']}'>Delete</a></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No lecturers found.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <script>
        function openModal() {
            document.getElementById("lecturerModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("lecturerModal").style.display = "none";
        }
    </script>
</body>
</html>

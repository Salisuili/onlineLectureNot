<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the username already exists
    $checkUsernameQuery = "SELECT * FROM lecturers WHERE username = '$username'";
    $result = $conn->query($checkUsernameQuery);

    if ($result === false) {
        // Query failed, debug and show alert
        echo "<script>alert('Database query failed: " . $conn->error . "');</script>";
        echo "<script>window.location.href = 'lecturers.php';</script>";
        exit();
    }

    if ($result->num_rows > 0) {
        // Username already exists
        $error_message = "Error: Username '$username' is already taken. Please choose a different username.";
        echo "<script>alert('$error_message');</script>";
        echo "<script>window.location.href = 'lecturers.php';</script>";
        exit();
    } else {
        // Proceed with inserting the new lecturer
        $sql = "INSERT INTO lecturers (name, address, phone, course, department, email, username, password) 
                VALUES ('$name', '$address', '$phone', '$course', '$department', '$email', '$username', '$password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Lecturer added successfully!";
            echo "<script>alert('Lecturer added successfully!');</script>";
            echo "<script>window.location.href = 'lecturers.php';</script>";
            exit();
        } else {
            $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
            echo "<script>alert('Error: " . $conn->error . "');</script>";
            echo "<script>window.location.href = 'lecturers.php';</script>";
            exit();
        }
    }

    $conn->close();
}
?>

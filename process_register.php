<?php
session_start();

require_once('database.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords
    if ($password !== $confirm_password) {
        $_SESSION['message'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }

    // Hash the password
    $hashed_password = $password;

    // Check if the username or email already exists
    $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "Username or email already exists.";
        header("Location: register.php");
        exit();
    }

    // Insert the new user into the database
    $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful!";
        header("Location: register.php");
        exit();
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        header("Location: register.php");
        exit();
    }
}

$conn->close();
?>

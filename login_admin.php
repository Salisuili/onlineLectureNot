<?php 
session_start();
require_once('database.php');

// Check if the request is a POST request
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Sanitize inputs
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Prepare and execute the SQL query
    $query = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password'";
    $result = $conn->query($query);

    if($result->num_rows > 0){
        // User found, proceed to login
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username']; 
        $_SESSION['login_status'] = 'success';
        header('Location: dashboard.php');
        exit; 
    }else{
        // User not found or password incorrect
        $_SESSION['login_status'] = 'failed';
        echo "<script>alert('Invalid username or password. Please try again.');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
        exit; 
    }
}
?>

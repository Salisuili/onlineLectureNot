<?php  
session_start();
require_once('database.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Sanitize inputs
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Prepare and execute the SQL query
    $query = "SELECT * FROM `lecturers` WHERE `username` = '$username'";
    $result = $conn->query($query);

    // Check if query execution was successful
    if ($result === false) {
        die("Database query failed: " . $conn->error);
    }

    // Check if the user exists
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        
        // Check if the password matches
        if ($password == $row['password']) {
            // Password matches, login successful
            $_SESSION['user_id'] = $row['lecturer_id']; 
            $_SESSION['username'] = $row['username'];
            $_SESSION['login_status'] = 'success';
            header('Location: dashboard.php');
            exit;
        } else {
            // Password incorrect
            $_SESSION['login_status'] = 'failed';
            echo "<script>alert('Invalid password. Please try again.');</script>";
            echo "<script>window.location.href = 'login.php';</script>";
            exit;
        }
    } else {
        // Username not found
        $_SESSION['login_status'] = 'failed';
        echo "<script>alert('Username not found. Please try again.');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
        exit;
    }
}
?>

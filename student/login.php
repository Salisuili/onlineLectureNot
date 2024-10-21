<?php 
session_start();
require_once('../database.php');

if(isset($_POST['submit'])){
    extract($_POST);

    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    $query = "SELECT * FROM `students` WHERE `username` = '$username' AND `password` = '$password'";
    $result = $conn->query($query);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['student_id'];
        $_SESSION['username'] = $row['username']; 
        $_SESSION['login_status'] = 'success';
        header('Location: dashboard.php');
        exit; 
    }else{
        $_SESSION['login_status'] = 'failed';
        header('Location: login.php');
        exit; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Online Lecture Notification</title>
    <link rel="stylesheet" href="outstyles/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1 style="margin-left: 15px;">Iya Abubakar Institute of ICT - ABU Zaria</h1>
            <nav>
                <ul>
                    <li style="margin-right: 15px;"><a href="index.php">Home</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <section class="login">
            <div class="container">
                <h2>Student Login</h2>
                <?php
                if (isset($_SESSION['login_status'])) {
                    if ($_SESSION['login_status'] == 'success') {
                        echo '<script>alert("Login Successfully!"); window.location="dashboard.php";</script>';
                    } elseif ($_SESSION['login_status'] == 'failed') {
                        echo '<script>alert("Invalid Username or Password!");</script>';
                    }
                    unset($_SESSION['login_status']); // Clear session variable after use
                }
                ?>
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button style="background-color: #5cb85c;" type="submit" name="submit" class="btn" >Login</button>
                </form>
                
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php 
session_start();

if(isset($_SESSION['user'])){
    header("Location:dashboard.php");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Academic Performance Evaluation</title>
    <link rel="stylesheet" href="outstyles/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1 style="margin-left: 15px;">Iya Abubakar Institute of ICT - ABU Zaria</h1>
            
        </div>
    </header>
    <main>
        <section class="intro">
            <div class="container">
                <h2>Welcome to the Academic Performance Evaluation System</h2>
                <p>
                    Our system is designed to streamline the evaluation process and provide real-time analysis of academic performance. 
                    With our web-based application, students, faculty, and administrators can efficiently manage and track academic progress.
                </p>
                <a href="login.php" class="cta" style="background-color: #5cb85c;">Login</a>&nbsp;
                <a href="register.php" class="cta" style="background-color: #5cb85c;">Register</a>
            </div>
        </section>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2024 Iya Abubakar Institute of ICT - ABU Zaria. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

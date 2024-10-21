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
    <title>Home - Real-Time Lecture Reminder</title>
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
                <h2>WELCOME TO IYA ABUBAKAR REAL-TIME LECTURE TIMETABLE AND TASK SCHEDULING SYSTEM</h2>
                <p>
                    Our system is designed to streamline and enhance the management of academic schedules and tasks, improving efficiency and communication within educational institutions. </p>
                <a href="login.php" class="cta" style="background-color: #5cb85c;">Login</a>&nbsp;
                
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

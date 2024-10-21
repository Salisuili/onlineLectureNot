<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gather and sanitize input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $date_of_birth = $_POST['date_of_birth'];
    $enrollment_year = mysqli_real_escape_string($conn, $_POST['enrollment_year']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);

    // Validation
    $errors = [];

    // Username validation (example: must be unique)
    $checkUsernameQuery = "SELECT * FROM students WHERE username = '$username'";
    $result = $conn->query($checkUsernameQuery);
    if ($result->num_rows > 0) {
        $errors[] = "Username '$username' is already taken. Please choose a different one.";
    }

    // Password validation (minimum 6 characters)
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Phone number validation (must start with +234 and followed by 10 digits)
    if (!preg_match('/^\+234\d{10}$/', $phone)) {
        $errors[] = "Phone number must be in the format +234XXXXXXXXXX, where X is a digit.";
    }

    // Date of Birth validation (must be a valid date)
    if (!DateTime::createFromFormat('Y-m-d', $date_of_birth)) {
        $errors[] = "Invalid date of birth format.";
    }

    // Enrollment Year validation (ensure it's a 4-digit number)
    if (!preg_match('/^\d{4}$/', $enrollment_year)) {
        $errors[] = "Enrollment year must be a 4-digit year.";
    }

    // If there are errors, display them and halt execution
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
        echo "<script>window.location.href = 'register.php';</script>";
        exit();
    }

    // Proceed to insert into the database
    $sql = "INSERT INTO students (username, password, first_name, last_name, email, phone, date_of_birth, enrollment_year, department) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Hash password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sssssssss", $username, $hashed_password, $first_name, $last_name, $email, $phone, $date_of_birth, $enrollment_year, $department);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!');</script>";
            header("Location: login.php");
            exit();
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
            header("Location: register.php");
            exit();
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="styles/css/register_style.css">
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
    <div class="register-container">
        <h1>Student Registration</h1>
        <form method="post" action="register.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone - Please use Country Code (+234)</label>
            <input type="text" id="phone" name="phone" placeholder='+2349010101010' required>

            <label for="date_of_birth">Date of Birth</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required>

            <label for="enrollment_year">Enrollment Year</label>
            <input type="text" id="enrollment_year" name="enrollment_year" required>

            <label for="department">Department</label>
            <input type="text" id="department" name="department" required>

            <button type="submit" style="background-color: #5cb85c;">Register</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

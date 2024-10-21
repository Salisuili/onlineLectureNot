<?php
require_once('database.php');

if (isset($_POST['class_id'])) {
    $class_id = mysqli_real_escape_string($conn, $_POST['class_id']);
    
    // Delete the class from the database
    $query = "DELETE FROM timetable WHERE timetable_id = '$class_id'";
    
    if ($conn->query($query)) {
        $_SESSION['error_message'] = 'Class deleted successfully!';
        header("Location: timetable.php");
    } else {
        $_SESSION['error_message'] = 'Error: ' . $conn->error;
        header("Location: timetable.php");
    }

    // Redirect back to the timetable page
    header('Location: timetable.php');
    exit;
} else {
    // If no class_id is provided, redirect back to timetable page
    header('Location: timetable.php');
    exit;
}

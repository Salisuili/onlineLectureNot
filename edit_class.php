<?php
require_once('database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = mysqli_real_escape_string($conn, $_POST['class_id']);
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $lecturer_name = mysqli_real_escape_string($conn, $_POST['lecturer_name']);
    $day = mysqli_real_escape_string($conn, $_POST['day']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    $query = "UPDATE timetable 
              SET course_name = '$course_name', 
                  lecturer_name = '$lecturer_name', 
                  day_of_week = '$day', 
                  start_time = '$start_time', 
                  end_time = '$end_time', 
                  location = '$location' 
              WHERE timetable_id = '$class_id'";

    if ($conn->query($query)) {
        $_SESSION['message'] = 'Class updated successfully!';
    } else {
        $_SESSION['error'] = 'Error updating class: ' . $conn->error;
    }

    header('Location: timetable.php');
    exit();
}
?>

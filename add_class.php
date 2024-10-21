<?php
include 'database.php';

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO timetable (course_id, day_of_week, start_time, end_time, location) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $course_id, $day_of_week, $start_time, $end_time, $location);

// Set parameters and execute
$course_id = $_POST['course_id'];
$day_of_week = $_POST['day_of_week'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$location = $_POST['location'];
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: timetable.php");
exit();
?>

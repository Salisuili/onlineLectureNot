<?php
session_start();
require_once('database.php');

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('location: Login.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input values
    $assessment_id = $_POST['assessment_id'];
    $course_id = $_POST['course_name']; // This will be the course_id selected from the dropdown
    $assessment_type = $_POST['assessment_type'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $total_marks = $_POST['total_marks'];
    $due_date = $_POST['due_date'];

    // Update the assignment in the database
    $sql = "UPDATE assessments SET course_id = ?, assessment_type = ?, title = ?, description = ?, total_marks = ?, due_date = ? WHERE assessment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssii", $course_id, $assessment_type, $title, $description, $total_marks, $due_date, $assessment_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Assignment updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update the assignment.";
    }

    $stmt->close();
    $conn->close();

    // Redirect to the assignments page
    header('location: assignments.php');
    exit;
} else {
    $_SESSION['error_message'] = "Invalid request method.";
    header('location: assignments.php');
    exit;
}
?>

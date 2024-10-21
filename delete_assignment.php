<?php
session_start();
require_once('database.php');

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('location: Login.php');
    exit;
}

// Check if an assignment ID is provided in the URL
if (isset($_GET['id'])) {
    $assessment_id = $_GET['id'];

    // Prepare and execute the delete statement
    $sql = "DELETE FROM assessments WHERE assessment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $assessment_id);

    if ($stmt->execute()) {
        // Redirect back to the assignments management page with a success message
        $_SESSION['success_message'] = "Assignment deleted successfully!";
        header('location: assignments.php');
    } else {
        // Redirect back with an error message if something went wrong
        $_SESSION['error_message'] = "Error deleting assignment. Please try again.";
        header('location: assignments.php');
    }

    // Close the statement
    $stmt->close();
} else {
    // If no ID is provided, redirect back to the assignments management page
    $_SESSION['error_message'] = "No assignment ID provided.";
    header('location: assignments.php');
}

// Close the database connection
$conn->close();
?>

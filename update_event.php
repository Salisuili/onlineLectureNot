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
    $event_id = $_POST['id'];
    $title = $_POST['title']; 
    $date = $_POST['date'];
    $event = $_POST['event'];
    
    $sql = "UPDATE events SET title = ?, `date` = ?, `event` = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $date, $event, $event_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Event updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update the event.";
    }

    $stmt->close();
    $conn->close();

    // Redirect to the assignments page
    header('location: events.php');
    exit;
} else {
    $_SESSION['error_message'] = "Invalid request method.";
    header('location: events.php');
    exit;
}
?>

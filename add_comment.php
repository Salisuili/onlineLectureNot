<?php
session_start();
require_once('database.php');

// Define the sanitizeInput function
function sanitizeInput($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars($input));
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location: Login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize inputs
    $message_id = sanitizeInput($conn, $_POST['message_id']);
    $user_id = $_SESSION['user_id'];
    $comment_content = sanitizeInput($conn, $_POST['comment_content']);

    // Insert the comment into the database
    $sql = "INSERT INTO comments (message_id, comment_content, timestamp) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $message_id, $comment_content);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Comment added successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to add comment.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the messages page
    header('location: messages.php');
    exit;
} else {
    $_SESSION['error_message'] = "Invalid request method.";
    header('location: messages.php');
    exit;
}
?>

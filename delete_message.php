<?php
require_once('database.php');

if (isset($_POST['message_id'])) {
    $message_id = mysqli_real_escape_string($conn, $_POST['message_id']);

    $deleteComments = "DELETE FROM comments WHERE message_id = '$message_id'";
    $conn->query($deleteComments); 

    $deleteNotifications = "DELETE FROM notifications WHERE message_id = '$message_id'";
    $conn->query($deleteNotifications); 

    $deleteMessage = "DELETE FROM messages WHERE message_id = '$message_id'";
    
    if ($conn->query($deleteMessage)) {
        $_SESSION['success_message'] = 'Message, comments, and notifications deleted successfully!';
    } else {
        $_SESSION['error_message'] = 'Error: ' . $conn->error;
    }

    header("Location: messages.php");
    exit;
} else {
    header('Location: messages.php');
    exit;
}
?>

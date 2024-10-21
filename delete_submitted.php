<?php
session_start();
require_once('database.php');

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

if (isset($_GET['file'])) {
    $file_path = urldecode($_GET['file']);

    // Delete the file from the server
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // Remove the file entry from the database
    $sql = "DELETE FROM submissions WHERE file_path = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $file_path);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['message'] = 'Assignment deleted successfully.';
    $_SESSION['message_type'] = 'success';
    header('location: submitted.php');
    exit;
} else {
    $_SESSION['message'] = 'Invalid request.';
    $_SESSION['message_type'] = 'error';
    header('location: submitted.php');
    exit;
}
?>

<?php
include 'database.php';

if (isset($_POST['notification_id'])) {
    $notification_id = intval($_POST['notification_id']);
    $sql = "UPDATE notifications SET read_status = 1 WHERE notification_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $notification_id);
    $stmt->execute();
    $stmt->close();
}
?>

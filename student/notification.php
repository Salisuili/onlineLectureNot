<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header('location: Login.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
include 'database.php';



// Fetch notifications from the database
$sql = "SELECT notification_id, title, message, notification_type, created_at, read_status FROM notifications ORDER BY created_at DESC";
$result = $conn->query($sql);

if (!$result) { 
    die('Error executing query: ' . $conn->error);
}

?>

<main class="main-content">
    <?php include 'navbar.php'; ?>
    <section class="notifications-section" style="width:80%;">
        <h1>Notifications</h1>
        <ul class="notification-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="notification-item" style="background-color: <?php echo $row['read_status'] == 0 ? 'lightgrey' : 'white'; ?>;">
                <a href="javascript:void(0);" onclick="markAsRead(<?php echo $row['notification_id']; ?>, 'message_detail.php?id=<?php echo $row['notification_id']; ?>')">
                    <h2><?php echo ucfirst(htmlspecialchars($row['title'])); ?></h2>
                    <p><?php echo htmlspecialchars(substr($row['message'], 0, 100)) . '...'; ?></p>
                    <span class="notification-time"><?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></span>
                </a>
            </li>

            <?php endwhile; ?>
        </ul>
    </section>
</main>

<script>
function markAsRead(notificationId, redirectUrl) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_notification.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            // Redirect to the single notification page after marking as read
            window.location.href = redirectUrl;
        }
    };
    xhr.send("notification_id=" + notificationId);
}
</script>

</body>
</html>


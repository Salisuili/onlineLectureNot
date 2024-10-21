<?php
session_start();
require_once('database.php');

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('location: Login.php');
    exit;
}

// Get the event ID from the URL
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Fetch current event details
    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if (!$event) {
        $_SESSION['error_message'] = "Event not found.";
        header('location: events.php');
        exit;
    }

    $stmt->close();
} else {
    $_SESSION['error_message'] = "No event ID provided.";
    header('location: events.php');
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="styles/css/events_style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <?php include 'navbar.php'; ?>
        <section class="assignments-section">
            <h1>Edit Assignment</h1>

            <form action="update_event.php" method="POST">
                        <div class="form-group">
                        <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                            <label for="title">Title:</label>
                            <input type="text" id="title" name="title" value="<?php echo $event['title'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="date">Date of Event:</label>
                            <input type="date" id="date" name="date" value="<?php echo $event['date'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="event">Event:</label>
                            <input id="event" name="event" rows="4" value="<?php echo $event['event'] ?>" required></input>
                        </div>
                        <button type="submit" class="btn">Update Event</button>
                    </form>
        </section>
    </main>
</body>
</html>

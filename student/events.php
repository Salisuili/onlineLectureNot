<?php
session_start();
require_once('database.php');

if (!isset($_SESSION['user_id'])) {
    header('location: Login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Management</title>
    <link rel="stylesheet" href="styles/css/events_style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <?php include 'navbar.php'; ?>
        <section class="events-section">
            <h1>Manage Events</h1>
            <!-- Display Current Events -->
            <table class="events-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Event</th>
                        <th>Date of Event</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'database.php';
                    
                    $sql = "SELECT * FROM events ORDER BY created_at DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['title']}</td>
                                <td>{$row['event']}</td>
                                <td>{$row['date']}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No events found.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <script>
        function openModal() {
            document.getElementById("eventModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("eventModal").style.display = "none";
        }
    </script>
</body>
</html>

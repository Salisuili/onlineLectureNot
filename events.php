<?php

session_start();
if(!isset($_SESSION['user_id'])){
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
            <button class="add-event-button" onclick="openModal()">Add Event</button>
            
            <!-- Form to Add Event --> 
            <div id="eventModal" class="modal">
                <div class="modal-content">
                    <span class="close-button" onclick="closeModal()">&times;</span>
                    <h2>Add an Event</h2>
                    <form action="process_event.php" method="POST">
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="date">Date of Event:</label>
                            <input type="date" id="date" name="date" required>
                        </div>
                        <div class="form-group">
                            <label for="event">Event:</label>
                            <textarea id="event" name="event" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="">Add Event</button>
                    </form>
                </div>
            </div>

            <!-- Display Current Events -->
            <table class="events-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Action</th>
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
                                <td>
                                <a href='edit_event.php?id={$row['id']}' class='edit-button'>Edit</a> |
                                <a href='delete_event.php?id={$row['id']}' class='delete-button'>Delete</a>
                                </td>
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

<!-- sidebar.php -->
<div class="sidebar">
    <ul>
        <li><a href="dashboard.php">Home</a></li>
        <li><a href="messages.php">Messages</a></li>
        <li><a href="timetable.php">Time Table</a></li>
        <?php if (isset($_SESSION['username']) && $_SESSION['username'] == 'admin'): ?>
            <li><a href="lecturers.php">Lecturers</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['username']) && $_SESSION['username'] != 'admin'): ?>
            <li><a href="assignments.php">Assignments</a></li>
        <?php endif; ?>
        <li><a href="events.php">Events</a></li>
        <li><a href="notification.php">Notifications</a></li>
    </ul>
</div>

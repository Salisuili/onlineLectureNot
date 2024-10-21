<?php
session_start();
require_once('database.php');

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

$lecturer_id = $_SESSION['user_id'];

// Fetch assignments submitted by students
$sql = "SELECT s.first_name, s.last_name, a.file_path, a.submission_date 
        FROM submissions a
        JOIN students s ON a.user_id = s.student_id
        ORDER BY a.submission_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments</title>
    <link rel="stylesheet" href="styles/css/notifications.css">
</head>
<body>
    <style type="text/css">
        table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 18px;
    text-align: left;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #8BC45A;
    color: white;
}

tr:hover {
    background-color: #f5f5f5;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

    </style>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">

        <section class="assignments-section" style="width:80%;">
            <div class="section-header">
                <h1><a href="assignments.php"><</a>&nbsp;Student Assignments</h1>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="notification <?php echo $_SESSION['message_type']; ?>">
                        <?php echo $_SESSION['message']; ?>
                    </div>
                    <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                <?php endif; ?>

            </div>

            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Student Name</th>
                        <th>File</th>
                        <th>Submission Time</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                        <td><?php echo basename($row['file_path']); ?></td>
                        <td><?php echo htmlspecialchars($row['submission_date']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($row['file_path']); ?>" download>Download</a> |
                            <a href="delete_submitted.php?file=<?php echo urlencode($row['file_path']); ?>" onclick="return confirm('Are you sure you want to delete this file?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No assignments submitted yet.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php //include 'footer.php'; ?>
</body>
</html>

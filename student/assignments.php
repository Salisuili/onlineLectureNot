<?php
session_start();
require_once('database.php');

if (!isset($_SESSION['user_id'])) {
    header('location: Login.php');
    exit;
}

// Fetch current submissions if any
$uploaded_file = '';
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['assessment_id'])) {
    $assessment_id = $_GET['assessment_id'];
    $sql = "SELECT file_path FROM submissions WHERE user_id = ? AND assessment_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $_SESSION['user_id'], $assessment_id);
        $stmt->execute();
        $stmt->bind_result($file_path);
        if ($stmt->fetch()) {
            $uploaded_file = $file_path;
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments Management</title>
    <link rel="stylesheet" href="styles/css/assignments_style.css">
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
    background-color: #f2f2f2;
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
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <?php include 'navbar.php'; ?>
        
        <section class="assignments-section" style="width:80%;">
            <div class="section-header">
               <h1>Assignments</h1>
                <!-- Display Notification -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="notification <?php echo $_SESSION['message_type']; ?>">
                        <?php echo $_SESSION['message']; ?>
                    </div>
                    <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                <?php endif; ?>
            </div>

            <!-- Display Current Assignments -->
            <table class="assignments-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Assessment Type</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Total Marks</th>
                        <th>Due Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM assessments ORDER BY due_date ASC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['course_name']}</td>
                                <td>{$row['assessment_type']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['description']}</td>
                                <td>{$row['total_marks']}</td>
                                <td>{$row['due_date']}</td>
                                <td>
                                    <button class='submit-button' onclick=\"openSubmitModal({$row['assessment_id']})\">Submit</button>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No assignments found.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody> 
            </table>
        </section>
    </main>

    <!-- Submission Modal -->
    <div id="submitModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeSubmitModal()">&times;</span>
            <h2>Submit Your Assignment</h2>
            <form action="submit_assignment.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="assessment_id" name="assessment_id">
                
                <div class="form-group">
                    <?php if ($uploaded_file): ?>
                        <p>Uploaded File: <a href="<?php echo $uploaded_file; ?>" download><?php echo basename($uploaded_file); ?></a></p>
                    <?php else: ?>
                        <p>No submission yet.</p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="assignment_file">Upload Assignment:</label>
                    <input type="file" id="assignment_file" name="assignment_file" required>
                </div>
                
                <button type="submit" class="btn">Submit Assignment</button>
            </form>
        </div>
    </div>

    <script>
        function openSubmitModal(assessmentId) {
            document.getElementById("assessment_id").value = assessmentId;
            document.getElementById("submitModal").style.display = "block";
        }

        function closeSubmitModal() {
            document.getElementById("submitModal").style.display = "none";
        }
    </script>
</body>
</html>

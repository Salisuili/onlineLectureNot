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
    <title>Assignments Management</title>
    <link rel="stylesheet" href="styles/css/assignments_style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <main class="main-content"> 
        <?php include 'navbar.php'; ?>
        <section class="assignments-section">
            <h1>Manage Assignments</h1>
            <button class="add-assignment-button" onclick="openModal()">Add Assignment</button>
            <a href="submitted.php">View Submited Assignments</a>
            <!-- Form to Add Assignment -->
<div id="assignmentModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h2>Add an Assignment</h2>
        <form action="process_assignment.php" method="POST">
            <div class="form-group">
                <label for="course_name">Course Name:</label>
                <select id="course_name" name="course_name" required>
                    <option value="" disabled selected>Select Course</option>
                    <?php
                    include 'database.php';

                    $course_sql = "SELECT course_id, course_name FROM courses";
                    $course_result = $conn->query($course_sql);

                    if ($course_result->num_rows > 0) {
                        while ($course_row = $course_result->fetch_assoc()) {
                            echo "<option value='{$course_row['course_name']}'>{$course_row['course_name']}</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No courses available</option>";
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="assessment_type">Assessment Type:</label>
                <input type="text" id="assessment_type" name="assessment_type" required>
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="total_marks">Total Marks:</label>
                <input type="number" id="total_marks" name="total_marks" required>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date:</label>
                <input type="date" id="due_date" name="due_date" required>
            </div>
            <button type="submit" class="">Add Assignment</button>
        </form>
    </div>
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
                    include 'database.php';
                    
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
                                    <a href='edit_assignment.php?id={$row['assessment_id']}' class='edit-button'>Edit</a> |
                                    <a href='delete_assignment.php?id={$row['assessment_id']}' class='delete-button'>Delete</a>
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

    <script>
        function openModal() {
            document.getElementById("assignmentModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("assignmentModal").style.display = "none";
        }
    </script>
</body>
</html>

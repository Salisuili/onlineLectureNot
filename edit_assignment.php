<?php
session_start();
require_once('database.php');

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('location: Login.php');
    exit;
}

// Get the assignment ID from the URL
if (isset($_GET['id'])) {
    $assessment_id = $_GET['id'];

    // Fetch current assignment details
    $sql = "SELECT * FROM assessments WHERE assessment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $assessment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $assignment = $result->fetch_assoc();

    if (!$assignment) {
        $_SESSION['error_message'] = "Assignment not found.";
        header('location: assignments.php');
        exit;
    }

    // Fetch the list of courses
    $course_sql = "SELECT course_id, course_name FROM courses";
    $course_result = $conn->query($course_sql);

    $stmt->close();
} else {
    $_SESSION['error_message'] = "No assignment ID provided.";
    header('location: assignments.php');
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Assignment</title>
    <link rel="stylesheet" href="styles/css/assignments_style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <?php include 'navbar.php'; ?>
        <section class="assignments-section">
            <h1>Edit Assignment</h1>

            <form action="update_assignment.php" method="POST">
                <input type="hidden" name="assessment_id" value="<?php echo $assignment['assessment_id']; ?>">

                <div class="form-group">
                    <label for="course_name">Course Name:</label>
                    <select id="course_name" name="course_name" required>
                        <option value="" disabled>Select Course</option>
                        <?php
                        if ($course_result->num_rows > 0) {
                            while ($course_row = $course_result->fetch_assoc()) {
                                $selected = $assignment['course_id'] == $course_row['course_id'] ? 'selected' : '';
                                echo "<option value='{$course_row['course_id']}' $selected>{$course_row['course_name']}</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No courses available</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="assessment_type">Assessment Type:</label>
                    <input type="text" id="assessment_type" name="assessment_type" value="<?php echo $assignment['assessment_type']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo $assignment['title']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" required><?php echo $assignment['description']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="total_marks">Total Marks:</label>
                    <input type="number" id="total_marks" name="total_marks" value="<?php echo $assignment['total_marks']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date:</label>
                    <input type="date" id="due_date" name="due_date" value="<?php echo $assignment['due_date']; ?>" required>
                </div>
                <button type="submit" class="btn">Update Assignment</button>
            </form>
        </section>
    </main>
</body>
</html>
